<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use OpenAdmin\Admin\Facades\Admin;

//for administrator roles (NOT IN USE)
if (Admin::user()->inRoles(['sysadmin'])) {
    $infoBox = new InfoBox(__('Pending Application Approval'), 'exclamation-triangle', 'blue', '/admin/users', '22');
    $infoBox->link("/admin/permit-applications?phc_check=0&phc_approve=0&phc_second_approve=0&jkr_check=0&jkr_approve=0&finance_check=0&finance_approve=0");
    $infoBox->link_text(__('More Details'));
    //query from db
    $unapprovedCount = PermitApplication::where('phc_approve', 0)
        ->orWhere('phc_check', 0)
        ->orWhere('phc_second_approve', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for phc role
if (Admin::user()->inRoles(['phc'])) {
    $infoBox = new InfoBox(__('Pending Application Approval'), 'exclamation-triangle', 'blue', '/admin/users', '22');
    $infoBox->link("/admin/permit-applications?phc_approve=0");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_approve', 0)
        ->orWhere('phc_check', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for jkr role
if (Admin::user()->inRoles(['jkr'])) {
    $infoBox = new InfoBox(__('Pending Application Approval'), 'exclamation-triangle', 'blue', '/admin/users', '22');
    $infoBox->link("/admin/permit-applications?jkr_approve=0");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('jkr_approve', 0)
        ->orWhere('jkr_check', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for fin role
if (Admin::user()->inRoles(['fin'])) {
    $infoBox = new InfoBox(__('Pending Application Approval'), 'exclamation-triangle', 'blue','/admin/users', '22');
    $infoBox->link("/admin/permit-applications?finance_approve=0");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('finance_approve', 0)
        ->orWhere('finance_check', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}
