<?php defined('ALTUMCODE') || die() ?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= SITE_URL ?></loc>
    </url>
    <url>
        <loc><?= SITE_URL . 'login' ?></loc>
    </url>
    <?php if(settings()->users->register_is_enabled): ?>
        <url>
            <loc><?= SITE_URL . 'register' ?></loc>
        </url>
    <?php endif ?>
    <url>
        <loc><?= SITE_URL . 'lost-password' ?></loc>
    </url>
    <url>
        <loc><?= SITE_URL . 'resend-activation' ?></loc>
    </url>
    <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
        <url>
            <loc><?= SITE_URL . 'affiliate' ?></loc>
        </url>
    <?php endif ?>
    <url>
        <loc><?= SITE_URL . 'api-documentation' ?></loc>
    </url>
    <?php if(settings()->email_notifications->contact && !empty(settings()->email_notifications->emails)): ?>
        <url>
            <loc><?= SITE_URL . 'contact' ?></loc>
        </url>
    <?php endif ?>
    <url>
        <loc><?= SITE_URL . 'pages' ?></loc>
    </url>
    <?php foreach($data->pages as $page): ?>
        <url>
            <loc><?= SITE_URL . ($page->language ? \Altum\Language::$active_languages[$page->language] . '/' : null) . 'page/' . $page->url ?></loc>
        </url>
    <?php endforeach ?>
    <?php foreach($data->pages_categories as $pages_category): ?>
        <url>
            <loc><?= SITE_URL . ($pages_category->language ? \Altum\Language::$active_languages[$pages_category->language] . '/' : null) . 'pages/' . $pages_category->url ?></loc>
        </url>
    <?php endforeach ?>
</urlset>
