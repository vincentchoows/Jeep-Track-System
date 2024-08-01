<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use Carbon\Carbon;

$infoBox = new InfoBox(__('Monthly Permit Application'), 'chart-bar', 'info', '/admin/users', '0');
$infoBox->link("/admin/permit-applications?");
$infoBox->link_text(__('More Details'));
$unapprovedCount = PermitApplication::whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->count();
$infoBox->info($unapprovedCount);
echo $infoBox->render();
