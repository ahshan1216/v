<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$code ?>" dir="<?= l('direction') ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <?php if(\Altum\Meta::$description): ?>
            <meta name="description" content="<?= \Altum\Meta::$description ?>" />
        <?php endif ?>
        <?php if(\Altum\Meta::$keywords): ?>
            <meta name="keywords" content="<?= \Altum\Meta::$keywords ?>" />
        <?php endif ?>

        <?php if(\Altum\Meta::$open_graph['url']): ?>
            <!-- Open Graph / Facebook -->
            <?php foreach(\Altum\Meta::$open_graph as $key => $value): ?>
                <?php if($value): ?>
                    <meta property="og:<?= $key ?>" content="<?= $value ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if(\Altum\Meta::$twitter['url']): ?>
            <!-- Twitter -->
            <?php foreach(\Altum\Meta::$open_graph as $key => $value): ?>
                <?php if($value): ?>
                    <meta property="twitter:<?= $key ?>" content="<?= $value ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if(isset($this->vcard) && $this->vcard_user->plan_settings->search_engine_block_is_enabled && !$this->vcard->is_se_visible): ?>
            <meta name="robots" content="noindex">
        <?php endif ?>

        <?php if(isset($this->vcard) && $this->vcard->settings->favicon): ?>
            <link href="<?= UPLOADS_FULL_URL . 'vcards/favicon/' . $this->vcard->settings->favicon ?>" rel="shortcut icon" />
        <?php else: ?>

            <?php if(!empty(settings()->main->favicon)): ?>
                <link href="<?= UPLOADS_FULL_URL . 'main/' . settings()->main->favicon ?>" rel="shortcut icon" />
            <?php endif ?>

        <?php endif ?>

        <link href="<?= ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php foreach(['vcard-custom.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
        <?php endforeach ?>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js_vcard)): ?>
            <?= settings()->custom->head_js_vcard ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css_vcard)): ?>
            <style><?= settings()->custom->head_css_vcard ?></style>
        <?php endif ?>

        <?php if(!empty($this->vcard->custom_css) && $this->vcard_user->plan_settings->custom_css_is_enabled): ?>
            <style><?= $this->vcard->custom_css ?></style>
        <?php endif ?>

        <?php if($this->vcard->settings->font_family): ?>
            <?php $fonts = require APP_PATH . 'includes/v/fonts.php' ?>
            <?php if($fonts[$this->vcard->settings->font_family]['font_css_url']): ?>
                <link href="<?= $fonts[$this->vcard->settings->font_family]['font_css_url'] ?>" rel="stylesheet">
            <?php endif ?>

            <?php if($fonts[$this->vcard->settings->font_family]['font-family']): ?>
                <style>html, body {font-family: '<?= $fonts[$this->vcard->settings->font_family]['name'] ?>' !important;}</style>
            <?php endif ?>
        <?php endif ?>
        <style>html {font-size: <?= (int) $this->vcard->settings->font_size . 'px' ?> !important;}</style>
    </head>

    <?php
    /* Generate the proper background */
    $body_style = '';
    switch($this->vcard->settings->background_type) {
        case 'preset':
            $body_style = (require APP_PATH . 'includes/v/background_presets.php')[$this->vcard->settings->background_preset];
            break;

        case 'color':
            $body_style = 'background: ' . $this->vcard->settings->background_color;
            break;

        case 'gradient':
            $body_style = 'background: linear-gradient(to right, ' . $this->vcard->settings->background_gradient_one . ', ' . $this->vcard->settings->background_gradient_two . ');';
            break;

        case 'image':
            $body_style = 'background: url(\'' . UPLOADS_FULL_URL . 'vcards/background/' . $this->vcard->settings->background . '\'); background-size: cover; background-position: center;';
            break;
    }
    ?>

    <body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?> <?= $this->vcard->theme ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>" style="<?= $body_style ?>">
        <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>

        <?php require THEME_PATH . 'views/v/partials/ads_header.php' ?>

        <main class="altum-animate altum-animate-fill-none altum-animate-fade-in">
            <?= $this->views['content'] ?>
        </main>

        <?php require THEME_PATH . 'views/v/partials/ads_footer.php' ?>

        <?= $this->views['footer'] ?>

        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php foreach(['libraries/jquery.slim.min.js', 'libraries/bootstrap.min.js', 'functions.js'] as $file): ?>
            <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>

        <?= \Altum\Event::get_content('javascript') ?>

        <?php if(!empty($this->vcard->custom_js) && $this->vcard_user->plan_settings->custom_js_is_enabled): ?>
            <?= $this->vcard->custom_js ?>
        <?php endif ?>
    </body>
</html>
