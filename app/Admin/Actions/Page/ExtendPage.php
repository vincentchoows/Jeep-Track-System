<?php

namespace App\Admin\Actions\Page;

use OpenAdmin\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Facades\Admin;
use App\Models\PermitExtendLog;
use Carbon\Carbon;

class ExtendPage extends BatchAction
{
    protected $selector = '.report-pages';

    public function handle(Collection $collection, Request $request)
    {
        $inputDays = $request->input('days');
        $inputDesc = $request->input('description');
        $inputAdmin = Admin::user()->id;

        foreach ($collection as $model) {
            $permitId = $model->id;

            $endDate = Carbon::parse($model->end_date);
            $resultDate = $endDate->addDays($inputDays);
            $model->end_date = $resultDate;
            $model->save();

            $newExtendLog = new PermitExtendLog();
            $newExtendLog->description = $inputDesc . '<br><br>' . Carbon::parse($model->end_date) . ' changed to ' . $resultDate;

            $newExtendLog->days = $inputDays;
            $newExtendLog->admin_user = $inputAdmin;
            $newExtendLog->permit_application_id = $permitId;
            $newExtendLog->save();
        }

        return $this->response()->success('Report submitted!')->refresh();
    }

    public function form()
    {
        $this->text('days', __('Total Days'))->rules('numeric');
        $this->textarea('description', __('Description'))->rules('required');
    }
    public function html()
    {
        // '.show-on-rows-selected' toggles '.d-none' when rows are seleted
        return "<a class='report-pages btn btn-sm btn-success show-on-rows-selected d-none me-1'><i class='icon-info-circle'></i>Extend / Deduct</a>";
    }

    public function script()
    {
        return <<<JS
        document.querySelector('{$this->getSelector()}').addEventListener("click",function(){
            let resource_url = '{$this->resource}/' + admin.grid.selected.join();
            admin.resource.batch_edit(resource_url);
        });
JS;
    }
}