<?php defined('ALTUMCODE') || die() ?>

<footer class="container mb-6">
    <div class="d-flex align-items-center justify-content-center">
        <div class="d-flex flex-column">
            <?php if(!$this->vcard->is_removed_branding || ($this->vcard->is_removed_branding && !$this->vcard_user->plan_settings->removable_branding_is_enabled)) :?>
                <div class="text-center text-lg-left mb-2">
                    <a href="<?= url() ?>" class="text-muted small" target="_blank"><?= settings()->vcards->branding ?></a>
                </div>
            <?php endif ?>

            <div class="text-center mb-2">
            </div>

            <?php if(count(\Altum\ThemeStyle::$themes) > 1): ?>
                <div class="mb-0 mb-lg-0 text-center">
                    <a href="#" data-choose-theme-style="dark" class="text-muted text-decoration-none <?= \Altum\ThemeStyle::get() == 'dark' ? 'd-none' : null ?>" title="<?= sprintf(l('global.theme_style'), l('global.theme_style_dark')) ?>">
                        🌙
                    </a>
                    <a href="#" data-choose-theme-style="light" class="text-muted text-decoration-none <?= \Altum\ThemeStyle::get() == 'light' ? 'd-none' : null ?>" title="<?= sprintf(l('global.theme_style'), l('global.theme_style_light')) ?>">
                        ☀️
                    </a>
                </div>

            <?php ob_start() ?>
                <script>
                    'use strict';

                    document.querySelectorAll('[data-choose-theme-style]').forEach(theme => {
                        theme.addEventListener('click', event => {
                            let chosen_theme_style = event.currentTarget.getAttribute('data-choose-theme-style');

                            /* Set a cookie with the new theme style */
                            set_cookie('theme_style', chosen_theme_style, 30);

                            /* Change the css and button on the page */
                            let css = document.querySelector(`#css_theme_style`);

                            document.querySelector(`[data-theme-style]`).setAttribute('data-theme-style', chosen_theme_style);

                            switch(chosen_theme_style) {
                                case 'dark':
                                    css.setAttribute('href', <?= json_encode(ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::$themes['dark'][l('direction')] . '?v=' . PRODUCT_CODE) ?>);
                                    document.querySelector(`[data-choose-theme-style="dark"]`).classList.add('d-none');
                                    document.querySelector(`[data-choose-theme-style="light"]`).classList.remove('d-none');
                                    break;

                                case 'light':
                                    css.setAttribute('href', <?= json_encode(ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::$themes['light'][l('direction')] . '?v=' . PRODUCT_CODE) ?>);
                                    document.querySelector(`[data-choose-theme-style="dark"]`).classList.remove('d-none');
                                    document.querySelector(`[data-choose-theme-style="light"]`).classList.add('d-none');
                                    break;
                            }

                            event.preventDefault();
                        });
                    })
                </script>
                <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
            <?php endif ?>
        </div>
    </div>
</footer>

<?php ob_start() ?>
<?= $this->views['pixels'] ?? null ?>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
