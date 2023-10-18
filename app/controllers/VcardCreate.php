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
use Altum\Routing\Router;

class VcardCreate extends Controller {

    public function index() {

        Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('vcards');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `vcards` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->vcards_limit != -1 && $total_rows >= $this->user->plan_settings->vcards_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('vcards');
        }

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url_is_enabled ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = mb_substr(trim(Database::clean_string($_POST['name'])), 0, 256);
            $_POST['description'] = mb_substr(trim(Database::clean_string($_POST['description'])), 0, 512);
            $_POST['domain_id'] = isset($_POST['domain_id']) && isset($domains[$_POST['domain_id']]) ? (!empty($_POST['domain_id']) ? (int) $_POST['domain_id'] : null) : null;
            $_POST['is_main_vcard'] = (bool) isset($_POST['is_main_vcard']) && isset($domains[$_POST['domain_id']]) && $domains[$_POST['domain_id']]->type == 0;

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for duplicate url if needed */
            if($_POST['url']) {
                $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                $is_existing_vcard = database()->query("SELECT `vcard_id` FROM `vcards` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;

                if($is_existing_vcard) {
                   Alerts::add_field_error('url', l('vcard.error_message.url_exists'));
                }

                if(array_key_exists($_POST['url'], Router::$routes[''])) {
                    Alerts::add_field_error('url', l('vcard.error_message.blacklisted_url'));
                }

                if(!empty($_POST['url']) && in_array($_POST['url'], explode(',', settings()->vcards->blacklisted_keywords))) {
                    Alerts::add_field_error('url', l('vcard.error_message.blacklisted_keyword'));
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $theme = 'new-york';
                $settings = json_encode([
                    'is_share_button_visible' => true,
                    'is_download_button_visible' => true,
                    'background_type' => 'preset',
                    'background_color' => '#ffffff',
                    'background_preset' => 'one',
                    'background_gradient_one' => '#ffffff',
                    'background_gradient_two' => '#ffffff',
                    'background' => '',
                    'font_family' => false,
                    'font_size' => 16,
                    'favicon' => '',
                    'logo' => '',
                    'opengraph' => '',
                ]);

                if(!$_POST['url']) {
                    $is_existing_vcard = true;

                    /* Generate random url if not specified */
                    while($is_existing_vcard) {
                        $_POST['url'] = mb_strtolower(string_generate(10));

                        $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                        $is_existing_vcard = database()->query("SELECT `vcard_id` FROM `vcards` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;
                    }
                }

                /* Prepare the statement and execute query */
                $vcard_id = db()->insert('vcards', [
                    'user_id' => $this->user->user_id,
                    'domain_id' => $_POST['domain_id'],
                    'pixels_ids' => json_encode([]),
                    'url' => $_POST['url'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'settings' => $settings,
                    'theme' => $theme,
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Update custom domain if needed */
                if($_POST['is_main_vcard']) {
                    /* Database query */
                    db()->where('domain_id', $_POST['domain_id'])->update('domains', ['vcard_id' => $vcard_id, 'last_datetime' => \Altum\Date::$date]);

                    /* Clear the cache */
                    \Altum\Cache::$adapter->deleteItemsByTag('domain_id=' . $_POST['domain_id']);
                }

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . filter_var($_POST['name'], FILTER_SANITIZE_STRING) . '</strong>'));

                redirect('vcard-update/' . $vcard_id);
            }

        }

        /* Set default values */
        $values = [
            'url' => $_POST['url'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'domain_id' => $_POST['domain_id'] ?? '',
            'is_main_vcard' => $_POST['is_main_vcard'] ?? '',
        ];

        /* Prepare the View */
        $data = [
            'domains' => $domains,
            'values' => $values
        ];

        $view = new \Altum\Views\View('vcard-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
