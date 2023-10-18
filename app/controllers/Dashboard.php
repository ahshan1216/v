<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class Dashboard extends Controller {

    public function index() {

        Authentication::guard();

        /* Get some stats */
        $total_vcards = db()->where('user_id', $this->user->user_id)->getValue('vcards', 'count(`vcard_id`)');
        $total_pixels = db()->where('user_id', $this->user->user_id)->getValue('pixels', 'count(`pixel_id`)');
        $total_projects = db()->where('user_id', $this->user->user_id)->getValue('projects', 'count(`project_id`)');
        $total_domains = db()->where('user_id', $this->user->user_id)->getValue('domains', 'count(`domain_id`)');

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, false);

        /* Get the vcards */
        $vcards = [];
        $vcards_result = database()->query("SELECT * FROM `vcards` WHERE `user_id` = {$this->user->user_id} ORDER BY `vcard_id` DESC LIMIT 5");
        while($row = $vcards_result->fetch_object()) {
            $row->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($row, $this->user, $domains);
            $row->settings = json_decode($row->settings);
            $vcards[] = $row;
        }

        /* Prepare the View */
        $data = [
            'vcards' => $vcards,
            'projects' => $projects,
            'total_vcards' => $total_vcards,
            'total_pixels' => $total_pixels,
            'total_projects' => $total_projects,
            'total_domains' => $total_domains,
        ];

        $view = new \Altum\Views\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
