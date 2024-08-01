<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use OpenAdmin\Admin\Facades\Admin;
//red,orange,blue, purple

//for phc
if (Admin::user()->inRoles(['phc'])) {
    $infoBox = new InfoBox(__('Applications To Be Approved By Head of Traffic Section (PHC2)'), 'user-tie', 'blue', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 2)
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

//for jkr
if (Admin::user()->inRoles(['jkr'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed By JKR Director (JKR2)'), 'user-tie', 'blue', '', '0');
    $infoBox->link("");
    $infoBox->link_text(__('More Details'));
    $unapprovedCount = PermitApplication::where('phc_check', 2)
        ->where('phc_approve', 2)
        ->where('phc_second_approve', 2)
        ->where('jkr_check', 2)
        ->where('jkr_approve', 0)
        ->where('finance_check', 0)
        ->where('finance_approve', 0)
        ->count();
    $infoBox->info($unapprovedCount);
    echo $infoBox->render();
}

//for fin
if (Admin::user()->inRoles(['fin'])) {
    $infoBox = new InfoBox(__('Applications To Be Reviewed By Senior Accounting (FIN2)'), 'user-tie', 'blue', '', '0');
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

//-----------------------------------------------------------------------------------------------------------------
