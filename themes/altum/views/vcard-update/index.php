<?php defined('ALTUMCODE') || die() ?>


<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('vcards') ?>"><?= l('vcards.breadcrumb') ?></a><i class="fa fa-fw fa-angle-right"></i>
            </li>
            <li>
                <a href="<?= url('vcard-update/' . $data->vcard->vcard_id) ?>"><?= l('vcard.breadcrumb') ?></a><i class="fa fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('vcard_update.breadcrumb') ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0"><?= sprintf(l('vcard_update.header'), $data->vcard->name) ?></h1>

        <div class="d-flex align-items-center col-auto p-0">
            <div>
                <button
                        id="url_copy"
                        type="button"
                        class="btn btn-link text-secondary"
                        data-toggle="tooltip"
                        title="<?= l('global.clipboard_copy') ?>"
                        aria-label="<?= l('global.clipboard_copy') ?>"
                        data-copy="<?= l('global.clipboard_copy') ?>"
                        data-copied="<?= l('global.clipboard_copied') ?>"
                        data-clipboard-text="<?= $data->vcard->full_url ?>"
                >
                    <i class="fa fa-fw fa-sm fa-copy"></i>
                </button>
            </div>

            <?= include_view(THEME_PATH . 'views/vcard/vcard_dropdown_button.php', ['id' => $data->vcard->vcard_id]) ?>
        </div>
    </div>

    <p>
        <a href="<?= $data->vcard->full_url ?>" target="_blank">
            <i class="fa fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i> <?= $data->vcard->full_url ?>
        </a>
    </p>

    <div class="row my-3">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <a href="<?= url('vcard-update/' . $data->vcard->vcard_id) ?>" class="btn btn-block btn-sm btn-secondary"><?= l('vcard.update') ?></a>
        </div>
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <a href="<?= url('vcard-blocks/' . $data->vcard->vcard_id) ?>" class="btn btn-block btn-sm btn-outline-secondary"><?= l('vcard.blocks') ?></a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <?php if(count($data->domains) && (settings()->vcards->domains_is_enabled || settings()->vcards->additional_domains_is_enabled)): ?>
                    <div class="form-group">
                        <label for="domain_id"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('vcard.input.domain_id') ?></label>
                        <select id="domain_id" name="domain_id" class="form-control">
                            <?php if(settings()->vcards->main_domain_is_enabled || \Altum\Middlewares\Authentication::is_admin()): ?>
                                <option value="" <?= $data->vcard->domain_id ? null : 'selected="selected"' ?>><?= SITE_URL ?></option>
                            <?php endif ?>

                            <?php foreach($data->domains as $row): ?>
                                <option value="<?= $row->domain_id ?>" data-type="<?= $row->type ?>" <?= $data->vcard->domain_id && $data->vcard->domain_id == $row->domain_id ? 'selected="selected"' : null ?>><?= $row->url ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('vcard.input.domain_id_help') ?></small>
                    </div>

                    <div id="is_main_vcard_wrapper" class="custom-control custom-switch my-3">
                        <input id="is_main_vcard" name="is_main_vcard" type="checkbox" class="custom-control-input" <?= $data->vcard->domain_id && $data->domains[$data->vcard->domain_id]->vcard_id == $data->vcard->vcard_id ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="is_main_vcard"><?= l('vcard.input.is_main_vcard') ?></label>
                        <small class="form-text text-muted"><?= l('vcard.input.is_main_vcard_help') ?></small>
                    </div>

                    <div <?= $this->user->plan_settings->custom_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="<?= $this->user->plan_settings->custom_url_is_enabled ? null : 'container-disabled' ?>">
                            <div class="form-group">
                                <label for="url"><i class="fa fa-fw fa-sm fa-anchor text-muted mr-1"></i> <?= l('vcard.input.url') ?></label>
                                <input type="text" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->vcard->url ?>" onchange="update_this_value(this, get_slug)" onkeyup="update_this_value(this, get_slug)" placeholder="<?= l('vcard.input.url_placeholder') ?>" />
                                <?= \Altum\Alerts::output_field_error('url') ?>
                                <small class="form-text text-muted"><?= l('vcard.input.url_help') ?></small>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div <?= $this->user->plan_settings->custom_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="<?= $this->user->plan_settings->custom_url_is_enabled ? null : 'container-disabled' ?>">
                            <label for="url"><i class="fa fa-fw fa-sm fa-anchor text-muted mr-1"></i> <?= l('vcard.input.url') ?></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?= SITE_URL ?></span>
                                    </div>
                                    <input type="text" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->vcard->url ?>" onchange="update_this_value(this, get_slug)" onkeyup="update_this_value(this, get_slug)" placeholder="<?= l('vcard.input.url_placeholder') ?>" />
                                    <?= \Altum\Alerts::output_field_error('url') ?>
                                </div>
                                <small class="form-text text-muted"><?= l('vcard.input.url_help') ?></small>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('vcard.input.name') ?></label>
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->vcard->name ?>" maxlength="256" required="required" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                </div>

                <div class="form-group">
                    <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('vcard.input.description') ?></label>
                    <input type="text" id="description" name="description" class="form-control" value="<?= $data->vcard->description ?>" maxlength="256" />
                    <small class="form-text text-muted"><?= l('vcard.input.description_help') ?></small>
                </div>

                <?php $themes = require APP_PATH . 'includes/v/themes.php'; ?>
                <div class="form-group">
                    <label for="theme"><i class="fa fa-fw fa-paint-roller fa-sm mr-1"></i> <?= l('vcard.input.theme') ?></label>
                    <select id="theme" name="theme" class="form-control">
                        <?php foreach($themes as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $data->vcard->theme == $key ? 'selected="selected"' : null?>><?= $value['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="logo"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('vcard.input.logo') ?></label>
                    <?php if(!empty($data->vcard->settings->logo)): ?>
                        <div class="row">
                            <div class="m-1 col-6 col-xl-3">
                                <img src="<?= UPLOADS_FULL_URL . 'vcards/logo/' . $data->vcard->settings->logo ?>" class="img-fluid" loading="lazy" />
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox my-2">
                            <input id="logo_remove" name="logo_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#logo').classList.add('d-none') : document.querySelector('#logo').classList.remove('d-none')">
                            <label class="custom-control-label" for="logo_remove">
                                <span class="text-muted"><?= l('global.delete_file') ?></span>
                            </label>
                        </div>
                    <?php endif ?>
                    <input id="logo" type="file" name="logo" accept="<?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards/logo')) ?>" class="form-control-file <?= \Altum\Alerts::has_field_errors('logo') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('logo') ?>
                    <small class="form-text text-muted"><?= l('vcard.input.logo_help') ?> <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards/logo')) ?></small>
                </div>

                <div class="form-group">
                    <label for="favicon"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('vcard.input.favicon') ?></label>
                    <?php if(!empty($data->vcard->settings->favicon)): ?>
                        <div class="row">
                            <div class="m-1 col-6 col-xl-3">
                                <img src="<?= UPLOADS_FULL_URL . 'vcards/favicon/' . $data->vcard->settings->favicon ?>" class="img-fluid" loading="lazy" />
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox my-2">
                            <input id="favicon_remove" name="favicon_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#favicon').classList.add('d-none') : document.querySelector('#favicon').classList.remove('d-none')">
                            <label class="custom-control-label" for="favicon_remove">
                                <span class="text-muted"><?= l('global.delete_file') ?></span>
                            </label>
                        </div>
                    <?php endif ?>
                    <input id="favicon" type="file" name="favicon" accept="<?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards/favicon')) ?>" class="form-control-file <?= \Altum\Alerts::has_field_errors('favicon') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('favicon') ?>
                    <small class="form-text text-muted"><?= l('vcard.input.favicon_help') ?> <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards/favicon')) ?></small>
                </div>

                <div class="form-group">
                    <label for="background_type"><i class="fa fa-fw fa-sm fa-images text-muted mr-1"></i> <?= l('vcard.input.background_type') ?></label>
                    <select id="background_type" name="background_type" class="form-control">
                        <option value="preset" <?= $data->vcard->settings->background_type == 'preset' ? 'selected="selected"' : null ?>><?= l('vcard.input.background_type_preset') ?></option>
                        <option value="color" <?= $data->vcard->settings->background_type == 'color' ? 'selected="selected"' : null ?>><?= l('vcard.input.background_type_color') ?></option>
                        <option value="gradient" <?= $data->vcard->settings->background_type == 'gradient' ? 'selected="selected"' : null ?>><?= l('vcard.input.background_type_gradient') ?></option>
                        <option value="image" <?= $data->vcard->settings->background_type == 'image' ? 'selected="selected"' : null ?>><?= l('vcard.input.background_type_image') ?></option>
                    </select>
                </div>

                <div data-background-type="preset">
                    <div class="row">
                        <?php foreach(require APP_PATH . 'includes/v/background_presets.php' as $key => $value): ?>
                            <label for="<?= 'background_preset_' . $key ?>" class="m-0 col-4 col-xl-3 mb-3">
                                <input type="radio" name="background_preset" value="<?= $key ?>" id="<?= 'background_preset_' . $key ?>" class="d-none" <?= $data->vcard->settings->background_preset == $key ? 'checked="checked"' : null ?>/>

                                <div class="vcard-background-preset" style="<?= $value ?>"></div>
                            </label>
                        <?php endforeach ?>
                    </div>
                </div>

                <div class="form-group" data-background-type="color">
                    <label for="background_color"><i class="fa fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('vcard.input.background_type_color') ?></label>
                    <input type="color" id="background_color" name="background_color" class="form-control" value="<?= $data->vcard->settings->background_color ?>" />
                </div>

                <div class="form-group" data-background-type="gradient">
                    <label for="background_gradient_one"><i class="fa fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('vcard.input.background_gradient_one') ?></label>
                    <input type="color" id="background_gradient_one" name="background_gradient_one" class="form-control" value="<?= $data->vcard->settings->background_gradient_one ?>" />
                </div>

                <div class="form-group" data-background-type="gradient">
                    <label for="background_gradient_two"><i class="fa fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('vcard.input.background_gradient_two') ?></label>
                    <input type="color" id="background_gradient_two" name="background_gradient_two" class="form-control" value="<?= $data->vcard->settings->background_gradient_two ?>" />
                </div>

                <div class="form-group" data-background-type="image">
                    <label for="background"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('vcard.input.background_type_image') ?></label>
                    <?php if(!empty($data->vcard->settings->background)): ?>
                        <div class="row">
                            <div class="m-1 col-6 col-xl-3">
                                <img src="<?= UPLOADS_FULL_URL . 'vcards/background/' . $data->vcard->settings->background ?>" class="img-fluid" loading="lazy" />
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox my-2">
                            <input id="background_remove" name="background_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#background').classList.add('d-none') : document.querySelector('#background').classList.remove('d-none')">
                            <label class="custom-control-label" for="background_remove">
                                <span class="text-muted"><?= l('global.delete_file') ?></span>
                            </label>
                        </div>
                    <?php endif ?>
                    <input id="background" type="file" name="background" accept="<?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards/background')) ?>" class="form-control-file <?= \Altum\Alerts::has_field_errors('background') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('background') ?>
                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards/background')) ?></small>
                </div>

                <div class="custom-control custom-switch my-3">
                    <input id="is_share_button_visible" name="is_share_button_visible" type="checkbox" class="custom-control-input" <?= $data->vcard->settings->is_share_button_visible ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="is_share_button_visible"><?= l('vcard.input.is_share_button_visible') ?></label>
                </div>

                <div class="custom-control custom-switch my-3">
                    <input id="is_download_button_visible" name="is_download_button_visible" type="checkbox" class="custom-control-input" <?= $data->vcard->settings->is_download_button_visible ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="is_download_button_visible"><?= l('vcard.input.is_download_button_visible') ?></label>
                </div>

                <div class="custom-control custom-switch my-3">
                    <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->vcard->is_enabled ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="is_enabled"><?= l('vcard.input.is_enabled') ?></label>
                </div>

                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#vcard_container" aria-expanded="false" aria-controls="vcard_container">
                    <?= l('vcard.input.vcard') ?>
                </button>

                <div class="collapse" id="vcard_container">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first_name"><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= l('vcard.input.first_name') ?></label>
                                <input type="text" id="first_name" name="first_name" class="form-control <?= \Altum\Alerts::has_field_errors('first_name') ? 'is-invalid' : null ?>" value="<?= $data->vcard->settings->first_name ?? null ?>" maxlength="64" />
                                <?= \Altum\Alerts::output_field_error('first_name') ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="last_name"><i class="fa fa-fw fa-signature fa-sm mr-1"></i> <?= l('vcard.input.last_name') ?></label>
                                <input type="text" id="last_name" name="last_name" class="form-control <?= \Altum\Alerts::has_field_errors('last_name') ? 'is-invalid' : null ?>" value="<?= $data->vcard->settings->last_name ?? null ?>" maxlength="64" />
                                <?= \Altum\Alerts::output_field_error('last_name') ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="company"><i class="fa fa-fw fa-building fa-sm mr-1"></i> <?= l('vcard.input.company') ?></label>
                        <input type="text" id="company" name="company" class="form-control <?= \Altum\Alerts::has_field_errors('company') ? 'is-invalid' : null ?>" value="<?= $data->vcard->settings->company ?? null ?>" maxlength="64" />
                        <?= \Altum\Alerts::output_field_error('company') ?>
                    </div>

                    <div class="form-group">
                        <label for="job_title"><i class="fa fa-fw fa-user-tie fa-sm mr-1"></i> <?= l('vcard.input.job_title') ?></label>
                        <input type="text" id="job_title" name="job_title" class="form-control <?= \Altum\Alerts::has_field_errors('job_title') ? 'is-invalid' : null ?>" value="<?= $data->vcard->settings->job_title ?? null ?>" maxlength="64" />
                        <?= \Altum\Alerts::output_field_error('job_title') ?>
                    </div>

                    <div class="form-group">
                        <label for="birthday"><i class="fa fa-fw fa-birthday-cake fa-sm mr-1"></i> <?= l('vcard.input.birthday') ?></label>
                        <input type="date" id="birthday" name="birthday" class="form-control <?= \Altum\Alerts::has_field_errors('birthday') ? 'is-invalid' : null ?>" value="<?= $data->vcard->settings->birthday ?? null ?>" />
                        <?= \Altum\Alerts::output_field_error('birthday') ?>
                    </div>
                </div>

                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#fonts_container" aria-expanded="false" aria-controls="fonts_container">
                    <?= l('vcard.input.fonts') ?>
                </button>

                <div class="collapse" id="fonts_container">
                    <?php $fonts = require APP_PATH . 'includes/v/fonts.php'; ?>

                    <div class="form-group">
                        <label for="font_family"><i class="fa fa-fw fa-pen-nib fa-sm mr-1"></i> <?= l('vcard.input.font_family') ?></label>
                        <select id="font_family" name="font_family" class="form-control">
                            <?php foreach($fonts as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->vcard->settings->font_family == $key ? 'selected="selected"' : null?>><?= $value['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="font_size"><i class="fa fa-fw fa-font fa-sm mr-1"></i> <?= l('vcard.input.font_size') ?></label>
                        <div class="input-group">
                            <input id="font_size" type="number" min="14" max="22" name="font_size" class="form-control" value="<?= $data->vcard->settings->font_size ?>" />
                            <div class="input-group-append">
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#pixels_container" aria-expanded="false" aria-controls="pixels_container">
                    <?= l('vcard.input.pixels') ?>
                </button>

                <div class="collapse" id="pixels_container">
                    <div class="form-group">
                        <div class="d-flex flex-column flex-xl-row justify-content-between">
                            <label><i class="fa fa-fw fa-sm fa-adjust text-muted mr-1"></i> <?= l('vcard.input.pixels_ids') ?></label>
                            <a href="<?= url('pixel-create') ?>" target="_blank" class="small mb-2"><i class="fa fa-fw fa-sm fa-plus mr-1"></i> <?= l('pixels.create') ?></a>
                        </div>

                        <div class="row">
                            <?php $available_pixels = require APP_PATH . 'includes/v/pixels.php'; ?>
                            <?php foreach($data->pixels as $pixel): ?>
                                <div class="col-12 col-lg-6">
                                    <div class="custom-control custom-checkbox my-2">
                                        <input id="pixel_id_<?= $pixel->pixel_id ?>" name="pixels_ids[]" value="<?= $pixel->pixel_id ?>" type="checkbox" class="custom-control-input" <?= in_array($pixel->pixel_id, $data->vcard->pixels_ids) ? 'checked="checked"' : null ?>>
                                        <label class="custom-control-label d-flex align-items-center" for="pixel_id_<?= $pixel->pixel_id ?>">
                                            <span class="mr-1"><?= $pixel->name ?></span>
                                            <small class="badge badge-light badge-pill"><?= $available_pixels[$pixel->type]['name'] ?></small>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#seo_container" aria-expanded="false" aria-controls="seo_container">
                    <?= l('vcard.input.seo') ?>
                </button>

                <div class="collapse" id="seo_container">
                    <div <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'container-disabled' ?>">
                            <input id="is_se_visible" name="is_se_visible" type="checkbox" class="custom-control-input" <?= $data->vcard->is_se_visible ? 'checked="checked"' : null?> <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'disabled="disabled"' ?>>
                            <label class="custom-control-label" for="is_se_visible"><?= l('vcard.input.is_se_visible') ?></label>
                            <small class="form-text text-muted"><?= l('vcard.input.is_se_visible_help') ?></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="opengraph"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('vcard.input.opengraph') ?></label>
                        <?php if(!empty($data->vcard->settings->opengraph)): ?>
                            <div class="row">
                                <div class="m-1 col-6 col-xl-3">
                                    <img src="<?= UPLOADS_FULL_URL . 'vcards/opengraph/' . $data->vcard->settings->opengraph ?>" class="img-fluid" loading="lazy" />
                                </div>
                            </div>
                            <div class="custom-control custom-checkbox my-2">
                                <input id="opengraph_remove" name="opengraph_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#opengraph').classList.add('d-none') : document.querySelector('#opengraph').classList.remove('d-none')">
                                <label class="custom-control-label" for="opengraph_remove">
                                    <span class="text-muted"><?= l('global.delete_file') ?></span>
                                </label>
                            </div>
                        <?php endif ?>
                        <input id="opengraph" type="file" name="opengraph" accept="<?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards/opengraph')) ?>" class="form-control-file <?= \Altum\Alerts::has_field_errors('opengraph') ? 'is-invalid' : null ?>" />
                        <?= \Altum\Alerts::output_field_error('opengraph') ?>
                        <small class="form-text text-muted"><?= l('vcard.input.opengraph_help') ?> <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards/opengraph')) ?></small>
                    </div>
                </div>

                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#advanced_container" aria-expanded="false" aria-controls="advanced_container">
                    <?= l('vcard.input.advanced') ?>
                </button>

                <div class="collapse" id="advanced_container">
                    <div class="form-group">
                        <div class="d-flex flex-column flex-xl-row justify-content-between">
                            <label for="project_id"><i class="fa fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                            <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fa fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                        </div>
                        <select id="project_id" name="project_id" class="form-control">
                            <option value=""><?= l('projects.project_id_null') ?></option>
                            <?php foreach($data->projects as $project_id => $project): ?>
                                <option value="<?= $project_id ?>" <?= $data->vcard->project_id == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('projects.project_id_help') ?></small>
                    </div>

                    <div <?= $this->user->plan_settings->password_protection_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="form-group <?= $this->user->plan_settings->password_protection_is_enabled ? null : 'container-disabled' ?>">
                            <label for="password"><i class="fa fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= l('vcard.input.password') ?></label>
                            <input type="password" id="password" name="password" class="form-control" value="<?= $data->vcard->password ?>" autocomplete="new-password" />
                        </div>
                    </div>

                    <div <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'container-disabled' ?>">
                            <input id="is_removed_branding" name="is_removed_branding" type="checkbox" class="custom-control-input" <?= $data->vcard->is_removed_branding ? 'checked="checked"' : null?> <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'disabled="disabled"' ?>>
                            <label class="custom-control-label" for="is_removed_branding"><?= l('vcard.input.is_removed_branding') ?></label>
                            <small class="form-text text-muted"><?= l('vcard.input.is_removed_branding_help') ?></small>
                        </div>
                    </div>

                    <div <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="form-group <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'container-disabled' ?>">
                            <label for="custom_css"><i class="fa fa-fw fa-sm fa-code text-muted mr-1"></i> <?= l('vcard.input.custom_css') ?></label>
                            <textarea id="custom_css" class="form-control" name="custom_css" maxlength="8192"><?= $data->vcard->custom_css ?></textarea>
                        </div>
                    </div>

                    <div <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="form-group <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'container-disabled' ?>">
                            <label for="custom_js"><i class="fa fa-fw fa-sm fa-code text-muted mr-1"></i> <?= l('vcard.input.custom_js') ?></label>
                            <textarea id="custom_js" class="form-control" name="custom_js" maxlength="8192"><?= $data->vcard->custom_js ?></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= l('global.update') ?></button>
            </form>

        </div>
    </div>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Background type handler */
    let background_type_handler = () => {
        let background_type = document.querySelector('select[name="background_type"]').value;

        document.querySelectorAll(`[data-background-type]:not([data-background-type="${background_type}"])`).forEach(element => {
            element.classList.add('d-none');
        });

        document.querySelectorAll(`[data-background-type="${background_type}"]`).forEach(element => {
            element.classList.remove('d-none');
        });
    }

    background_type_handler();
    document.querySelector('select[name="background_type"]') && document.querySelector('select[name="background_type"]').addEventListener('change', background_type_handler);


    /* Is main vcard handler */
    let is_main_vcard_handler = () => {
        if(document.querySelector('#is_main_vcard').checked) {
            document.querySelector('#url').setAttribute('disabled', 'disabled');
        } else {
            document.querySelector('#url').removeAttribute('disabled');
        }
    }

    document.querySelector('#is_main_vcard') && document.querySelector('#is_main_vcard').addEventListener('change', is_main_vcard_handler);

    /* Domain Id Handler */
    let domain_id_handler = () => {
        let domain_id = document.querySelector('select[name="domain_id"]').value;

        if(document.querySelector(`select[name="domain_id"] option[value="${domain_id}"]`).getAttribute('data-type') == '0') {
            document.querySelector('#is_main_vcard_wrapper').classList.remove('d-none');
        } else {
            document.querySelector('#is_main_vcard_wrapper').classList.add('d-none');
            document.querySelector('#is_main_vcard').checked = false;
        }

        is_main_vcard_handler();
    }

    domain_id_handler();

    document.querySelector('select[name="domain_id"]') && document.querySelector('select[name="domain_id"]').addEventListener('change', domain_id_handler);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/vcard/vcard_delete_modal.php'), 'modals'); ?>
