<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="branding"><?= l('admin_settings.vcards.branding') ?></label>
        <textarea id="branding" name="branding" class="form-control form-control-lg"><?= settings()->vcards->branding ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.vcards.branding_help') ?></small>
    </div>

    <div class="form-group">
        <label for="domains_is_enabled"><?= l('admin_settings.vcards.domains_is_enabled') ?></label>
        <select id="domains_is_enabled" name="domains_is_enabled" class="form-control form-control-lg">
            <option value="1" <?= settings()->vcards->domains_is_enabled ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
            <option value="0" <?= !settings()->vcards->domains_is_enabled ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
        </select>
        <small class="form-text text-muted"><?= l('admin_settings.vcards.domains_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="additional_domains_is_enabled"><?= l('admin_settings.vcards.additional_domains_is_enabled') ?></label>
        <select id="additional_domains_is_enabled" name="additional_domains_is_enabled" class="form-control form-control-lg">
            <option value="1" <?= settings()->vcards->additional_domains_is_enabled ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
            <option value="0" <?= !settings()->vcards->additional_domains_is_enabled ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
        </select>
        <small class="form-text text-muted"><?= l('admin_settings.vcards.additional_domains_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="main_domain_is_enabled"><?= l('admin_settings.vcards.main_domain_is_enabled') ?></label>
        <select id="main_domain_is_enabled" name="main_domain_is_enabled" class="form-control form-control-lg">
            <option value="1" <?= settings()->vcards->main_domain_is_enabled ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
            <option value="0" <?= !settings()->vcards->main_domain_is_enabled ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
        </select>
        <small class="form-text text-muted"><?= l('admin_settings.vcards.main_domain_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="directory_is_enabled"><?= l('admin_settings.vcards.directory_is_enabled') ?></label>
        <select id="directory_is_enabled" name="directory_is_enabled" class="form-control form-control-lg">
            <option value="1" <?= settings()->vcards->directory_is_enabled ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
            <option value="0" <?= !settings()->vcards->directory_is_enabled ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
        </select>
        <small class="form-text text-muted"><?= l('admin_settings.vcards.directory_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="logo_size_limit"><?= l('admin_settings.vcards.logo_size_limit') ?></label>
        <input id="logo_size_limit" type="number" min="0" max="<?= get_max_upload() ?>" step="any" name="logo_size_limit" class="form-control form-control-lg" value="<?= settings()->vcards->logo_size_limit ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.vcards.size_limit_help') ?></small>
    </div>

    <div class="form-group">
        <label for="favicon_size_limit"><?= l('admin_settings.vcards.favicon_size_limit') ?></label>
        <input id="favicon_size_limit" type="number" min="0" max="<?= get_max_upload() ?>" step="any" name="favicon_size_limit" class="form-control form-control-lg" value="<?= settings()->vcards->favicon_size_limit ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.vcards.size_limit_help') ?></small>
    </div>

    <div class="form-group">
        <label for="opengraph_size_limit"><?= l('admin_settings.vcards.opengraph_size_limit') ?></label>
        <input id="opengraph_size_limit" type="number" min="0" max="<?= get_max_upload() ?>" step="any" name="opengraph_size_limit" class="form-control form-control-lg" value="<?= settings()->vcards->opengraph_size_limit ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.vcards.size_limit_help') ?></small>
    </div>

    <div class="form-group">
        <label for="background_size_limit"><?= l('admin_settings.vcards.background_size_limit') ?></label>
        <input id="background_size_limit" type="number" min="0" max="<?= get_max_upload() ?>" step="any" name="background_size_limit" class="form-control form-control-lg" value="<?= settings()->vcards->background_size_limit ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.vcards.size_limit_help') ?></small>
    </div>

    <div class="form-group">
        <label for="blacklisted_domains"><?= l('admin_settings.vcards.blacklisted_domains') ?></label>
        <textarea id="blacklisted_domains" class="form-control form-control-lg" name="blacklisted_domains"><?= settings()->vcards->blacklisted_domains ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.vcards.blacklisted_domains_help') ?></small>
    </div>

    <div class="form-group">
        <label for="blacklisted_keywords"><?= l('admin_settings.vcards.blacklisted_keywords') ?></label>
        <textarea id="blacklisted_keywords" class="form-control form-control-lg" name="blacklisted_keywords"><?= settings()->vcards->blacklisted_keywords ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.vcards.blacklisted_keywords_help') ?></small>
    </div>

    <div class="form-group">
        <label for="google_safe_browsing_is_enabled"><?= l('admin_settings.vcards.google_safe_browsing_is_enabled') ?></label>
        <select id="google_safe_browsing_is_enabled" name="google_safe_browsing_is_enabled" class="form-control form-control-lg">
            <option value="1" <?= settings()->vcards->google_safe_browsing_is_enabled ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
            <option value="0" <?= !settings()->vcards->google_safe_browsing_is_enabled ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
        </select>
        <small class="form-text text-muted"><?= l('admin_settings.vcards.google_safe_browsing_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="google_safe_browsing_api_key"><?= l('admin_settings.vcards.google_safe_browsing_api_key') ?></label>
        <input id="google_safe_browsing_api_key" type="text" name="google_safe_browsing_api_key" class="form-control form-control-lg" value="<?= settings()->vcards->google_safe_browsing_api_key ?>" />
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
