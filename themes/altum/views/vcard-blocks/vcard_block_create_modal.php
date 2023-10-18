<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="vcard_block_create_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= l('vcard_blocks.create') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="px-3">
                <form action="" method="get" role="form">
                    <div class="form-group">
                        <input type="search" name="search" class="form-control form-control-lg" value="" placeholder="<?= l('global.filters.search') ?>" aria-label="<?= l('global.filters.search') ?>" />
                    </div>
                </form>
            </div>

            <div class="modal-body">
                <div class="row">
                    <?php foreach(require APP_PATH . 'includes/v/vcards_blocks.php' as $key => $value): ?>
                        <div class="col-12" data-block-id="<?= $key ?>" data-block-name="<?= l('vcards_blocks.' . $key) ?>">
                            <button
                                    type="button"
                                    data-dismiss="modal"
                                    data-toggle="modal"
                                    data-target="#vcard_block_generic_create_modal"
                                    class="btn btn-light btn-block btn-lg mb-3"
                                    data-title="<?= l('vcards_blocks.' . $key) ?>"
                                    data-type="<?= $key ?>"
                                    data-value-type="<?= $data->available_vcards_blocks[$key]['value_type'] ?>"
                                    data-value-max-length="<?= $data->available_vcards_blocks[$key]['value_max_length'] ?>"
                                    data-icon="<?= $data->available_vcards_blocks[$key]['icon'] ?>"
                                    data-input-label="<?= l('vcard_blocks.input.' . $key) ?>"
                                    data-input-display-format="<?= (int) $data->available_vcards_blocks[$key]['input_display_format'] ?>"
                                    data-format="<?= str_replace('%s', '', $data->available_vcards_blocks[$key]['format']) ?>"
                            >
                                <i class="<?= $data->available_vcards_blocks[$key]['icon'] ?> fa-fw fa-sm mr-1"></i>

                                <?= l('vcards_blocks.' . $key) ?>
                            </button>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    let blocks = [];
    document.querySelectorAll('[data-block-id]').forEach(element => blocks.push({
        id: element.getAttribute('data-block-id'),
        name: element.getAttribute('data-block-name').toLowerCase(),
    }));

    document.querySelector('#vcard_block_create_modal input').addEventListener('keyup', event => {
        let string = event.currentTarget.value.toLowerCase();

        for(let block of blocks) {
            if(block.name.includes(string)) {
                document.querySelector(`[data-block-id="${block.id}"]`).classList.remove('d-none');
            } else {
                document.querySelector(`[data-block-id="${block.id}"]`).classList.add('d-none');
            }
        }
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
