<?php

namespace App\Admin\Actions\Page;

use OpenAdmin\Admin\Actions\BatchAction;
// use OpenAdmin\Admin\Grid\Tools\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ReportPage extends BatchAction
{
    protected $selector = '.report-pages';

    public function handle(Collection $collection, Request $request)
    {
        foreach ($collection as $model) {
            $model->reason = $request->input("reason");
            $model->save();
        }

        return $this->response()->success('Report submitted!')->refresh();
    }

    public function form()
    {
        $this->textarea('reason', 'reason')->rules('required');
    }

    public function html()
    {
        // '.show-on-rows-selected' toggles '.d-none' when rows are seleted
        return "<a class='report-pages btn btn-sm btn-danger show-on-rows-selected d-none me-1 ml-1'><i class='icon-info-circle'></i>Report</a>";
    }
}