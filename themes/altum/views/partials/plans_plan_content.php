<?php defined('ALTUMCODE') || die() ?>

<div class="">
    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.vcards_limit'), '<strong>' . ($data->plan_settings->vcards_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->vcards_limit)) . '</strong>') ?>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->vcards_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.vcard_blocks_limit'), '<strong>' . ($data->plan_settings->vcard_blocks_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->vcard_blocks_limit)) . '</strong>') ?>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->vcard_blocks_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.projects_limit'), '<strong>' . ($data->plan_settings->projects_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->projects_limit)) . '</strong>') ?>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->projects_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.pixels_limit'), '<strong>' . ($data->plan_settings->pixels_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->pixels_limit)) . '</strong>') ?>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->pixels_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </div>

    <?php if(settings()->vcards->domains_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.domains_limit'), '<strong>' . ($data->plan_settings->domains_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->domains_limit)) . '</strong>') ?>
            </div>

            <i class="fa fa-fw fa-sm <?= $data->plan_settings->domains_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(\Altum\Plugin::is_active('teams')): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.teams_limit'), '<strong>' . ($data->plan_settings->teams_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->teams_limit)) . '</strong>') ?>
            </div>

            <i class="fa fa-fw fa-sm <?= $data->plan_settings->teams_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.team_members_limit'), '<strong>' . ($data->plan_settings->team_members_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->team_members_limit)) . '</strong>') ?>
            </div>

            <i class="fa fa-fw fa-sm <?= $data->plan_settings->team_members_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.statistics_retention'), '<strong>' . ($data->plan_settings->statistics_retention == -1 ? l('global.unlimited') : nr($data->plan_settings->statistics_retention)) . '</strong>') ?>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->statistics_retention ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </div>

    <?php if(settings()->vcards->additional_domains_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->additional_domains_is_enabled ? null : 'text-muted' ?>">
            <div>
                <?= l('global.plan_settings.additional_domains_is_enabled') ?>
                <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.additional_domains_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
            </div>

            <i class="fa fa-fw fa-sm <?= $data->plan_settings->additional_domains_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->analytics_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.analytics_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.analytics_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->analytics_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->qr_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.qr_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.qr_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->qr_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->password_protection_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.password_protection_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.password_protection_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->password_protection_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->removable_branding_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.removable_branding_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.removable_branding_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->removable_branding_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->custom_url_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.custom_url_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.custom_url_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->custom_url_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->search_engine_block_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.search_engine_block_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.search_engine_block_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->search_engine_block_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->custom_css_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.custom_css_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.custom_css_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->custom_css_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->custom_js_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.custom_js_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.custom_js_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->custom_js_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->api_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.api_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.api_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->api_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->affiliate_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.affiliate_is_enabled') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.affiliate_is_enabled_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->affiliate_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->no_ads ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.no_ads') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.no_ads_help') ?>"><i class="fa fa-fw fa-xs fa-question text-gray-500"></i></span>
        </div>

        <i class="fa fa-fw fa-sm <?= $data->plan_settings->no_ads ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>
</div>
