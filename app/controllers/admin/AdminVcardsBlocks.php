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
use Altum\Middlewares\Csrf;

class AdminVcardsBlocks extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'user_id', 'vcard_id', 'type'], ['name', 'value'], ['datetime', 'name', 'value', 'pageviews']));
        $filters->set_default_order_by('vcard_block_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `vcards_blocks` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/vcards-blocks?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $vcards_blocks = [];
        $vcards_blocks_result = database()->query("
            SELECT
                `vcards_blocks`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `vcards_blocks`
            LEFT JOIN
                `users` ON `vcards_blocks`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('vcards_blocks')}
                {$filters->get_sql_order_by('vcards_blocks')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $vcards_blocks_result->fetch_object()) {
            $vcards_blocks[] = $row;
        }

        /* Export handler */
        process_export_csv($vcards_blocks, 'include', ['vcard_block_id', 'vcard_id', 'user_id', 'type', 'name', 'value', 'pageviews', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('vcards_blocks.title')));
        process_export_json($vcards_blocks, 'include', ['vcard_block_id', 'vcard_id', 'user_id', 'type', 'name', 'value', 'settings', 'pageviews', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('vcards_blocks.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'vcards_blocks' => $vcards_blocks,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('admin/vcards-blocks/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/vcards-blocks');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/vcards-blocks');
        }

        if(!isset($_POST['type']) || (isset($_POST['type']) && !in_array($_POST['type'], ['delete']))) {
            redirect('admin/vcards-blocks');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $vcard_block_id) {

                        /* Delete the domain_name */
                        db()->where('vcard_block_id', $vcard_block_id)->delete('vcards_blocks');

                        /* Clear the cache */
                        \Altum\Cache::$adapter->deleteItemsByTag('vcard_block_id=' . $vcard_block_id);

                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('admin_bulk_delete_modal.success_message'));

        }

        redirect('admin/vcards-blocks');
    }

    public function delete() {

        $vcard_block_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$vcard_block = db()->where('vcard_block_id', $vcard_block_id)->getOne('vcards_blocks', ['vcard_block_id', 'vcard_id', 'name'])) {
            redirect('admin/vcards-blocks');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Prepare the statement and execute query */
            db()->where('vcard_block_id', $vcard_block->vcard_block_id)->delete('vcards_blocks');

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('vcard_id=' . $vcard_block->vcard_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $vcard_block->name . '</strong>'));

        }

        redirect('admin/vcards-blocks');
    }

}
