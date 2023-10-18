<?php defined('ALTUMCODE') || die() ?>


<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <h1 class="h4 mb-0 text-truncate"><?= l('dashboard.header') ?></h1>
        </div>
    </div>

    <div class="my-4">
        <div class="row">
            <div class="col-12 col-sm-6 col-xl mb-4 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('vcards') ?>" class="stretched-link">
                            <i class="fa fa-fw fa-id-card text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_vcards'), '<span class="h6">' . nr($data->total_vcards) . '</span>') ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl mb-4 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('projects') ?>" class="stretched-link">
                            <i class="fa fa-fw fa-project-diagram text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_projects'), '<span class="h6">' . nr($data->total_projects) . '</span>') ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl mb-4 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('pixels') ?>" class="stretched-link">
                            <i class="fa fa-fw fa-adjust text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_pixels'), '<span class="h6">' . nr($data->total_pixels) . '</span>') ?>
                    </div>
                </div>
            </div>

            <?php if(settings()->vcards->domains_is_enabled): ?>
            <div class="col-12 col-sm-6 col-xl mb-4 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-100 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('domains') ?>" class="stretched-link">
                            <i class="fa fa-fw fa-globe text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_domains'), '<span class="h6">' . nr($data->total_domains) . '</span>') ?>
                    </div>
                </div>
            </div>
            <?php endif ?>
        </div>
    </div>

    <div class="my-4">
        <div class="d-flex align-items-center mb-3">
            <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fa fa-fw fa-sm fa-id-card mr-1"></i> <?= l('dashboard.vcards_header') ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('vcard-create') ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= l('vcards.create') ?></a>
            </div>
        </div>

        <?php if(count($data->vcards)): ?>
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th><?= l('vcards.table.vcard') ?></th>
                        <th></th>
                        <th><?= l('vcards.table.stats') ?></th>
                        <th><?= l('vcards.table.datetime') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->vcards as $row): ?>

                        <tr>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    <?php if($row->settings->logo): ?>
                                    <img src="<?= UPLOADS_FULL_URL . 'vcards/logo/' . $row->settings->logo ?>" class="vcard-table-logo rounded-circle mr-3" loading="lazy" />
                                    <?php endif ?>

                                    <div class="d-flex flex-column">
                                        <div><a href="<?= url('vcard-update/' . $row->vcard_id) ?>"><?= $row->name ?></a></div>
                                        <div class="small">
                                            <i class="fa fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i>
                                            <a href="<?= $row->full_url ?>" class="text-muted" target="_blank" rel="noreferrer"><?= $row->full_url ?></a>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <?php if($row->project_id): ?>
                                    <a href="<?= url('vcards?project_id=' . $row->project_id) ?>" class="small text-decoration-none">
                                    <span class="py-1 px-2 border rounded text-muted small" style="border-color: <?= $data->projects[$row->project_id]->color ?> !important;">
                                        <?= $data->projects[$row->project_id]->name ?>
                                    </span>
                                    </a>
                                <?php endif ?>
                            </td>

                            <td class="text-nowrap">
                                <a href="<?= url('statistics?vcard_id=' . $row->vcard_id) ?>" class="btn btn-link text-decoration-none" data-toggle="tooltip" title="<?= l('vcards.table.pageviews') ?>">
                                    <i class="fa fa-fw fa-chart-line mr-1"></i> <?= nr($row->pageviews) ?>
                                </a>
                            </td>

                            <td class="text-nowrap text-muted">
                                <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>"><?= \Altum\Date::get($row->datetime, 2) ?></span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-end">
                                    <?= include_view(THEME_PATH . 'views/vcard/vcard_dropdown_button.php', ['id' => $row->vcard_id]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    <tr>
                        <td colspan="5">
                            <a href="<?= url('vcards') ?>" class="text-muted">
                                <i class="fa fa-angle-right fa-sm fa-fw mr-1"></i> <?= l('dashboard.view_all') ?>
                            </a>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        <?php else: ?>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center justify-content-center py-3">
                        <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-4 mb-3" alt="<?= l('vcards.no_data') ?>" />
                        <h2 class="h4 text-muted"><?= l('vcards.no_data') ?></h2>
                        <p class="text-muted"><?= l('vcards.no_data_help') ?></p>
                    </div>
                </div>
            </div>

        <?php endif ?>

    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/vcard/vcard_delete_modal.php'), 'modals'); ?>
