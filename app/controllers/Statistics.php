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
use Altum\Title;

class Statistics extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->user->plan_settings->analytics_is_enabled) {
            Alerts::add_info(l('global.info_message.plan_feature_no_access'));
            redirect('vcards');
        }

        if(isset($_GET['vcard_id'])) {
            $vcard_id = isset($_GET['vcard_id']) ? (int) $_GET['vcard_id'] : null;

            if(!$vcard = Database::get('*', 'vcards', ['vcard_id' => $vcard_id, 'user_id' => $this->user->user_id])) {
                redirect('vcards');
            }

            /* Genereate the vcard full URL base */
            $vcard->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($vcard, $this->user);

            $identifier_name = 'vcard';
            $identifier_key = 'vcard_id';
            $identifier_value = $vcard->vcard_id;
        }

        elseif(isset($_GET['vcard_block_id'])) {
            $vcard_block_id = isset($_GET['vcard_block_id']) ? (int) $_GET['vcard_block_id'] : null;

            if(!$vcard_block = Database::get('*', 'vcards_blocks', ['vcard_block_id' => $vcard_block_id, 'user_id' => $this->user->user_id])) {
                redirect('vcards');
            }

            if(!$vcard = Database::get('*', 'vcards', ['vcard_id' => $vcard_block->vcard_id, 'user_id' => $this->user->user_id])) {
                redirect('vcards');
            }

            /* Genereate the vcard full URL base */
            $vcard->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($vcard, $this->user);

            $identifier_name = 'vcard_block';
            $identifier_key = 'vcard_block_id';
            $identifier_value = $vcard_block->vcard_block_id;

        } else {
            redirect('vcards');
        }

        /* Statistics related variables */
        $type = isset($_GET['type']) && in_array($_GET['type'], ['overview', 'referrer_host', 'referrer_path', 'country', 'city_name', 'os', 'browser', 'device', 'language', 'utm_source', 'utm_medium', 'utm_campaign']) ? Database::clean_string($_GET['type']) : 'overview';

        $datetime = \Altum\Date::get_start_end_dates_new();

        /* Get the required statistics */
        $pageviews = [];
        $pageviews_chart = [];

        $pageviews_result = database()->query("
            SELECT
                COUNT(`id`) AS `pageviews`,
                SUM(`is_unique`) AS `visitors`,
                DATE_FORMAT(`datetime`, '{$datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `statistics`
            WHERE
                `{$identifier_key}` = {$identifier_value}
                AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");

        /* Generate the raw chart data and save pageviews for later usage */
        while($row = $pageviews_result->fetch_object()) {
            $pageviews[] = $row;

            $row->formatted_date = $datetime['process']($row->formatted_date);

            $pageviews_chart[$row->formatted_date] = [
                'pageviews' => $row->pageviews,
                'visitors' => $row->visitors
            ];
        }

        $pageviews_chart = get_chart_data($pageviews_chart);

        /* Get data based on what statistics are needed */
        switch($type) {
            case 'overview':

                $result = database()->query("
                    SELECT
                        *
                    FROM
                        `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    ORDER BY
                        `datetime` DESC
                    LIMIT 25
                ");

                break;

            case 'referrer_host':
            case 'country':
            case 'os':
            case 'browser':
            case 'device':
            case 'language':

                $columns = [
                    'referrer_host' => 'referrer_host',
                    'referrer_path' => 'referrer_path',
                    'country' => 'country_code',
                    'city_name' => 'city_name',
                    'os' => 'os_name',
                    'browser' => 'browser_name',
                    'device' => 'device_type',
                    'language' => 'browser_language'
                ];

                $result = database()->query("
                    SELECT
                        `{$columns[$type]}`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `{$columns[$type]}`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'referrer_path':

                $referrer_host = trim(Database::clean_string($_GET['referrer_host']));

                $result = database()->query("
                    SELECT
                        `referrer_path`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND `referrer_host` = '{$referrer_host}'
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `referrer_path`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'city_name':

                $country_code = trim(Database::clean_string($_GET['country_code']));

                $result = database()->query("
                    SELECT
                        `city_name`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND `country_code` = '{$country_code}'
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `city_name`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'utm_source':

                $result = database()->query("
                    SELECT
                        `utm_source`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                        AND `utm_source` IS NOT NULL
                    GROUP BY
                        `utm_source`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'utm_medium':

                $utm_source = trim(Database::clean_string($_GET['utm_source']));

                $result = database()->query("
                    SELECT
                        `utm_medium`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND `utm_source` = '{$utm_source}'
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `utm_medium`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;

            case 'utm_campaign':

                $utm_source = trim(Database::clean_string($_GET['utm_source']));
                $utm_medium = trim(Database::clean_string($_GET['utm_medium']));

                $result = database()->query("
                    SELECT
                        `utm_campaign`,
                        COUNT(*) AS `total`
                    FROM
                         `statistics`
                    WHERE
                        `{$identifier_key}` = {$identifier_value}
                        AND `utm_source` = '{$utm_source}'
                        AND `utm_medium` = '{$utm_medium}'
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `utm_campaign`
                    ORDER BY
                        `total` DESC
                    LIMIT 250
                ");

                break;
        }

        switch($type) {
            case 'overview':

                $statistics_keys = [
                    'country_code',
                    'referrer_host',
                    'device_type',
                    'os_name',
                    'browser_name',
                    'browser_language'
                ];

                $statistics = [];
                foreach($statistics_keys as $key) {
                    $statistics[$key] = [];
                    $statistics[$key . '_total_sum'] = 0;
                }

                /* Start processing the rows from the database */
                while($row = $result->fetch_object()) {
                    foreach($statistics_keys as $key) {

                        $statistics[$key][$row->{$key}] = isset($statistics[$key][$row->{$key}]) ? $statistics[$key][$row->{$key}] + 1 : 1;

                        $statistics[$key . '_total_sum']++;

                    }
                }

                foreach($statistics_keys as $key) {
                    arsort($statistics[$key]);
                }

                /* Prepare the statistics method View */
                $data = [
                    'statistics' => $statistics,
                    'vcard' => $vcard,
                    'datetime' => $datetime,
                ];

                break;

            case 'referrer_host':
            case 'country':
            case 'city_name':
            case 'os':
            case 'browser':
            case 'device':
            case 'language':
            case 'referrer_path':
            case 'utm_source':
            case 'utm_medium':
            case 'utm_campaign':

                /* Store all the results from the database */
                $statistics = [];
                $statistics_total_sum = 0;

                while($row = $result->fetch_object()) {
                    $statistics[] = $row;

                    $statistics_total_sum += $row->total;
                }

                /* Prepare the statistics method View */
                $data = [
                    'rows' => $statistics,
                    'total_sum' => $statistics_total_sum,
                    'vcard' => $vcard,
                    'datetime' => $datetime,

                    'referrer_host' => $referrer_host ?? null,
                    'country_code' => $country_code ?? null,
                    'utm_source' => $utm_source ?? null,
                    'utm_medium' => $utm_medium ?? null,
                ];

            break;
        }

        /* Export handler */
        process_export_csv($statistics, 'basic');
        process_export_json($statistics, 'basic');

        $data = array_merge($data, [
            'identifier_name' => $identifier_name,
            'identifier_key' => $identifier_key,
            'identifier_value' => $identifier_value,
        ]);
        $data['type'] = $type;
        $view = new \Altum\Views\View('statistics/statistics_' . $type, (array) $this);
        $this->add_view_content('statistics', $view->run($data));

        /* Delete Modal */
        $view = new \Altum\Views\View('vcard/vcard_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Set a custom title */
        Title::set(sprintf(l('vcard_statistics.title'), $vcard->name));

        /* Prepare the View */
        $data = [
            'identifier_name' => $identifier_name,
            'identifier_key' => $identifier_key,
            'identifier_value' => $identifier_value,
            'vcard' => $vcard,
            'vcard_block' => $vcard_block ?? null,
            'type' => $type,
            'datetime' => $datetime,
            'pageviews' => $pageviews,
            'pageviews_chart' => $pageviews_chart
        ];

        $view = new \Altum\Views\View('statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function reset() {

        Authentication::guard();

        if(!$this->user->plan_settings->analytics_is_enabled) {
            redirect('vcards');
        }

        if(empty($_POST)) {
            redirect('vcards');
        }

        $vcard_id = (int) Database::clean_string($_POST['vcard_id']);
        $datetime = \Altum\Date::get_start_end_dates_new($_POST['start_date'], $_POST['end_date']);

        /* Make sure the vcard id is created by the logged in user */
        if(!$vcard = db()->where('vcard_id', $vcard_id)->where('user_id', $this->user->user_id)->getOne('vcards', ['vcard_id', 'name'])) {
            redirect('vcards');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('statistics?vcard_id=' . $vcard->vcard_id);
        }

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('statistics?vcard_id=' . $vcard->vcard_id);
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Clear statistics data */
            database()->query("DELETE FROM `statistics` WHERE `vcard_id` = {$vcard->vcard_id} AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')");

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.update2'));

            redirect('statistics?vcard_id=' . $vcard->vcard_id);

        }

        redirect('statistics?vcard_id=' . $vcard->vcard_id);

    }

}
