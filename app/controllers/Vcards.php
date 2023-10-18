<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class Vcards extends Controller {

    public function index() {
        Authentication::guard();

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, false);

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'project_id'], ['name', 'url'], ['datetime', 'name', 'url', 'pageviews']));
        $filters->set_default_order_by('vcard_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `vcards` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('vcards?' . $filters->get_get() . '&page=%d')));

        /* Get the vcards */
        $vcards = [];
        $vcards_result = database()->query("
            SELECT
                *
            FROM
                `vcards`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}

            {$paginator->get_sql_limit()}
        ");
        while($row = $vcards_result->fetch_object()) {
            $row->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($row, $this->user, $domains);
            $row->settings = json_decode($row->settings);
            $vcards[] = $row;
        }

        /* Export handler */
        process_export_csv($vcards, 'include', ['vcard_id', 'user_id', 'domain_id', 'project_id', 'pixels_ids', 'url', 'name', 'description', 'pageviews', 'is_se_visible', 'is_removed_branding', 'is_enabled', 'datetime'], sprintf(l('vcards.title')));
        process_export_json($vcards, 'include', ['vcard_id', 'user_id', 'domain_id', 'project_id', 'pixels_ids', 'url', 'name', 'description', 'pageviews', 'is_se_visible', 'is_removed_branding', 'is_enabled', 'datetime'], sprintf(l('vcards.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Prepare the View */
        $data = [
            'projects' => $projects,
            'vcards' => $vcards,
            'total_vcards' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\Views\View('vcards/index', (array) $this);

        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {
        Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('vcards');
        }


        if(empty($_POST)) {
            redirect('vcards');
        }

        $vcard_id = (int) Database::clean_string($_POST['vcard_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('vcards');
        }

        /* Make sure the vcard id is created by the logged in user */
        if(!$vcard = db()->where('vcard_id', $vcard_id)->where('user_id', $this->user->user_id)->getOne('vcards', ['vcard_id', 'name'])) {
            redirect('vcards');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new \Altum\Models\Vcard())->delete($vcard->vcard_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $vcard->name . '</strong>'));

            redirect('vcards');

        }

        redirect('vcards');
    }

}
