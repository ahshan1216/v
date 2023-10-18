<?php defined('ALTUMCODE') || die() ?>

<nav class="navbar app-navbar d-lg-none navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <a href="<?= url() ?>" class="navbar-brand">
            <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                <img src="<?= UPLOADS_FULL_URL . 'main/' . settings()->main->{'logo_' . \Altum\ThemeStyle::get()} ?>" class="img-fluid navbar-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <?= settings()->main->title ?>
            <?php endif ?>
        </a>

        <button class="btn navbar-custom-toggler d-lg-none" type="button" id="app_menu_toggler" aria-controls="main_navbar" aria-expanded="false" aria-label="<?= l('global.accessibility.toggle_navigation') ?>">
            <i class="fa fa-fw fa-bars"></i>
        </button>
    </div>
</nav>
