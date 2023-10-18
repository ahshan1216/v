<?php defined('ALTUMCODE') || die() ?>

<div class="index-background pt-6 pt-lg-8 pb-6">
    <div class="container">
        <?= \Altum\Alerts::output_alerts() ?>

        <div class="row">
            <div class="col-12 col-lg-7">
                <h1 class="index-header mb-4"><?= l('index.header') ?></h1>
                <p class="index-subheader mb-4"><?= l('index.subheader') ?></p>

                <div class="d-flex flex-column flex-lg-row">
                    <a href="<?= url('register') ?>" class="btn btn-primary index-button mb-3 mb-lg-0 mr-lg-3"><?= l('index.get_started') ?></a>
                    <a href="<?= url('example') ?>" target="_blank" class="btn btn-gray-100 index-button mb-3 mb-lg-0"><?= l('index.example') ?> <i class="fa fa-fw fa-xs fa-external-link-alt"></i></a>
                </div>
            </div>

            <div class="col-12 col-lg-5 mt-5 mt-lg-0">
                <img src="<?= ASSETS_FULL_URL . 'images/index/hero.png' ?>" class="img-fluid shadow index-hero" />
            </div>
        </div>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="index-background-one py-7">
    <div class="container text-center">
        <span class="text-white h2"><?= sprintf(l('index.stats'), '<span class="text-primary">' . nr($data->total_vcards) . '</span>') ?></span>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="row">
        <div class="col-6 col-lg-4 mb-5">
            <div class="card d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/themes.png' ?>" class="img-fluid rounded mb-2 " loading="lazy" />

                <div class="card-body">
                    <div class="mb-2">
                        <span class="h5"><?= l('index.themes.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.themes.subheader') ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="card d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/customizability.png' ?>" class="img-fluid rounded mb-2" loading="lazy" />

                <div class="card-body">
                    <div class="mb-2">
                        <span class="h5"><?= l('index.customizability.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.customizability.subheader') ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="card d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/theme-style-dark.png' ?>" class="img-fluid rounded mb-2" loading="lazy" />

                <div class="card-body">
                    <div class="mb-2">
                        <span class="h5"><?= l('index.theme_style_dark.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.theme_style_dark.subheader') ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="card d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/pixels.png' ?>" class="img-fluid rounded mb-2" loading="lazy" />

                <div class="card-body">
                    <div class="mb-2">
                        <span class="h5"><?= l('index.pixels.header') ?></span>
                    </div>
                    <span class="text-muted"><?= sprintf(l('index.pixels.subheader'), implode(', ',  array_map(function($item) {return $item['name'];}, require APP_PATH . 'includes/v/pixels.php'))) ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="card d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/projects.png' ?>" class="img-fluid rounded mb-2" loading="lazy" />

                <div class="card-body">
                    <div class="mb-2">
                        <span class="h5"><?= l('index.projects.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.projects.subheader') ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 mb-5">
            <div class="card d-flex flex-column justify-content-between h-100">
                <img src="<?= ASSETS_FULL_URL . 'images/index/domains.png' ?>" class="img-fluid rounded mb-2" loading="lazy" />

                <div class="card-body">
                    <div class="mb-2">
                        <span class="h5"><?= l('index.domains.header') ?></span>
                    </div>
                    <span class="text-muted"><?= l('index.domains.subheader') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="py-7 bg-gray-100">
    <div class="container">
        <div class="text-center">
            <h2><?= l('index.testimonials.header') ?></h2>
            <p class="text-muted mt-3"><?= l('index.testimonials.subheader') ?></p>
        </div>

        <div class="row mt-8">
            <?php foreach(['one', 'two', 'three'] as $key): ?>
                <div class="col-12 col-md-4 m mb-md-0">

                    <div class="card border-0">
                        <div class="card-body">
                            <img src="<?= ASSETS_FULL_URL . 'images/index/testimonial-' . $key . '.jpeg' ?>" class="img-fluid index-testimonial-avatar" />

                            <p class="mt-5">
                                <span class="text-gray-800 font-weight-bold h4">“</span>
                                <span class="font-italic text-muted"><?= l('index.testimonials.' . $key . '.text') ?></span>
                                <span class="text-gray-800 font-weight-bold h4">”</span>
                            </p>
                            <div class="blockquote-footer mt-4">
                                <span class="font-weight-bold"><?= l('index.testimonials.' . $key . '.name') ?></span>, <span class="text-muted"><?= l('index.testimonials.' . $key . '.attribute') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

<div class="my-5">&nbsp;</div>

<div class="container">
    <div class="text-center mb-5">
        <h2 class="mt-2"><?= l('index.pricing.header') ?></h2>
        <span class="text-muted"><?= l('index.pricing.subheader') ?></span>
    </div>

    <?= $this->views['plans'] ?>
</div>

<div class="my-5">&nbsp;</div>

<?php if(settings()->users->register_is_enabled): ?>
    <div class="bg-gray-100 py-6">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-around align-items-lg-center">
                <div>
                    <h2 class="text-gray-900"><?= l('index.cta.header') ?></h2>
                    <p class="text-gray-800"><?= l('index.cta.subheader') ?></p>
                </div>

                <div>
                    <a href="<?= url('register') ?>" class="btn btn-primary index-button"><?= l('index.cta.register') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>


