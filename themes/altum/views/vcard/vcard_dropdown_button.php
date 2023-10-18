<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fa fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="<?= url('vcard-redirect/' . $data->id) ?>" target="_blank" rel="noreferrer"><i class="fa fa-fw fa-sm fa-external-link-alt mr-2"></i> <?= l('vcards.external_url') ?></a>
        <a class="dropdown-item" href="<?= url('vcard-qr/' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-qrcode mr-2"></i> <?= l('vcard_qr.menu') ?></a>
        <a class="dropdown-item" href="<?= url('statistics?vcard_id=' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-chart-pie mr-2"></i> <?= l('vcard_statistics.menu') ?></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="<?= url('vcard-blocks/' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-bars mr-2"></i> <?= l('vcard_blocks.menu') ?></a>
        <a class="dropdown-item" href="<?= url('vcard-update/' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?></a>
        <a href="#" data-toggle="modal" data-target="#vcard_delete_modal" data-vcard-id="<?= $data->id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>
