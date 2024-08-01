<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\PermitApplication;
use Carbon\Carbon;

$infoBox = new InfoBox(__('Pending Renewal (incomplete)'), 'exclamation-triangle', 'warning', '/admin/users', '22');
$infoBox->link("/admin/renewal");
$infoBox->link_text(__('More Details'));

//query from db
// $unapprovedCount = PermitApplication::whereMonth('created_at', Carbon::now()->month)
//     ->whereYear('created_at', Carbon::now()->year)
//     ->count();

$infoBox->info(0);
echo $infoBox->render();
