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
            <li class="active" aria-current="page"><?= l('vcard_blocks.breadcrumb') ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0"><?= sprintf(l('vcard_blocks.header'), $data->vcard->name) ?></h1>

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
            <a href="<?= url('vcard-update/' . $data->vcard->vcard_id) ?>" class="btn btn-block btn-sm btn-outline-secondary"><?= l('vcard.update') ?></a>
        </div>
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <a href="<?= url('vcard-blocks/' . $data->vcard->vcard_id) ?>" class="btn btn-block btn-sm btn-secondary"><?= l('vcard.blocks') ?></a>
        </div>
    </div>

    <div class="my-3">
        <?php if($this->user->plan_settings->vcard_blocks_limit != -1 && count($data->vcard_blocks) >= $this->user->plan_settings->vcard_blocks_limit): ?>
            <button type="button" class="btn btn-block btn-outline-primary disabled" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>">
                <i class="fa fa-fw fa-sm fa-plus"></i> <?= l('vcard_blocks.create') ?>
            </button>
        <?php else: ?>
            <button data-toggle="modal" data-target="#vcard_block_create_modal" type="button" class="btn btn-block btn-primary">
                <i class="fa fa-fw fa-sm fa-plus"></i> <?= l('vcard_blocks.create') ?>
            </button>
        <?php endif ?>
    </div>

    <?php if(count($data->vcard_blocks)): ?>
        <div id="vcard_blocks">
            <?php foreach($data->vcard_blocks as $vcard_block_id => $vcard_block): ?>
                <div class="vcard_block" data-vcard-block-id="<?= $vcard_block->vcard_block_id ?>">
                    <form name="vcard_block_update" action="<?= url('vcard-blocks/update') ?>" method="post" role="form">
                        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
                        <input type="hidden" name="vcard_block_id" value="<?= $vcard_block->vcard_block_id ?>" />

                        <div class="card my-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="h5 m-0">
                                        <span data-toggle="tooltip" title="<?= l('vcards_blocks.' . $vcard_block->type) ?>"><i class="<?= $data->available_vcards_blocks[$vcard_block->type]['icon'] ?> fa-fw fa-sm text-primary-600 mr-1"></i></span>
                                        <a href="<?= '#collapse_' . $vcard_block->vcard_block_id ?>" data-toggle="collapse" class="">
                                            <span><?= $vcard_block->name ?></span>
                                        </a>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="<?= url('statistics?vcard_block_id=' . $vcard_block->vcard_block_id) ?>" class="btn btn-link text-decoration-none mr-2" data-toggle="tooltip" title="<?= l('vcard_blocks.pageviews') ?>">
                                            <i class="fa fa-fw fa-chart-line mr-1"></i> <?= nr($vcard_block->pageviews) ?>
                                        </a>

                                        <button type="button" class="btn btn-link cursor-grab reorder" data-toggle="tooltip" title="<?= l('vcard_blocks.reorder') ?>">
                                            <i class="fa fa-fw fa-sm fa-bars"></i>
                                        </button>

                                        <div data-toggle="tooltip" title="<?= l('global.delete') ?>">
                                            <button data-toggle="modal" data-target="#vcard_block_delete_modal" type="button" class="btn btn-link" data-vcard-block-id="<?= $vcard_block->vcard_block_id ?>">
                                                <i class="fa fa-fw fa-sm fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="collapse" id="<?= 'collapse_' . $vcard_block->vcard_block_id ?>">
                                    <div class="mt-4">
                                        <div class="notification-container"></div>

                                        <div class="form-group">
                                            <label for="<?= 'name_' . $vcard_block->vcard_block_id ?>"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('vcard_blocks.input.name') ?></label>
                                            <input type="text" id="<?= 'name_' . $vcard_block->vcard_block_id ?>" name="name" class="form-control" value="<?= $vcard_block->name ?>" maxlength="128" required="required" />
                                        </div>

                                        <div class="form-group">
                                            <label for="<?= 'value_' . $vcard_block->vcard_block_id ?>"><i class="<?= $data->available_vcards_blocks[$vcard_block->type]['icon'] ?> fa-fw fa-sm text-muted mr-1"></i> <?= l('vcard_blocks.input.' . $vcard_block->type) ?></label>
                                            <div class="input-group">
                                                <?php if($data->available_vcards_blocks[$vcard_block->type]['input_display_format']): ?>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><?= str_replace('%s', '', $data->available_vcards_blocks[$vcard_block->type]['format']) ?></span>
                                                    </div>
                                                <?php endif ?>
                                                <input type="<?= $data->available_vcards_blocks[$vcard_block->type]['value_type'] ?>" id="<?= 'value_' . $vcard_block->vcard_block_id ?>" name="value" class="form-control" value="<?= $vcard_block->value ?>" maxlength="<?= $data->available_vcards_blocks[$vcard_block->type]['value_max_length'] ?>" required="required" />
                                            </div>
                                        </div>

                                        <div class="custom-control custom-switch my-3">
                                            <input id="<?= 'open_in_new_tab_' . $vcard_block->vcard_block_id ?>" name="open_in_new_tab" type="checkbox" class="custom-control-input" <?= $vcard_block->settings->open_in_new_tab ? 'checked="checked"' : null?>>
                                            <label class="custom-control-label" for="<?= 'open_in_new_tab_' . $vcard_block->vcard_block_id ?>"><?= l('vcard_blocks.input.open_in_new_tab') ?></label>
                                        </div>

                                        <div class="custom-control custom-switch my-3">
                                            <input id="<?= 'is_enabled_' . $vcard_block->vcard_block_id ?>" name="is_enabled" type="checkbox" class="custom-control-input" <?= $vcard_block->is_enabled ? 'checked="checked"' : null?>>
                                            <label class="custom-control-label" for="<?= 'is_enabled_' . $vcard_block->vcard_block_id ?>"><?= l('vcard_blocks.input.is_enabled') ?></label>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= l('global.update') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-3">
                    <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-4 mb-3" alt="<?= l('vcard_blocks.no_data') ?>" />
                    <h2 class="h4 text-muted"><?= l('vcard_blocks.no_data') ?></h2>
                    <p class="text-muted"><?= l('vcard_blocks.no_data_help') ?></p>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php if(count($data->vcard_blocks)): ?>
    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/sortable.js' ?>"></script>

    <script>
        'use strict';

        let sortable = Sortable.create(document.getElementById('vcard_blocks'), {
            animation: 150,
            handle: '.reorder',
            draggable: '.vcard_block',
            onUpdate: () => {

                let vcard_blocks = [];
                document.querySelectorAll('#vcard_blocks > .vcard_block').forEach((element, index) => {
                    vcard_blocks.push({
                        vcard_block_id: element.getAttribute('data-vcard-block-id'),
                        order: index
                    });
                });

                /* Send the request */
                fetch(`${url}vcard-blocks/reorder`, {
                    method: 'POST',
                    body: JSON.stringify({vcard_blocks, global_token}),
                    headers: {'Content-Type': 'application/json; charset=UTF-8'}
                })
                .then(response => {
                    return response.ok ? response.json() : Promise.reject(response);
                })
                .then(data => {});

            }
        });

        document.querySelectorAll('form[name="vcard_block_update"]').forEach(element => {
            element.addEventListener('submit', event => {
                let form = event.target;
                let form_data = new FormData(form);
                let vcard_id = <?= json_encode($data->vcard->vcard_id) ?>;

                let notification_container = form.querySelector('.notification-container');
                notification_container.innerHTML = '';

                fetch(form.action, {
                    method: 'POST',
                    body: form_data,
                })
                    .then(response => response.ok ? response.json() : Promise.reject(response))
                    .then(data => {
                        if(data.status == 'error') {
                            display_notifications(data.message, 'error', notification_container);
                        }

                        else if(data.status == 'success') {
                            display_notifications(data.message, 'success', notification_container);

                            setTimeout(() => {
                                redirect(`vcard-blocks/${vcard_id}`);
                            }, 1000);
                        }
                    })
                    .catch(error => {});

                event.preventDefault();
            });
        });
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/vcard/vcard_delete_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/vcard-blocks/vcard_block_delete_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/vcard-blocks/vcard_block_create_modal.php', ['available_vcards_blocks' => $data->available_vcards_blocks]), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/vcard-blocks/vcard_block_generic_create_modal.php', ['vcard' => $data->vcard]), 'modals'); ?>

