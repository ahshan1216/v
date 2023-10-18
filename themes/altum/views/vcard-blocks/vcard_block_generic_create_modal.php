<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="vcard_block_generic_create_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#vcard_block_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fa fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="vcard_block_generic_create_modal" method="post" action="<?= url('vcard-blocks/create') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="vcard_id" value="<?= $data->vcard->vcard_id ?>" />
                    <input type="hidden" name="type" value="generic" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="generic_name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('vcard_blocks.input.name') ?></label>
                        <input type="text" id="generic_name" name="name" class="form-control" value="" maxlength="128" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="generic_value"></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"></span>
                            </div>
                            <input type="<?= $data->available_vcards_blocks['generic']['value_type'] ?>" id="generic_value" name="value" class="form-control" value="" maxlength="<?= $data->available_vcards_blocks['generic']['value_max_length'] ?>" required="required" />
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.create') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    $('#vcard_block_generic_create_modal').on('show.bs.modal', event => {
        let title = event.relatedTarget.getAttribute('data-title');
        let input_label = event.relatedTarget.getAttribute('data-input-label');
        let type = event.relatedTarget.getAttribute('data-type');
        let value_type = event.relatedTarget.getAttribute('data-value-type');
        let value_max_length = event.relatedTarget.getAttribute('data-value-max-length');
        let icon = event.relatedTarget.getAttribute('data-icon');
        let input_display_format = parseInt(event.relatedTarget.getAttribute('data-input-display-format'));
        let format = event.relatedTarget.getAttribute('data-format');

        event.currentTarget.querySelector('.modal-title').innerText = title;
        event.currentTarget.querySelector('label[for="generic_value"]').innerHTML = `<i class="${icon} fa-fw fa-sm text-muted mr-1"></i> ${input_label}`;
        event.currentTarget.querySelector('input[name="type"]').value = type;
        event.currentTarget.querySelector('input[name="value"]').type = value_type;
        event.currentTarget.querySelector('input[name="value"]').maxLength = value_max_length;

        if(input_display_format) {
            event.currentTarget.querySelector('.input-group-prepend').classList.remove('d-none');
            event.currentTarget.querySelector('.input-group-text').innerText = format;
        } else {
            event.currentTarget.querySelector('.input-group-prepend').classList.add('d-none');
        }
    });

    document.querySelector('form[name="vcard_block_generic_create_modal"]').addEventListener('submit', event => {
        let form = event.target;
        let form_data = new FormData(form);
        let vcard_id = form.querySelector('input[name="vcard_id"]').value;

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
                    /* Hide modal */
                    $('#vcard_block_generic_create_modal').modal('hide');

                    redirect(`vcard-blocks/${vcard_id}`);
                }, 1000);
            }
        })
        .catch(error => {});

        event.preventDefault();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
