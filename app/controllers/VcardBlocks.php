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
use Altum\Response;
use Altum\Title;

class VcardBlocks extends Controller {

    public function index() {

        Authentication::guard();

        $vcard_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$vcard = db()->where('vcard_id', $vcard_id)->where('user_id', $this->user->user_id)->getOne('vcards')) {
            redirect('vcards');
        }

        /* Genereate the vcard full URL base */
        $vcard->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($vcard, $this->user);

        /* Parse some details */
        foreach(['settings', 'pixels_ids'] as $key) {
            $vcard->{$key} = json_decode($vcard->{$key});
        }

        /* Get all the available vcard blocks */
        $vcard_blocks = [];
        $result = database()->query("SELECT * FROM `vcards_blocks` WHERE `vcard_id` = {$vcard_id} ORDER BY `order`");
        while($row = $result->fetch_object()) {
            $row->settings = json_decode($row->settings);
            $vcard_blocks[$row->vcard_block_id] = $row;
        }

        /* Set a custom title */
        Title::set(sprintf(l('vcard_blocks.title'), $vcard->name));

        $available_vcards_blocks = require_once APP_PATH . 'includes/v/vcards_blocks.php';

        /* Prepare the View */
        $data = [
            'vcard' => $vcard,
            'vcard_blocks' => $vcard_blocks,
            'available_vcards_blocks' => $available_vcards_blocks,
        ];

        $view = new \Altum\Views\View('vcard-blocks/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    /* AJAX */
    public function create() {
        Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        if(empty($_POST)) {
            redirect();
        }

        $available_vcards_blocks = require_once APP_PATH . 'includes/v/vcards_blocks.php';
        $_POST['vcard_id'] = (int) $_POST['vcard_id'];
        $_POST['type'] = array_key_exists($_POST['type'], $available_vcards_blocks) ? Database::clean_string($_POST['type']) : 'link';
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['value'] = mb_substr(trim(Database::clean_string($_POST['value'])), 0, $available_vcards_blocks[$_POST['type']]['value_max_length']);

        $settings = json_encode([
            'open_in_new_tab' => false,
        ]);

        if(!$vcard = db()->where('vcard_id', $_POST['vcard_id'])->where('user_id', $this->user->user_id)->getOne('vcards')) {
            redirect();
        }

        /* Make sure that the user didn't exceed the limit */
        $total_vcard_blocks = db()->where('vcard_id', $vcard->vcard_id)->getValue('vcards_blocks', 'count(`vcard_block_id`)');
        if($this->user->plan_settings->vcard_blocks_limit != -1 && $total_vcard_blocks >= $this->user->plan_settings->vcard_blocks_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Check for any errors */
        $required_fields = ['name', 'value'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(!Csrf::check()) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        /* Extra checks if needed */
        if(in_array($_POST['type'], ['link'])) {
            /* Make sure it is an actual link */
            if(!parse_url($_POST['value'], PHP_URL_SCHEME)) {
                Response::json(l('vcard.error_message.invalid_url'), 'error');
            }

            /* Make sure the domain is not blacklisted */
            $domain = get_domain_from_url($_POST['value']);
            if($domain && in_array($domain, explode(',', settings()->vcards->blacklisted_domains))) {
                Response::json(l('vcard.error_message.blacklisted_domain'), 'error');

            }

            /* Check the url with google safe browsing to make sure it is a safe website */
            if(settings()->vcards->google_safe_browsing_is_enabled) {
                if(google_safe_browsing_check($_POST['value'], settings()->vcards->google_safe_browsing_api_key)) {
                    Response::json(l('vcard.error_message.blacklisted_url'), 'error');
                }
            }
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            /* Prepare the statement and execute query */
            db()->insert('vcards_blocks', [
                'user_id' => $this->user->user_id,
                'vcard_id' => $vcard->vcard_id,
                'type' => $_POST['type'],
                'name' => $_POST['name'],
                'value' => $_POST['value'],
                'order' => $total_vcard_blocks,
                'settings' => $settings,
                'is_enabled' => 1,
                'datetime' => \Altum\Date::$date,
            ]);

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('v_vcard_blocks?vcard_id=' . $vcard->vcard_id);

            /* Set a nice success message */
            Response::json(sprintf(l('global.success_message.create1'), '<strong>' . filter_var($_POST['name'], FILTER_SANITIZE_STRING) . '</strong>'));
        }

        redirect('vcard-blocks/' . $vcard->vcard_id);
    }

    /* AJAX */
    public function update() {
        Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        if(empty($_POST)) {
            redirect();
        }

        if(!$vcard_block = db()->where('vcard_block_id', $_POST['vcard_block_id'])->where('user_id', $this->user->user_id)->getOne('vcards_blocks')) {
            redirect();
        }

        $available_vcards_blocks = require_once APP_PATH . 'includes/v/vcards_blocks.php';
        $_POST['vcard_block_id'] = (int) $_POST['vcard_block_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['value'] = mb_substr(trim(Database::clean_string($_POST['value'])), 0, $available_vcards_blocks[$vcard_block->type]['value_max_length']);
        $_POST['is_enabled'] = (int) (bool) isset($_POST['is_enabled']);
        $_POST['open_in_new_tab'] = (int) (bool) isset($_POST['open_in_new_tab']);

        $settings = json_encode([
            'open_in_new_tab' => $_POST['open_in_new_tab'],
        ]);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Check for any errors */
        $required_fields = ['name', 'value'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(!Csrf::check()) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        /* Extra checks if needed */
        if(in_array($vcard_block->type, ['link'])) {

            /* Make sure it is an actual link */
            if(!parse_url($_POST['value'], PHP_URL_SCHEME)) {
                Response::json(l('vcard.error_message.invalid_url'), 'error');
            }

            /* Make sure the domain is not blacklisted */
            $domain = get_domain_from_url($_POST['value']);
            if($domain && in_array($domain, explode(',', settings()->vcards->blacklisted_domains))) {
                Response::json(l('vcard.error_message.blacklisted_domain'), 'error');
            }

            /* Check the url with google safe browsing to make sure it is a safe website */
            if(settings()->vcards->google_safe_browsing_is_enabled) {
                if(google_safe_browsing_check($_POST['value'], settings()->vcards->google_safe_browsing_api_key)) {
                    Response::json(l('vcard.error_message.blacklisted_url'), 'error');
                }
            }
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            /* Prepare the statement and execute query */
            db()->where('vcard_block_id', $vcard_block->vcard_block_id)->update('vcards_blocks', [
                'name' => $_POST['name'],
                'value' => $_POST['value'],
                'settings' => $settings,
                'is_enabled' => $_POST['is_enabled'],
                'last_datetime' => \Altum\Date::$date,
            ]);

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('v_vcard_blocks?vcard_id=' . $vcard_block->vcard_id);

            /* Set a nice success message */
            Response::json(sprintf(l('global.success_message.update1'), '<strong>' . filter_var($_POST['name'], FILTER_SANITIZE_STRING) . '</strong>'));
        }

        redirect('vcard-blocks/' . $vcard_block->vcard_id);
    }

    /* AJAX */
    public function reorder() {
        Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST = json_decode(file_get_contents('php://input'), true);

        if(empty($_POST)) {
            redirect();
        }

        if(isset($_POST['vcard_blocks']) && is_array($_POST['vcard_blocks'])) {
            foreach($_POST['vcard_blocks'] as $link) {
                if(!isset($link['vcard_block_id']) || !isset($link['order'])) {
                    continue;
                }
                $link['vcard_block_id'] = (int) $link['vcard_block_id'];
                $link['order'] = (int) $link['order'];

                /* Update the link order */
                db()->where('vcard_block_id', $link['vcard_block_id'])->where('user_id', $this->user->user_id)->update('vcards_blocks', ['order' => $link['order']]);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('vcard_block_id=' . $link['vcard_block_id']);
            }
        }

        die();
    }

    public function delete() {

        Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        if(empty($_POST)) {
            redirect('dashboard');
        }

        $_POST['vcard_block_id'] = (int) $_POST['vcard_block_id'];

        if(!$vcard_block = db()->where('vcard_block_id', $_POST['vcard_block_id'])->where('user_id', $this->user->user_id)->getOne('vcards_blocks')) {
            redirect('dashboard');
        }

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Prepare the statement and execute query */
            db()->where('vcard_block_id', $vcard_block->vcard_block_id)->delete('vcards_blocks');

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('v_vcard_blocks?vcard_id=' . $vcard_block->vcard_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . filter_var($vcard_block->name, FILTER_SANITIZE_STRING) . '</strong>'));

            redirect('vcard-blocks/' . $vcard_block->vcard_id);
        }

        redirect('vcard-blocks/' . $vcard_block->vcard_id);
    }

}
