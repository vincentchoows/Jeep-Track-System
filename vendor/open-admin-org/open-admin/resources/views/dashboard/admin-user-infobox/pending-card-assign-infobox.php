<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use OpenAdmin\Admin\Facades\Admin;
//red,orange,blue, purple

//for fin roles
if (Admin::user()->inRoles(['fin'])) {
    $infoBox = new InfoBox(__('Permit Cards to Assign ()'), 'shield-alt', 'purple', '', '0');
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

//-----------------------------------------------------------------------------------------------------------------
