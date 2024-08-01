<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use OpenAdmin\Admin\Facades\Admin;

//for administrator roles (not in use)
if (Admin::user()->inRoles(['sysadmin'])) {
    $infoBox = new InfoBox(__('Applications Pending Second Approval'), 'chart-line', 'purple', '/admin/users', '22');
    $infoBox->link("/admin/permit-applications?phc_second_approve=0");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_second_approve', 0)
        ->orWhere('phc_approve', 0)
        ->orWhere('phc_check', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for phc roles 
if (Admin::user()->inRoles(['phc'])) {
    $infoBox = new InfoBox(__('Applications Pending Second Approval'), 'chart-line', 'purple', '/admin/users', '22');
    $infoBox->link("/admin/permit-applications?phc_second_approve=0");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_second_approve', 0)
        ->orWhere('phc_approve', 0)
        ->orWhere('phc_check', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}


