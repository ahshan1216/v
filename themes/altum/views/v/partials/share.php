<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="modal fade" id="share_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= l('v_vcard.share') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="qr-code" data-qr></div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <a href="mailto:?body=<?= $data->full_url ?>" target="_blank" title="Email" class="btn btn-gray-200 mb-2 mb-md-0 mr-md-3">
                        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . '/images/v/email.svg') ?></div>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $data->full_url ?>" target="_blank" title="Facebook" class="btn btn-gray-200 mb-2 mb-md-0 mr-md-3">
                        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . '/images/v/facebook.svg') ?></div>
                    </a>
                    <a href="https://twitter.com/share?url=<?= $data->full_url ?>" target="_blank" title="Twitter" class="btn btn-gray-200 mb-2 mb-md-0 mr-md-3">
                        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . '/images/v/twitter.svg') ?></div>
                    </a>
                    <a href="https://pinterest.com/pin/create/link/?url=<?= $data->full_url ?>" target="_blank" title="Pinterest" class="btn btn-gray-200 mb-2 mb-md-0 mr-md-3">
                        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . '/images/v/pinterest.svg') ?></div>
                    </a>
                    <a href="https://linkedin.com/shareArticle?url=<?= $data->full_url ?>" target="_blank" title="LinkedIn" class="btn btn-gray-200 mb-2 mb-md-0 mr-md-3">
                        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . '/images/v/linkedin.svg') ?></div>
                    </a>
                    <a href="https://www.reddit.com/submit?url=<?= $data->full_url ?>" target="_blank" title="Reddit" class="btn btn-gray-200 mb-2 mb-md-0 mr-md-3">
                        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . '/images/v/reddit.svg') ?></div>
                    </a>
                    <a href="https://wa.me/?text=<?= $data->full_url ?>" target="_blank" title="Whatsapp" class="btn btn-gray-200 mb-2 mb-md-0 mr-md-3">
                        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . '/images/v/whatsapp.svg') ?></div>
                    </a>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" value="<?= $data->full_url ?>" onclick="this.select();" readonly="readonly" />
                </div>
            </div>

        </div>
    </div>
</div>
<?php \Altum\Event::add_content(ob_get_clean(), 'modals') ?>


<?php if(!\Altum\Event::exists_content_type_key('javascript', 'share')): ?>
<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/jquery-qrcode.min.js' ?>"></script>

<script>
    'use strict';

    let qr = document.querySelector('#share_modal [data-qr]');

    let generate_qr = () => {
        let qr_url = <?= json_encode($data->full_url) ?>;

        let mode = 0;
        let mode_size = 0.1;

        let default_options = {
            render: 'image',
            minVersion: 1,
            maxVersion: 40,
            ecLevel: 'H',
            left: 0,
            top: 0,
            size: 1000,
            text: qr_url,
            quiet: 0,
            mode: mode,
            mSize: mode_size,
            mPosX: 0.5,
            mPosY: 0.5,
        };

        /* Delete already existing image generated */
        qr.querySelector('img') && qr.querySelector('img').remove();
        $(qr).qrcode(default_options);
    }

    generate_qr();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'share') ?>
<?php endif ?>
