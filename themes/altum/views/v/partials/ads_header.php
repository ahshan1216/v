<?php
if(
    !empty(settings()->ads->header_vcards)
    && !$this->vcard_user->plan_settings->no_ads
): ?>
    <div class="container my-3"><?= settings()->ads->header_vcards ?></div>
<?php endif ?>
