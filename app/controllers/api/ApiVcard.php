<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Response;
use Altum\Traits\Apiable;

class ApiVcard extends Controller {
    use Apiable;

    public function index() {

        $this->verify_request();

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

            break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->api_user, false);

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));
        $filters->set_default_order_by('vcard_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `vcards` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/vcards?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `vcards`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {
            $row->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($row, $this->api_user, $domains);

            /* Prepare the data */
            $row = [
                'id' => (int) $row->vcard_id,
                'domain_id' => (int) $row->domain_id,
                'project_id' => (int) $row->project_id,
                'pixels_ids' => json_decode($row->pixels_ids),
                'url' => $row->url,
                'full_url' => $row->full_url,
                'name' => $row->name,
                'description' => $row->description,
                'settings' => json_decode($row->settings),
                'password' => (bool) $row->password,
                'timezone' => $row->timezone,
                'theme' => $row->theme,
                'custom_js' => $row->custom_js,
                'custom_css' => $row->custom_css,
                'pageviews' => (int) $row->pageviews,
                'is_se_visible' => (bool) $row->is_se_visible,
                'is_removed_branding' => (bool) $row->is_removed_branding,
                'is_enabled' => (bool) $row->is_enabled,
                'datetime' => $row->datetime
            ];

            $data[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        /* Prepare the pagination links */
        $others = ['links' => [
            'first' => $paginator->getPageUrl(1),
            'last' => $paginator->getNumPages() ? $paginator->getPageUrl($paginator->getNumPages()) : null,
            'next' => $paginator->getNextUrl(),
            'prev' => $paginator->getPrevUrl(),
            'self' => $paginator->getPageUrl($_GET['page'] ?? 1)
        ]];

        Response::jsonapi_success($data, $meta, 200, $others);
    }

    private function get() {

        $vcard_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $vcard = db()->where('vcard_id', $vcard_id)->where('user_id', $this->api_user->user_id)->getOne('vcards');

        /* We haven't found the resource */
        if(!$vcard) {
            Response::jsonapi_error([[
                'title' => l('api.error_message.not_found'),
                'status' => '404'
            ]], null, 404);
        }

        $vcard->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($vcard, $this->api_user);

        /* Prepare the data */
        $data = [
            'id' => (int) $vcard->vcard_id,
            'domain_id' => (int) $vcard->domain_id,
            'project_id' => (int) $vcard->project_id,
            'pixels_ids' => json_decode($vcard->pixels_ids),
            'url' => $vcard->url,
            'full_url' => $vcard->full_url,
            'name' => $vcard->name,
            'description' => $vcard->description,
            'settings' => json_decode($vcard->settings),
            'password' => (bool) $vcard->password,
            'timezone' => $vcard->timezone,
            'theme' => $vcard->theme,
            'custom_js' => $vcard->custom_js,
            'custom_css' => $vcard->custom_css,
            'pageviews' => (int) $vcard->pageviews,
            'is_se_visible' => (bool) $vcard->is_se_visible,
            'is_removed_branding' => (bool) $vcard->is_removed_branding,
            'is_enabled' => (bool) $vcard->is_enabled,
            'datetime' => $vcard->datetime
        ];

        Response::jsonapi_success($data);

    }

}
