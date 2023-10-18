<?php defined('ALTUMCODE') || die() ?>


<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('vcards') ?>"><?= l('vcards.breadcrumb') ?></a><i class="fa fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('vcard_create.breadcrumb') ?></li>
        </ol>
    </nav>

    <h1 class="h4 text-truncate"><?= l('vcard_create.header') ?></h1>
    <p></p>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <?php if(count($data->domains) && (settings()->vcards->domains_is_enabled || settings()->vcards->additional_domains_is_enabled)): ?>
                    <div class="form-group">
                        <label for="domain_id"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('vcard.input.domain_id') ?></label>
                        <select id="domain_id" name="domain_id" class="form-control">
                            <?php if(settings()->vcards->main_domain_is_enabled || \Altum\Middlewares\Authentication::is_admin()): ?>
                                <option value=""><?= SITE_URL ?></option>
                            <?php endif ?>

                            <?php foreach($data->domains as $row): ?>
                                <option value="<?= $row->domain_id ?>" data-type="<?= $row->type ?>" <?= $data->values['domain_id'] && $data->values['domain_id'] == $row->domain_id ? 'selected="selected"' : null ?>><?= $row->url ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('vcard.input.domain_id_help') ?></small>
                    </div>

                    <div id="is_main_vcard_wrapper" class="custom-control custom-switch my-3">
                        <input id="is_main_vcard" name="is_main_vcard" type="checkbox" class="custom-control-input" <?= $data->values['is_main_vcard'] ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="is_main_vcard"><?= l('vcard.input.is_main_vcard') ?></label>
                        <small class="form-text text-muted"><?= l('vcard.input.is_main_vcard_help') ?></small>
                    </div>

                    <div <?= $this->user->plan_settings->custom_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="<?= $this->user->plan_settings->custom_url_is_enabled ? null : 'container-disabled' ?>">
                            <div class="form-group">
                                <label for="url"><i class="fa fa-fw fa-sm fa-anchor text-muted mr-1"></i> <?= l('vcard.input.url') ?></label>
                                <input type="text" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->values['url'] ?>" onchange="update_this_value(this, get_slug)" onkeyup="update_this_value(this, get_slug)" placeholder="<?= l('vcard.input.url_placeholder') ?>" />
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
                                    <input type="text" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->values['url'] ?>" onchange="update_this_value(this, get_slug)" onkeyup="update_this_value(this, get_slug)" placeholder="<?= l('vcard.input.url_placeholder') ?>" />
                                    <?= \Altum\Alerts::output_field_error('url') ?>
                                </div>
                                <small class="form-text text-muted"><?= l('vcard.input.url_help') ?></small>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('vcard.input.name') ?></label>
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?>" maxlength="256" required="required" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                </div>

                <div class="form-group">
                    <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('vcard.input.description') ?></label>
                    <input type="text" id="description" name="description" class="form-control" value="<?= $data->values['description'] ?>" maxlength="256" />
                    <small class="form-text text-muted"><?= l('vcard.input.description_help') ?></small>
                </div>

                <p><small class="form-text text-muted"><i class="fa fa-fw fa-sm fa-info-circle"></i> <?= l('vcard_create.info') ?></small></p>

                <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= l('global.create') ?></button>
            </form>

        </div>
    </div>
</div>


<?php ob_start() ?>
<script>
    'use strict';

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
