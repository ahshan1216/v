<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Vcard extends Model {

    public function get_vcard_full_url($vcard, $user, $domains = null) {

        /* Detect the URL of the vcard */
        if($vcard->domain_id) {

            /* Get available custom domains */
            if(!$domains) {
                $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($user, false);
            }

            if(isset($domains[$vcard->domain_id])) {

                if($vcard->vcard_id == $domains[$vcard->domain_id]->vcard_id) {

                    $vcard->full_url = $domains[$vcard->domain_id]->scheme . $domains[$vcard->domain_id]->host . '/';

                } else {

                    $vcard->full_url = $domains[$vcard->domain_id]->scheme . $domains[$vcard->domain_id]->host . '/' . $vcard->url . '/';

                }

            }

        } else {

            $vcard->full_url = SITE_URL . $vcard->url . '/';

        }

        return $vcard->full_url;
    }

    public function get_vcard_by_vcard_id($vcard_id) {

        /* Get the vcard */
        $vcard = null;

        /* Try to check if the vcard posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('v_vcard?vcard_id=' . $vcard_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $vcard = database()->query("SELECT * FROM `vcards` WHERE `vcard_id` = '{$vcard_id}'")->fetch_object() ?? null;

            if($vcard) {
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($vcard)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('vcard_id=' . $vcard->vcard_id)
                );
            }

        } else {

            /* Get cache */
            $vcard = $cache_instance->get();

        }

        return $vcard;

    }

    public function delete($vcard_id) {

        $vcard = db()->where('vcard_id', $vcard_id)->getOne('vcards', ['vcard_id', 'settings']);

        if(!$vcard) return;

        $vcard->settings = json_decode($vcard->settings);

        /* Offload deleting */
        if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

            foreach(['favicons', 'logos', 'opengraph', 'background'] as $image_name) {
                if(!empty($vcard->settings->{$image_name})) {
                    $s3->deleteObject([
                        'Bucket' => settings()->offload->storage_name,
                        'Key' => 'uploads/vcards/' . $image_name . '/' . $vcard->settings->{$image_name},
                    ]);
                }
            }
        }

        /* Local deleting */
        else {
            foreach(['favicons', 'logos', 'opengraph', 'background'] as $image_name) {
                if(!empty($vcard->settings->{$image_name}) && file_exists(UPLOADS_PATH . 'vcards/' . $image_name . '/' . $vcard->settings->{$image_name})) {
                    unlink(UPLOADS_PATH . 'vcards/' . $image_name . '/' . $vcard->settings->{$image_name});
                }
            }
        }

        /* Delete the vcard */
        db()->where('vcard_id', $vcard_id)->delete('vcards');

        /* Clear cache */
        \Altum\Cache::$adapter->deleteItemsByTag('vcard_id=' . $vcard_id);

    }

}
