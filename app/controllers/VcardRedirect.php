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
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Title;

class VcardRedirect extends Controller {

    public function index() {

        $vcard_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$vcard = db()->where('vcard_id', $vcard_id)->getOne('vcards', ['vcard_id', 'domain_id', 'user_id', 'url'])) {
            redirect();
        }

        $this->vcard_user = (new User())->get_user_by_user_id($vcard->user_id);

        /* Genereate the vcard full URL base */
        $vcard->full_url = (new \Altum\Models\Vcard())->get_vcard_full_url($vcard, $this->vcard_user);

        header('Location: ' . $vcard->full_url);

        die();

    }
}
