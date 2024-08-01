<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use OpenAdmin\Admin\Facades\Admin;


//for administrator roles (not in use)
if (Admin::user()->inRoles(['sysadmin'])) {
    $infoBox = new InfoBox(__('Applications To Be Checked'), 'chart-bar', 'orange', '', '0');
    $infoBox->link("/admin/permit-applications?phc_check=0");
    $infoBox->link_text(__('More Details'));

    $unapprovedCount = PermitApplication::where('phc_check', 0)->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for phc roles
if (Admin::user()->inRoles(['phc'])) {
    $infoBox = new InfoBox(__('Applications To Be Checked'), 'chart-bar', 'orange', '', '0');
    $infoBox->link("/admin/permit-applications?phc_check=0");
    $infoBox->link_text(__('More Details'));

    $unapprovedCount = PermitApplication::where('phc_check', 0)->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for jkr roles
if (Admin::user()->inRoles(['jkr'])) {
    $infoBox = new InfoBox(__('Applications To Be Checked'), 'chart-bar', 'orange', '', '0');
    $infoBox->link("/admin/permit-applications?jkr_check=0");
    $infoBox->link_text(__('More Details'));

    $unapprovedCount = PermitApplication::where('jkr_check', 0)->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for fin roles
if (Admin::user()->inRoles(['fin'])) {
    $infoBox = new InfoBox(__('Applications To Be Checked'), 'chart-bar', 'orange', '', '0');
    $infoBox->link("/admin/permit-applications?finance_check=0");
    $infoBox->link_text(__('More Details'));

    $unapprovedCount = PermitApplication::where('finance_check', 0)->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}



