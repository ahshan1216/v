<?php defined('ALTUMCODE') || die() ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8 offset-md-1 offset-lg-2">
            <div class="card border-0 rounded-0 chicago-card">
                <div class="d-flex align-items-center">
                    <?php if($data->vcard->settings->logo): ?>
                        <img src="<?= UPLOADS_FULL_URL . 'vcards/logo/' . $data->vcard->settings->logo ?>" class="chicago-logo" loading="lazy" />
                    <?php endif ?>

                    <div class="<?= $data->vcard->settings->logo ? 'ml-3' : null ?>">
                        <h1 class="h3"><?= $data->vcard->name ?></h1>
                        <p class="text-muted m-0"><?= $data->vcard->description ?></p>
                    </div>
                </div>

                <div class="mt-4">
                    <?php foreach($data->vcard_blocks as $vcard_block): ?>
                        <?php
                        $href = $vcard_block->value;
                        if($vcard_block->type == 'address') {
                            $href = urlencode($vcard_block->value);
                        }
                        ?>
                        <div class="my-3">
                            <a href="<?= sprintf($data->available_vcards_blocks[$vcard_block->type]['format'], $href) ?>" data-vcard-block-id="<?= $vcard_block->vcard_block_id ?>" target="<?= $vcard_block->settings->open_in_new_tab ? '_blank' : '_self' ?>" class="chicago-button text-decoration-none d-flex align-items-center" rel="nofollow">
                                <div class="icon text-gray-600 rounded d-flex justify-content-center align-items-center">
                                    <div class="svg-md d-flex"><?= include_view(ASSETS_PATH . '/images/v/' . $data->available_vcards_blocks[$vcard_block->type]['svg_icon']) ?></div>
                                </div>

                                <div class="d-flex flex-column ml-3">
                                    <small class="text-gray-500"><?= $vcard_block->name ?></small>
                                    <span class="text-gray-700"><?= $data->available_vcards_blocks[$vcard_block->type]['input_display_format'] ? sprintf($data->available_vcards_blocks[$vcard_block->type]['format'], $vcard_block->value) : $vcard_block->value ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach ?>
                </div>

                <?php if($this->vcard->settings->is_share_button_visible || $this->vcard->settings->is_download_button_visible): ?>
                    <div class="mt-4">
                        <div class="row">
                            <?php if($this->vcard->settings->is_share_button_visible): ?>
                                <div class="col">
                                    <button data-toggle="modal" data-target="#share_modal" type="button" class="btn btn-block btn-gray-100 rounded-0 d-flex align-items-center justify-content-center">
                                        <div class="svg-sm d-flex mr-1"><?= include_view(ASSETS_PATH . '/images/v/share.svg') ?></div> <?= l('v_vcard.share') ?>
                                    </button>
                                    <?= include_view(THEME_PATH . 'views/v/partials/share.php', ['full_url' => $this->vcard->full_url]) ?>
                                </div>
                            <?php endif ?>

                            <?php if($this->vcard->settings->is_download_button_visible): ?>
                                <div class="col">
                                    <a href="<?= $data->vcard->full_url . '?export=vcard&referrer=vcard' ?>" class="btn btn-block btn-gray-100 rounded-0 d-flex align-items-center justify-content-center" target="_blank">
                                        <div class="svg-sm d-flex mr-1"><?= include_view(ASSETS_PATH . '/images/v/vcard.svg') ?></div> <?= l('v_vcard.vcard') ?>
                                    </a>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    document.querySelectorAll('[data-vcard-block-id]').forEach(element => {
        element.addEventListener('click', event => {
            let vcard_block_id = event.currentTarget.getAttribute('data-vcard-block-id');
            try {
                navigator.sendBeacon(`${window.location.href}?vcard_block_id=${vcard_block_id}`);
            } catch (error) {
                console.log(error)
            }
        });
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
