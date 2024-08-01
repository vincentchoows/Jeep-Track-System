<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use OpenAdmin\Admin\Facades\Admin;
use Carbon\Carbon;

$currentMonth = Carbon::now()->startOfMonth();

//for administrator/sysadmin roles
if (Admin::user()->inRoles(['sysadmin'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed'), 'search', 'warning', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('status', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}
//-----------------------------------------------------------------------------------------------------------------

//for phc-checker
if (Admin::user()->inRoles(['phc-checker'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed'), 'search', 'warning', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 0)
        ->where('phc_approve', 0)
        ->where('phc_second_approve', 0)
        ->where('jkr_check', 0)
        ->where('jkr_approve', 0)
        ->where('finance_check', 0)
        ->where('finance_approve', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for phc-approve
if (Admin::user()->inRoles(['phc-approver'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed'), 'search', 'warning', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 2)
        ->where('phc_approve', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for phc-approve
if (Admin::user()->inRoles(['phc-second-approver'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed'), 'search', 'warning', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 2)
        ->where('phc_approve', 2)
        ->where('phc_second_approve', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//-----------------------------------------------------------------------------------------------------------------

//for jkr-checker
if (Admin::user()->inRoles(['jkr-checker'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed'), 'search', 'warning', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 2)
        ->where('phc_approve', 2)
        ->where('phc_second_approve', 2)
        ->where('jkr_check', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for jkr-approver
if (Admin::user()->inRoles(['jkr-approver'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed'), 'search', 'warning', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 2)
        ->where('phc_approve', 2)
        ->where('phc_second_approve', 2)
        ->where('jkr_check', 2)
        ->where('jkr_approve', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//-----------------------------------------------------------------------------------------------------------------

//for fin-checker
if (Admin::user()->inRoles(['fin-checker'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed'), 'search', 'warning', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 2)
        ->where('phc_approve', 2)
        ->where('phc_second_approve', 2)
        ->where('jkr_check', 2)
        ->where('jkr_approve', 2)
        ->where('finance_check', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for fin-approver
if (Admin::user()->inRoles(['fin-approver'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed'), 'search', 'warning', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 2)
        ->where('phc_approve', 2)
        ->where('phc_second_approve', 2)
        ->where('jkr_check', 2)
        ->where('jkr_approve', 2)
        ->where('finance_check', 2)
        ->where('finance_approve', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

