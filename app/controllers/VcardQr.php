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
use Altum\Middlewares\Authentication;
use Altum\Title;

class VcardQr extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->user->plan_settings->qr_is_enabled) {
            Alerts::add_info(l('global.info_message.plan_feature_no_access'));
            redirect('vcards');
        }

        $vcard_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$vcard = db()->where('vcard_id', $vcard_id)->where('user_id', $this->user->user_id)->getOne('vcards')) {
            redirect('vcards');
        }

        /* Genereate the vcard full URL base */
        $vcard->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($vcard, $this->user);

        /* Delete Modal */
        $view = new \Altum\Views\View('vcard/vcard_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Set a custom title */
        Title::set(sprintf(l('vcard_qr.title'), $vcard->name));

        /* Prepare the View */
        $data = [
            'vcard' => $vcard
        ];

        $view = new \Altum\Views\View('vcard-qr/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
