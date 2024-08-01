<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use OpenAdmin\Admin\Facades\Admin;

//for administrator roles
if (Admin::user()->inRoles(['sysadmin'])) {
    $infoBox = new InfoBox(__('Unapproved Applications'), 'clock', 'danger', '', '0');
    $infoBox->link("/admin/permit-applications");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::whereNotIn('status', [4, 5, 6])->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}




