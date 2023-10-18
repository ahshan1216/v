<?php defined('ALTUMCODE') || die() ?>

<div class="app-sidebar">
    <div class="app-sidebar-title text-truncate">
        <a href="<?= url() ?>">
            <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                <img src="<?= UPLOADS_FULL_URL . 'main/' . settings()->main->{'logo_' . \Altum\ThemeStyle::get()} ?>" class="img-fluid navbar-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <?= settings()->main->title ?>
            <?php endif ?>
        </a>
    </div>

    <div class="overflow-auto flex-grow-1">
        <ul class="app-sidebar-links">
            <li class="<?= \Altum\Routing\Router::$controller == 'Dashboard' ? 'active' : null ?>">
                <a href="<?= url('dashboard') ?>"><i class="fa fa-fw fa-sm fa-th mr-2"></i> <?= l('dashboard.menu') ?></a>
            </li>

            <li class="<?= \Altum\Routing\Router::$controller == 'Vcards' ? 'active' : null ?>">
                <a href="<?= url('vcards') ?>"><i class="fa fa-fw fa-sm fa-id-card mr-2"></i> <?= l('vcards.menu') ?></a>
            </li>

            <li class="<?= \Altum\Routing\Router::$controller == 'Projects' ? 'active' : null ?>">
                <a href="<?= url('projects') ?>"><i class="fa fa-fw fa-sm fa-project-diagram mr-2"></i> <?= l('projects.menu') ?></a>
            </li>

            <li class="<?= \Altum\Routing\Router::$controller == 'Pixels' ? 'active' : null ?>">
                <a href="<?= url('pixels') ?>"><i class="fa fa-fw fa-sm fa-adjust mr-2"></i> <?= l('pixels.menu') ?></a>
            </li>

            <?php if(settings()->vcards->domains_is_enabled): ?>
                <li class="<?= \Altum\Routing\Router::$controller == 'Domains' ? 'active' : null ?>">
                    <a href="<?= url('domains') ?>"><i class="fa fa-fw fa-sm fa-globe mr-2"></i> <?= l('domains.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(settings()->vcards->directory_is_enabled): ?>
                <li class="<?= \Altum\Routing\Router::$controller == 'Directory' ? 'active' : null ?>">
                    <a href="<?= url('directory') ?>"><i class="fa fa-fw fa-sm fa-sitemap mr-2"></i> <?= l('directory.menu') ?></a>
                </li>
            <?php endif ?>

            <?php foreach($data->pages as $data): ?>
                <li>
                    <a href="<?= $data->url ?>" target="<?= $data->target ?>"><?= $data->title ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>

    <?php if(\Altum\Middlewares\Authentication::check()): ?>

        <div class="app-sidebar-footer dropdown">
            <a href="#" class="dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="d-flex align-items-center app-sidebar-footer-block">
                    <img src="<?= get_gravatar($this->user->email, 80, 'identicon') ?>" class="app-sidebar-avatar mr-3" loading="lazy" />

                    <div class="app-sidebar-footer-text d-flex flex-column text-truncate">
                        <span class="text-truncate"><?= $this->user->name ?></span>
                        <small class="text-truncate"><?= $this->user->email ?></small>
                    </div>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <?php if(!\Altum\Teams::is_delegated()): ?>
                    <?php if(\Altum\Middlewares\Authentication::is_admin()): ?>
                        <a class="dropdown-item" href="<?= url('admin') ?>"><i class="fa fa-fw fa-sm fa-fingerprint mr-2"></i> <?= l('global.menu.admin') ?></a>
                        <div class="dropdown-divider"></div>
                    <?php endif ?>
                    <a class="dropdown-item" href="<?= url('account') ?>"><i class="fa fa-fw fa-sm fa-wrench mr-2"></i> <?= l('account.menu') ?></a>

                    <a class="dropdown-item" href="<?= url('account-plan') ?>"><i class="fa fa-fw fa-sm fa-box-open mr-2"></i> <?= l('account_plan.menu') ?></a>

                    <?php if(settings()->payment->is_enabled): ?>
                        <a class="dropdown-item" href="<?= url('account-payments') ?>"><i class="fa fa-fw fa-sm fa-dollar-sign mr-2"></i> <?= l('account_payments.menu') ?></a>

                        <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                            <a class="dropdown-item" href="<?= url('referrals') ?>"><i class="fa fa-fw fa-sm fa-wallet mr-2"></i> <?= l('referrals.menu') ?></a>
                        <?php endif ?>
                    <?php endif ?>

                    <a class="dropdown-item" href="<?= url('account-api') ?>"><i class="fa fa-fw fa-sm fa-code mr-2"></i> <?= l('account_api.menu') ?></a>

                    <?php if(\Altum\Plugin::is_active('teams')): ?>
                        <a class="dropdown-item" href="<?= url('teams-system') ?>"><i class="fa fa-fw fa-sm fa-user-shield mr-2"></i> <?= l('teams_system.menu') ?></a>
                    <?php endif ?>
                <?php endif ?>

                <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fa fa-fw fa-sm fa-sign-out-alt mr-2"></i> <?= l('global.menu.logout') ?></a>
            </div>
        </div>

    <?php else: ?>

        <ul class="app-sidebar-links">
            <li>
                <a class="nav-link" href="<?= url('login') ?>"><i class="fa fa-fw fa-sm fa-sign-in-alt mr-2"></i> <?= l('login.menu') ?></a>
            </li>

            <?php if(settings()->users->register_is_enabled): ?>
                <li><a class="nav-link" href="<?= url('register') ?>"><i class="fa fa-fw fa-sm fa-user-plus mr-2"></i> <?= l('register.menu') ?></a></li>
            <?php endif ?>
        </ul>

    <?php endif ?>
</div>
