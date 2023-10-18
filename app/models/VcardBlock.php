<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class VcardBlock extends Model {

    public function get_vcard_blocks_by_vcard_id($vcard_id) {

        /* Get the vcard blocks */
        $vcard_blocks = [];

        /* Try to check if the vcard blocks exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('v_vcard_blocks?vcard_id=' . $vcard_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $result = database()->query("SELECT * FROM `vcards_blocks` WHERE `vcard_id` = {$vcard_id} AND `is_enabled` = 1 ORDER BY `order`");
            while($row = $result->fetch_object()) {
                $row->settings = json_decode($row->settings);
                $vcard_blocks[$row->vcard_block_id] = $row;
            }

            /* Properly tag the cache */
            $cache_instance->set($vcard_blocks)->expiresAfter(CACHE_DEFAULT_SECONDS);

            foreach($vcard_blocks as $vcard_block) {
                $cache_instance->addTag('vcard_block_id=' . $vcard_block->vcard_block_id);
            }

            if(count($vcard_blocks)) {
                \Altum\Cache::$adapter->save($cache_instance);
            }

        } else {

            /* Get cache */
            $vcard_blocks = $cache_instance->get();

        }

        return $vcard_blocks;

    }

}
