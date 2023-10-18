<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class Directory extends Controller {

    public function index() {

        if(!settings()->vcards->directory_is_enabled) {
            redirect();
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['url', 'name'], ['pageviews', 'url', 'name']));
        $filters->set_default_order_by('pageviews', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Get data from the database */
        $domains = [];
        $domains_result = database()->query("SELECT * FROM `domains`");
        while($row = $domains_result->fetch_object()) {
            $row->url = $row->scheme . $row->host . '/';
            $domains[$row->domain_id] = $row;
        }

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `vcards` WHERE `is_enabled` = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('directory?' . $filters->get_get() . '&page=%d')));

        /* Get the vcards list for the project */
        $vcards_result = database()->query("
            SELECT 
                `vcards`.*, `domains`.`scheme`, `domains`.`host`
            FROM 
                `vcards`
            LEFT JOIN 
                `domains` ON `vcards`.`domain_id` = `domains`.`domain_id`
            WHERE 
                `vcards`.`is_enabled` = 1
                {$filters->get_sql_where('vcards')}
                {$filters->get_sql_order_by('vcards')}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the vcards */
        $vcards = [];

        while($row = $vcards_result->fetch_object()) {
            $row->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($row, null, $domains);
            $row->settings = json_decode($row->settings);
            $vcards[] = $row;
        }

        /* Export handler */
        process_export_csv($vcards, 'include', ['name', 'url', 'full_url', 'pageviews'], sprintf(l('vcards.title')));
        process_export_json($vcards, 'include', ['name', 'url', 'full_url', 'pageviews'], sprintf(l('vcards.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'vcards'             => $vcards,
            'pagination'        => $pagination,
            'filters'           => $filters,
        ];

        $view = new \Altum\Views\View('directory/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


