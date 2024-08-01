<?php
use OpenAdmin\Admin\Widgets\InfoBox;
use App\Models\Transaction;
use Carbon\Carbon;

//infobox attributes: name, icon, link, color, info, link_text
$infoBox = new InfoBox(__('Total Monthly Transaction'), 'chart-line', 'info', '/admin/users', '0');
$infoBox->link("/admin/transactions");
$infoBox->link_text(__('More Details'));

$unapprovedCount = Transaction::whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->count();
$infoBox->info($unapprovedCount);
echo $infoBox->render();