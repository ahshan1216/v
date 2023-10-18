<?php defined('ALTUMCODE') || die() ?>


<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('pixels') ?>"><?= l('pixels.breadcrumb') ?></a><i class="fa fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('pixel_update.breadcrumb') ?></li>
        </ol>
    </nav>

    <?php $url = parse_url(SITE_URL); $host = $url['host'] . (mb_strlen($url['path']) > 1 ? $url['path'] : null); ?>

    <div class="d-flex align-items-center mb-4">
        <h1 class="h4 text-truncate mb-0 mr-2"><?= l('pixel_update.header') ?></h1>

        <?= include_view(THEME_PATH . 'views/pixels/pixel_dropdown_button.php', ['id' => $data->pixel->pixel_id]) ?>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('pixels.input.name') ?></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $data->pixel->name ?>" required="required" />
                </div>

                <div class="form-group">
                    <label for="type"><i class="fa fa-fw fa-adjust fa-sm mr-1"></i> <?= l('pixels.input.type') ?></label>
                    <select id="type" name="type" class="form-control" required="required">
                        <?php foreach(require APP_PATH . 'includes/v/pixels.php' as $pixel_key => $pixel): ?>
                            <option value="<?= $pixel_key ?>" <?= $data->pixel->type == $pixel_key ? 'selected="selected"' : null ?>><?= $pixel['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pixel"><i class="fa fa-fw fa-code fa-sm mr-1"></i> <?= l('pixels.input.pixel') ?></label>
                    <input type="text" id="pixel" name="pixel" class="form-control" value="<?= $data->pixel->pixel ?>" required="required" />
                    <small class="text-muted form-text"><?= l('pixels.input.pixel_help') ?></small>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
            </form>

        </div>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/pixels/pixel_delete_modal.php'), 'modals'); ?>
