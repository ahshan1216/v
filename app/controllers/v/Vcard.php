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
use Altum\Date;
use Altum\Meta;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Models\VcardBlock;
use Altum\Routing\Router;
use Altum\Title;
use MaxMind\Db\Reader;

class Vcard extends Controller {
    public $vcard = null;
    public $vcard_blocks = null;
    public $vcard_user = null;
    public $available_vcards_blocks = null;
    public $has_access = null;

    public function index() {

        $this->vcard = Router::$data['vcard'];

        /* Make sure the vcard is active */
        if(!$this->vcard->is_enabled) {
            redirect();
        }

        $this->vcard_user = (new User())->get_user_by_user_id($this->vcard->user_id);

        /* Make sure to check if the user is active */
        if($this->vcard_user->status != 1) {
            redirect();
        }

        /* Process the plan of the user */
        (new User())->process_user_plan_expiration_by_user($this->vcard_user);

        /* Check if the user has access to the vcard */
        $this->has_access = !$this->vcard->password || ($this->vcard->password && isset($_COOKIE['vcard_password_' . $this->vcard->vcard_id]) && $_COOKIE['vcard_password_' . $this->vcard->vcard_id] == $this->vcard->password);

        /* Do not let the user have password protection if the plan doesnt allow it */
        if(!$this->vcard_user->plan_settings->password_protection_is_enabled) {
            $this->has_access = true;
        }

        /* Parse some details */
        foreach(['settings', 'pixels_ids'] as $key) {
            $this->vcard->{$key} = json_decode($this->vcard->{$key});
        }

        /* Set the default language of the user */
        \Altum\Language::set_by_name($this->vcard_user->language);

        /* Check if the password form is submitted */
        if(!$this->has_access && !empty($_POST)) {

            /* Check for any errors */
            if(!Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!password_verify($_POST['password'], $this->vcard->password)) {
                Alerts::add_field_error('password', l('v_vcard.password.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Set a cookie */
                setcookie('vcard_password_' . $this->vcard->vcard_id, $this->vcard->password, time()+60*60*24*30);

                header('Location: ' . $this->vcard->full_url); die();

            }

        }

        /* Display the password form */
        if(!$this->has_access) {

            /* Set a custom title */
            Title::set(l('v_vcard.password.title'));

            /* Main View */
            $data = [
                'vcard' => $this->vcard,
            ];

            $view = new \Altum\Views\View('v/vcard/' . $this->vcard->theme . '/password', (array) $this);

        }

        /* No password or access granted */
        else {

            /* Get all the vcard blocks */
            $this->vcard_blocks = $vcard_blocks = (new VcardBlock())->get_vcard_blocks_by_vcard_id($this->vcard->vcard_id);
            $this->available_vcards_blocks = require_once APP_PATH . 'includes/v/vcards_blocks.php';

            /* Check if we should add stats for a vcard or vcard block */
            if(isset($_GET['vcard_block_id'])) {
                $vcard_block_id = (int) $_GET['vcard_block_id'];

                if(array_key_exists($vcard_block_id, $vcard_blocks)) {
                    $this->create_statistics(null, $vcard_block_id);
                    die();
                }
            }

            $this->create_statistics($this->vcard->vcard_id);

            /* Process the vcard download */
            $this->process_vcard();

            /* Set a custom title */
            Title::set($this->vcard->name);

            /* Set the meta tags */
            Meta::set_description(string_truncate($this->vcard->description, 200));
            Meta::set_social_url($this->vcard->full_url);
            Meta::set_social_title($this->vcard->name);
            Meta::set_social_description(string_truncate($this->vcard->description, 200));
            Meta::set_social_image(!empty($this->vcard->settings->opengraph) ? UPLOADS_FULL_URL . 'vcards/opengraph/' . $this->vcard->settings->opengraph : null);

            if(count($this->vcard->pixels_ids ?? [])) {
                /* Get the needed pixels */
                $pixels = (new \Altum\Models\Pixel())->get_pixels_by_pixels_ids_and_user_id($this->vcard->pixels_ids, $this->vcard->user_id);

                /* Prepare the pixels view */
                $pixels_view = new \Altum\Views\View('v/partials/pixels');
                $this->add_view_content('pixels', $pixels_view->run(['pixels' => $pixels]));
            }

            /* Main View */
            $data = [
                'vcard' => $this->vcard,
                'vcard_user' => $this->vcard_user,
                'vcard_blocks' => $vcard_blocks,
                'available_vcards_blocks' => $this->available_vcards_blocks,
            ];

            $view = new \Altum\Views\View('v/vcard/' . $this->vcard->theme . '/index', (array) $this);
        }

        $this->add_view_content('content', $view->run($data));
    }

    /* Insert statistics log */
    private function create_statistics($vcard_id = null, $vcard_block_id = null) {

        $cookie_name = 'v_statistics_' . ($vcard_id ? '_' . $vcard_id : null) . ($vcard_block_id ? '_' . $vcard_block_id : null);

        if(isset($_COOKIE[$cookie_name]) && (int) $_COOKIE[$cookie_name] >= 3) {
            return;
        }

        if(!$this->vcard_user->plan_settings->analytics_is_enabled) {
            return;
        }

        /* Detect extra details about the user */
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

        /* Do not track bots */
        if($whichbrowser->device->type == 'bot') {
            return;
        }

        /* Detect extra details about the user */
        $browser_name = $whichbrowser->browser->name ?? null;
        $os_name = $whichbrowser->os->name ?? null;
        $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
        $is_unique = isset($_COOKIE[$cookie_name]) ? 0 : 1;

        /* Detect the location */
        try {
            $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());
        } catch(\Exception $exception) {
            /* :) */
        }
        $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
        $city_name = isset($maxmind) && isset($maxmind['city']) ? $maxmind['city']['names']['en'] : null;

        /* Process referrer */
        $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;

        if(!isset($referrer)) {
            $referrer = [
                'host' => null,
                'path' => null
            ];
        }

        /* Check if the referrer comes from the same location */
        if(isset($referrer) && isset($referrer['host']) && $referrer['host'] == parse_url($this->vcard->full_url)['host']) {
            $is_unique = 0;

            $referrer = [
                'host' => null,
                'path' => null
            ];
        }

        /* Check if referrer actually comes from the QR code */
        if(isset($_GET['referrer']) && in_array($_GET['referrer'], ['qr', 'vcard'])) {
            $referrer = [
                'host' => $_GET['referrer'],
                'path' => null
            ];
        }

        $utm_source = $_GET['utm_source'] ?? null;
        $utm_medium = $_GET['utm_medium'] ?? null;
        $utm_campaign = $_GET['utm_campaign'] ?? null;

        /* Insert the log */
        db()->insert('statistics', [
            'vcard_id' => $vcard_id,
            'vcard_block_id' => $vcard_block_id,
            'country_code' => $country_code,
            'city_name' => $city_name,
            'os_name' => $os_name,
            'browser_name' => $browser_name,
            'referrer_host' => $referrer['host'],
            'referrer_path' => $referrer['path'],
            'device_type' => $device_type,
            'browser_language' => $browser_language,
            'utm_source' => $utm_source,
            'utm_medium' => $utm_medium,
            'utm_campaign' => $utm_campaign,
            'is_unique' => $is_unique,
            'datetime' => Date::$date,
        ]);

        /* Add the unique hit to the vcard table as well */
        if($vcard_id) {
            db()->where('vcard_id', $vcard_id)->update('vcards', ['pageviews' => db()->inc()]);
        } elseif($vcard_block_id) {
            db()->where('vcard_block_id', $vcard_block_id)->update('vcards_blocks', ['pageviews' => db()->inc()]);
        }

        /* Set cookie to try and avoid multiple entrances */
        $cookie_new_value = isset($_COOKIE[$cookie_name]) ? (int) $_COOKIE[$cookie_name] + 1 : 0;
        setcookie($cookie_name, (int) $cookie_new_value, time()+60*60*24*1);
    }

    private function process_vcard() {
        if(isset($_GET['export']) && $_GET['export'] == 'vcard') {

            $vcard = new \JeroenDesloovere\VCard\VCard();
            $vcard->addURL($this->vcard->full_url);
            $vcard->addNote($this->vcard->description);

            if(empty($this->vcard->settings->first_name) && empty($this->vcard->settings->last_name)) {
                $vcard->addName($this->vcard->name);
            } else {
                $vcard->addName($this->vcard->settings->last_name, $this->vcard->settings->first_name);
            }

            if($this->vcard->settings->company) $vcard->addCompany($this->vcard->settings->company);
            if($this->vcard->settings->job_title) $vcard->addJobtitle($this->vcard->settings->job_title);
            if($this->vcard->settings->birthday) $vcard->addBirthday($this->vcard->settings->birthday);

            /* Check if we should try to add the image to the vcard */
            if($this->vcard->settings->logo && $this->vcard->settings->logo_size && $this->vcard->settings->logo_size <= 0.75 * 1000000) {
                $vcard->addPhoto(UPLOADS_FULL_URL . 'vcards/logo/' . $this->vcard->settings->logo);
            }

            foreach($this->vcard_blocks as $vcard_block) {
                switch($vcard_block->type) {
                    case 'email':
                        $vcard->addEmail($vcard_block->value, 'PREF');
                        break;

                    case 'phone':
                        $vcard->addPhoneNumber($vcard_block->value, 'PREF');
                        break;

                    case 'address':
                        $vcard->addAddress($vcard_block->value);
                        break;

                    default:
                        $vcard->addURL(
                            sprintf($this->available_vcards_blocks[$vcard_block->type]['format'], $vcard_block->value),
                            'TYPE=' . l('vcards_blocks.' . $vcard_block->type));
                        break;
                }
            }

            $vcard->download();

            die();

        }
    }
}
