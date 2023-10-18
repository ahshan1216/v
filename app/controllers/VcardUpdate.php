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
use Altum\Title;
use Altum\Uploads;

class VcardUpdate extends Controller {

    public function index() {

        Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('vcards');
        }


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

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, true, $vcard->vcard_id);

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Get available pixels */
        $pixels = (new \Altum\Models\Pixel())->get_pixels($this->user->user_id);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = mb_substr(trim(Database::clean_string($_POST['name'])), 0, 256);
            $_POST['description'] = mb_substr(trim(Database::clean_string($_POST['description'])), 0, 512);
            $_POST['domain_id'] = isset($_POST['domain_id']) && isset($domains[$_POST['domain_id']]) ? (!empty($_POST['domain_id']) ? (int) $_POST['domain_id'] : null) : null;
            $_POST['is_main_vcard'] = (bool) isset($_POST['is_main_vcard']) && isset($domains[$_POST['domain_id']]) && $domains[$_POST['domain_id']]->type == 0;
            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
            $_POST['password'] = !empty($_POST['password']) ?
                ($_POST['password'] != $vcard->password ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $vcard->password)
                : null;
            $_POST['background_type'] = in_array($_POST['background_type'], ['preset', 'color', 'gradient', 'image']) ? $_POST['background_type'] : 'preset';
            $_POST['is_enabled'] = (int) (bool) isset($_POST['is_enabled']);
            $_POST['is_share_button_visible'] = (int) (bool) isset($_POST['is_share_button_visible']);
            $_POST['is_download_button_visible'] = (int) (bool) isset($_POST['is_download_button_visible']);
            $_POST['is_se_visible'] = $this->user->plan_settings->search_engine_block_is_enabled ? (int) (bool) isset($_POST['is_se_visible']) : 1;
            $_POST['is_removed_branding'] = (int) (bool) isset($_POST['is_removed_branding']);
            $_POST['custom_css'] = mb_substr(trim(filter_var($_POST['custom_css'], FILTER_SANITIZE_STRING)), 0, 8192);
            $_POST['custom_js'] = mb_substr(trim($_POST['custom_js']), 0, 8192);
            $_POST['pixels_ids'] = isset($_POST['pixels_ids']) ? array_map(
                function($pixel_id) {
                    return (int) $pixel_id;
                },
                array_filter($_POST['pixels_ids'], function($pixel_id) use($pixels) {
                    return array_key_exists($pixel_id, $pixels);
                })
            ) : [];
            $_POST['pixels_ids'] = json_encode($_POST['pixels_ids']);
            switch($_POST['background_type']) {
                case 'preset':
                    $background_presets = require APP_PATH . 'includes/v/background_presets.php';
                    $_POST['background_preset'] = array_key_exists($_POST['background_preset'], $background_presets) ? Database::clean_string($_POST['background_preset']) : array_key_first($background_presets);
                    break;

                case 'color':
                    $_POST['background_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_color']) ? '#ffffff' : $_POST['background_color'];
                    break;

                case 'gradient':
                    $_POST['background_gradient_one'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_gradient_one']) ? '#ffffff' : $_POST['background_gradient_one'];
                    $_POST['background_gradient_two'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_gradient_two']) ? '#ffffff' : $_POST['background_gradient_two'];
                    break;

                case 'image':
                    /* :) */
                    break;
            }
            $fonts = require APP_PATH . 'includes/v/fonts.php';
            $_POST['font_family'] = array_key_exists($_POST['font_family'], $fonts) ? Database::clean_string($_POST['font_family']) : false;
            $_POST['font_size'] = (int) $_POST['font_size'] < 14 || (int) $_POST['font_size'] > 22 ? 16 : (int) $_POST['font_size'];
            $themes = require APP_PATH . 'includes/v/themes.php';
            $_POST['theme'] = array_key_exists($_POST['theme'], $themes) ? Database::clean_string($_POST['theme']) : 'new-york';

            $_POST['first_name'] = mb_substr(trim(Database::clean_string($_POST['first_name'])), 0, 64);
            $_POST['last_name'] = mb_substr(trim(Database::clean_string($_POST['last_name'])), 0, 64);
            $_POST['company'] = mb_substr(trim(Database::clean_string($_POST['company'])), 0, 64);
            $_POST['job_title'] = mb_substr(trim(Database::clean_string($_POST['job_title'])), 0, 64);
            $_POST['birthday'] = mb_substr(trim(Database::clean_string($_POST['birthday'])), 0, 16);

            $settings = $vcard->settings ?? (new \StdClass());

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
            if(
                ($_POST['url'] && $this->user->plan_settings->custom_url_is_enabled && $_POST['url'] != $vcard->url)
                || ($vcard->domain_id != $_POST['domain_id'])
            ) {
                $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                $is_existing_vcard = database()->query("SELECT `vcard_id` FROM `vcards` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;

                if(array_key_exists($_POST['url'], Router::$routes[''])) {
                    Alerts::add_field_error('url', l('vcard.error_message.blacklisted_url'));
                }

                if(!empty($_POST['url']) && in_array($_POST['url'], explode(',', settings()->vcards->blacklisted_keywords))) {
                    Alerts::add_field_error('url', l('vcard.error_message.blacklisted_keyword'));
                }

                if($is_existing_vcard) {
                    Alerts::add_field_error('url', l('vcard.error_message.url_exists'));
                }
            }

            $images = [
                'logo' => [
                    'is_uploaded' => !empty($_FILES['logo']['name']) && !isset($_POST['logo_remove']),
                ],
                'favicon' => [
                    'is_uploaded' => !empty($_FILES['favicon']['name']) && !isset($_POST['favicon_remove']),
                ],
                'opengraph' => [
                    'is_uploaded' => !empty($_FILES['opengraph']['name']) && !isset($_POST['opengraph_remove']),
                ],
                'background' => [
                    'is_uploaded' => !empty($_FILES['background']['name']) && !isset($_POST['background_remove']),
                ],
            ];

            foreach($images as $image_key => $image) {
                $settings->{$image_key} = $settings->{$image_key} ?? null;
                $settings->{$image_key . '_size'} = $settings->{$image_key . '_size'} ?? null;

                if($image['is_uploaded']) {
                    $file_name = $_FILES[$image_key]['name'];
                    $file_extension = explode('.', $file_name);
                    $file_extension = mb_strtolower(end($file_extension));
                    $file_temp = $_FILES[$image_key]['tmp_name'];

                    if($_FILES[$image_key]['error'] == UPLOAD_ERR_INI_SIZE) {
                        Alerts::add_error(sprintf(l('global.error_message.file_size_limit'), settings()->vcards->{$image_key . '_size_limit'}));
                    }

                    if($_FILES[$image_key]['error'] && $_FILES[$image_key]['error'] != UPLOAD_ERR_INI_SIZE) {
                        Alerts::add_error(l('global.error_message.file_upload'));
                    }

                    if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('vcards/' . $image_key))) {
                        Alerts::add_field_error($image_key, l('global.error_message.invalid_file_type'));
                    }

                    if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                        if(!is_writable(UPLOADS_PATH . 'vcards/' . $image_key . '/')) {
                            Alerts::add_field_error($image_key, sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . 'vcards/' . $image_key . '/'));
                        }
                    }

                    if($_FILES[$image_key]['size'] > settings()->vcards->{$image_key . '_size_limit'} * 1000000) {
                        Alerts::add_field_error($image_key, sprintf(l('global.error_message.file_size_limit'), settings()->vcards->{$image_key . '_size_limit'}));
                    }

                    if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                        /* Generate new name */
                        $file_new_name = md5(time() . rand()) . '.' . $file_extension;

                        /* Try to compress the image */
                        if(\Altum\Plugin::is_active('image-optimizer')) {
                            \Altum\Plugin\ImageOptimizer::optimize($file_temp, $file_new_name);
                        }

                        /* Offload uploading */
                        if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                            try {
                                $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                                /* Delete current image */
                                $s3->deleteObject([
                                    'Bucket' => settings()->offload->storage_name,
                                    'Key' => 'uploads/vcards/' . $image_key . '/' . $vcard->settings->{$image_key},
                                ]);

                                /* Upload image */
                                $result = $s3->putObject([
                                    'Bucket' => settings()->offload->storage_name,
                                    'Key' => 'uploads/vcards/' . $image_key . '/' . $file_new_name,
                                    'ContentType' => mime_content_type($file_temp),
                                    'SourceFile' => $file_temp,
                                    'ACL' => 'public-read'
                                ]);
                            } catch (\Exception $exception) {
                                Alerts::add_error($exception->getMessage());
                            }
                        }

                        /* Local uploading */
                        else {
                            /* Delete current file */
                            if(!empty($vcard->settings->{$image_key}) && file_exists(UPLOADS_PATH . 'vcards/' . $image_key . '/' . $vcard->settings->{$image_key})) {
                                unlink(UPLOADS_PATH . 'vcards/' . $image_key . '/' . $vcard->settings->{$image_key});
                            }

                            /* Upload the original */
                            move_uploaded_file($file_temp, UPLOADS_PATH . 'vcards/' . $image_key . '/' . $file_new_name);
                        }

                        /* Database query */
                        $settings->{$image_key} = $file_new_name;
                        $settings->{$image_key . '_size'} = $_FILES[$image_key]['size'];
                    }
                }

                if(isset($_POST[$image_key . '_remove'])) {
                    /* Offload deleting */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        $s3 = new \Aws\S3\S3Client(get_aws_s3_config());
                        $s3->deleteObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => 'uploads/vcards/' . $image_key . '/' . $vcard->settings->{$image_key},
                        ]);
                    }

                    /* Local deleting */
                    else {
                        /* Delete current file */
                        if(!empty($vcard->settings->{$image_key}) && file_exists(UPLOADS_PATH . 'vcards/' . $image_key . '/' . $vcard->settings->{$image_key})) {
                            unlink(UPLOADS_PATH . 'vcards/' . $image_key . '/' . $vcard->settings->{$image_key});
                        }
                    }

                    /* Database query */
                    $settings->{$image_key} = null;
                    $settings->{$image_key . '_size'} = null;
                }
            }


            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings->is_share_button_visible = $_POST['is_share_button_visible'];
                $settings->is_download_button_visible = $_POST['is_download_button_visible'];
                $settings->background_type = $_POST['background_type'];
                $settings->background_preset = $_POST['background_preset'];
                $settings->background_color = $_POST['background_color'];
                $settings->background_gradient_one = $_POST['background_gradient_one'];
                $settings->background_gradient_two = $_POST['background_gradient_two'];
                $settings->font_family = $_POST['font_family'];
                $settings->font_size = $_POST['font_size'];
                $settings->first_name = $_POST['first_name'];
                $settings->last_name = $_POST['last_name'];
                $settings->company = $_POST['company'];
                $settings->job_title = $_POST['job_title'];
                $settings->birthday = $_POST['birthday'];
                $settings = json_encode($settings);

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
                db()->where('vcard_id', $vcard->vcard_id)->update('vcards', [
                    'domain_id' => $_POST['domain_id'],
                    'project_id' => $_POST['project_id'],
                    'pixels_ids' => $_POST['pixels_ids'],
                    'url' => $_POST['url'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'theme' => $_POST['theme'],
                    'settings' => $settings,
                    'password' => $_POST['password'],
                    'is_se_visible' => $_POST['is_se_visible'],
                    'is_removed_branding' => $_POST['is_removed_branding'],
                    'custom_css' => $_POST['custom_css'],
                    'custom_js' => $_POST['custom_js'],
                    'is_enabled' => $_POST['is_enabled'],
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Update custom domain if needed */
                if($_POST['is_main_vcard']) {

                    /* If the main vcard of a particular domain is changing, update the old domain as well to "free" it */
                    if($_POST['domain_id'] != $vcard->domain_id) {
                        /* Database query */
                        db()->where('domain_id', $vcard->domain_id)->update('domains', [
                            'vcard_id' => null,
                            'last_datetime' => \Altum\Date::$date,
                        ]);
                    }

                    /* Database query */
                    db()->where('domain_id', $_POST['domain_id'])->update('domains', [
                        'vcard_id' => $vcard_id,
                        'last_datetime' => \Altum\Date::$date,
                    ]);

                    /* Clear the cache */
                    \Altum\Cache::$adapter->deleteItemsByTag('domain_id=' . $_POST['domain_id']);
                }

                /* Update old main custom domain if needed */
                if(!$_POST['is_main_vcard'] && $vcard->domain_id && $domains[$vcard->domain_id]->vcard_id == $vcard->vcard_id) {
                    /* Database query */
                    db()->where('domain_id', $vcard->domain_id)->update('domains', [
                        'vcard_id' => null,
                        'last_datetime' => \Altum\Date::$date,
                    ]);

                    /* Clear the cache */
                    \Altum\Cache::$adapter->deleteItemsByTag('domain_id=' . $_POST['domain_id']);
                }

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('vcard_id=' . $vcard_id);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . filter_var($_POST['name'], FILTER_SANITIZE_STRING) . '</strong>'));

                redirect('vcard-update/' . $vcard->vcard_id);
            }

        }

        /* Set a custom title */
        Title::set(sprintf(l('vcard_update.title'), $vcard->name));

        /* Prepare the View */
        $data = [
            'pixels' => $pixels,
            'domains' => $domains,
            'projects' => $projects,
            'vcard' => $vcard
        ];

        $view = new \Altum\Views\View('vcard-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
