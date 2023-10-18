<?php defined('ALTUMCODE') || die() ?>

<div class="container mt-7 mb-5">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-8 offset-md-1 offset-lg-2">
            <div class="card border-0 san-francisco-card">

                <div class="mb-4 d-flex">
                    <div>
                        <h1 class="h3"><?= l('v_vcard.password.header')  ?></h1>
                        <span class="text-muted"><?= l('v_vcard.password.subheader') ?></span>
                    </div>
                </div>

                <?= \Altum\Alerts::output_alerts() ?>

                <form action="" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                    <div class="form-group">
                        <label for="password"><?= l('v_vcard.password.input') ?></label>
                        <input type="password" id="password" name="password" value="" class="form-control <?= \Altum\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" required="required" />
                        <?= \Altum\Alerts::output_field_error('password') ?>
                    </div>

                    <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
                </form>

            </div>
        </div>
    </div>
</div>

