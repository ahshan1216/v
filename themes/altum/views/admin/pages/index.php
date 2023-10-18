<?php defined('ALTUMCODE') || die() ?>

<?= \Altum\Alerts::output_alerts() ?>

<?php if($data->pages_categories_result->num_rows): ?>
    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3 m-0"><i class="fa fa-fw fa-xs fa-book text-primary-900 mr-2"></i> <?= l('admin_pages_categories.header') ?></h1>

        <div class="col-auto p-0">
            <a href="<?= url('admin/pages-category-create') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-plus-circle"></i> <?= l('admin_pages_categories.create') ?></a>
        </div>
    </div>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= l('admin_pages_categories.table.pages_category') ?></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = $data->pages_categories_result->fetch_object()): ?>

                <tr>
                    <td class="text-nowrap">
                        <div class="d-flex align-items-center">
                            <?php if(!empty($row->icon)): ?>
                                <span class="round-circle-md bg-primary-100 text-primary p-3 mr-3"><i class="<?= $row->icon ?>"></i></span>
                            <?php endif ?>

                            <div class="d-flex flex-column">
                                <div>
                                    <a href="<?= url('admin/pages-category-update/' . $row->pages_category_id) ?>"><?= $row->title ?></a>
                                    <a href="<?= SITE_URL . ($row->language ? \Altum\Language::$active_languages[$row->language] . '/' : null) . 'pages/' . $row->url ?>" target="_blank" rel="noreferrer"><i class="fa fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
                                </div>
                                <span class="text-muted"><?= $row->url ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap text-muted">
                        <i class="fa fa-fw fa-sm fa-file-alt"></i> <?= sprintf(l('admin_pages_categories.table.total_pages'), $row->total_pages) ?>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <?= include_view(THEME_PATH . 'views/admin/pages/admin_pages_category_dropdown_button.php', ['id' => $row->pages_category_id]) ?>
                        </div>
                    </td>
                </tr>

            <?php endwhile ?>
            </tbody>
        </table>
    </div>

<?php else: ?>

    <div class="d-flex flex-column flex-md-row align-items-md-center">
        <div class="mb-3 mb-md-0 mr-md-5">
            <i class="fa fa-fw fa-7x fa-book text-primary-200"></i>
        </div>

        <div class="d-flex flex-column">
            <h1 class="h3 m-0"><?= l('admin_pages_categories.header_no_data') ?></h1>
            <p class="text-muted"><?= l('admin_pages_categories.subheader_no_data') ?></p>

            <div>
                <a href="<?= url('admin/pages-category-create') ?>" class="btn btn-primary"><i class="fa fa-fw fa-sm fa-plus-circle"></i> <?= l('admin_pages_categories.create') ?></a>
            </div>
        </div>
    </div>

<?php endif ?>


<hr class="my-5 border-gray-100" />


<?php if($data->pages_result->num_rows): ?>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3 m-0"><i class="fa fa-fw fa-xs fa-file-alt text-primary-900 mr-2"></i> <?= l('admin_pages.header') ?></h1>

        <div class="col-auto p-0">
            <a href="<?= url('admin/page-create') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-plus-circle"></i> <?= l('admin_pages.create') ?></a>
        </div>
    </div>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= l('admin_pages.table.page') ?></th>
                <th><?= l('admin_pages.input.position') ?></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = $data->pages_result->fetch_object()): ?>

                <tr>
                    <td class="text-nowrap">
                        <div class="d-flex align-items-center">
                            <?php if(!empty($row->pages_category_icon)): ?>
                                <span class="round-circle-md bg-primary-100 text-primary p-3 mr-3" data-toggle="tooltip" title="<?= $row->pages_category_title ?>"><i class="<?= $row->pages_category_icon ?>"></i></span>
                            <?php endif ?>

                            <div class="d-flex flex-column">
                                <div>
                                    <a href="<?= url('admin/page-update/' . $row->page_id) ?>"><?= $row->title ?></a>
                                    <a href="<?= $row->type == 'internal' ? SITE_URL . ($row->language ? \Altum\Language::$active_languages[$row->language] . '/' : null) . 'page/' . $row->url : $row->url ?>" target="_blank" rel="noreferrer"><i class="fa fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
                                </div>
                                <span class="text-muted"><?= $row->url ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <div class="d-flex flex-column">
                            <?= l('admin_pages.input.position_' . $row->position) ?>
                            <small class="text-muted"><?= l('admin_pages.input.type_' . mb_strtolower($row->type)) ?></small>
                        </div>
                    </td>
                    <td class="text-nowrap text-muted"><?= sprintf(l('admin_pages.table.total_views'), nr($row->total_views)) ?></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <?= include_view(THEME_PATH . 'views/admin/pages/admin_page_dropdown_button.php', ['id' => $row->page_id]) ?>
                        </div>
                    </td>
                </tr>

            <?php endwhile ?>
            </tbody>
        </table>
    </div>

<?php else: ?>

    <div class="d-flex flex-column flex-md-row align-items-md-center">
        <div class="mb-3 mb-md-0 mr-md-5">
            <i class="fa fa-fw fa-7x fa-file-alt text-primary-200"></i>
        </div>

        <div class="d-flex flex-column">
            <h1 class="h3 m-0"><?= l('admin_pages.header_no_data') ?></h1>
            <p class="text-muted"><?= l('admin_pages.subheader_no_data') ?></p>

            <div>
                <a href="<?= url('admin/page-create') ?>" class="btn btn-primary"><i class="fa fa-fw fa-sm fa-plus-circle"></i> <?= l('admin_pages.create') ?></a>
            </div>
        </div>
    </div>

<?php endif ?>


<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/pages/page_delete_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/pages/pages_category_delete_modal.php'), 'modals'); ?>
