<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */


return [
    'link' => [
        'icon' => 'fa fa-link',
        'svg_icon' => 'website.svg',
        'format' => '%s',
        'value_max_length' => 1024,
        'value_type' => 'url',
        'input_display_format' => false,
    ],
    'email' => [
        'icon' => 'fa fa-envelope',
        'svg_icon' => 'email.svg',
        'format' => 'mailto:%s',
        'value_max_length' => 320,
        'value_type' => 'email',
        'input_display_format' => false,
    ],
    'phone' => [
        'icon' => 'fa fa-phone',
        'svg_icon' => 'phone.svg',
        'format' => 'tel:%s',
        'value_max_length' => 32,
        'value_type' => 'text',
        'input_display_format' => false,
    ],
    'address' => [
        'icon' => 'fa fa-map-marker-alt',
        'svg_icon' => 'location-marker.svg',
        'format' => 'https://www.google.com/maps/search/%s',
        'value_max_length' => 256,
        'value_type' => 'text',
        'input_display_format' => false,
    ],

    /* Socials */
    'facebook' => [
        'icon' => 'fab fa-facebook',
        'svg_icon' => 'facebook.svg',
        'format' => 'https://facebook.com/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'twitter' => [
        'icon' => 'fab fa-twitter',
        'svg_icon' => 'twitter.svg',
        'format' => 'https://twitter.com/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'instagram' => [
        'icon' => 'fab fa-instagram',
        'svg_icon' => 'instagram.svg',
        'format' => 'https://instagram.com/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'youtube' => [
        'icon' => 'fab fa-youtube',
        'svg_icon' => 'youtube.svg',
        'format' => 'https://youtube.com/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'whatsapp' => [
        'icon' => 'fab fa-whatsapp',
        'svg_icon' => 'whatsapp.svg',
        'format' => 'https://api.whatsapp.com/send?phone=%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => false,
    ],

    'tiktok' => [
        'icon' => 'fab fa-tiktok',
        'svg_icon' => 'tiktok.svg',
        'format' => 'https://tiktok.com/@%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'telegram' => [
        'icon' => 'fab fa-telegram',
        'svg_icon' => 'telegram.svg',
        'format' => 'https://t.me/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'spotify' => [
        'icon' => 'fab fa-spotify',
        'svg_icon' => 'spotify.svg',
        'format' => 'https://open.spotify.com/artist/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'pinterest' => [
        'icon' => 'fab fa-pinterest',
        'svg_icon' => 'pinterest.svg',
        'format' => 'https://pinterest.com/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'linkedin' => [
        'icon' => 'fab fa-linkedin',
        'svg_icon' => 'linkedin.svg',
        'format' => 'https://linkedin.com/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'snapchat' => [
        'icon' => 'fab fa-snapchat',
        'svg_icon' => 'snapchat.svg',
        'format' => 'https://snapchat.com/add/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'twitch' => [
        'icon' => 'fab fa-twitch',
        'svg_icon' => 'twitch.svg',
        'format' => 'https://twitch.tv/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'discord' => [
        'icon' => 'fab fa-discord',
        'svg_icon' => 'discord.svg',
        'format' => 'https://discord.gg/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'github' => [
        'icon' => 'fab fa-github',
        'svg_icon' => 'github.svg',
        'format' => 'https://github.com/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'facebook_messenger' => [
        'icon' => 'fab fa-facebook-messenger',
        'svg_icon' => 'facebook-messenger.svg',
        'format' => 'https://m.me/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],

    'reddit' => [
        'icon' => 'fab fa-reddit',
        'svg_icon' => 'reddit.svg',
        'format' => 'https://www.reddit.com/user/%s',
        'value_max_length' => 128,
        'value_type' => 'text',
        'input_display_format' => true,
    ],
];
