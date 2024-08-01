<?php

namespace App\Admin\Actions\Page;

use OpenAdmin\Admin\Actions\BatchAction;
// use OpenAdmin\Admin\Grid\Tools\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Facades\Admin;
use App\Models\PermitExtendLog;
use Carbon\Carbon;

class DeductPage extends BatchAction
{
    protected $selector = '.deduct-pages';
    public function handle(Collection $collection, Request $request)
    {
        $inputDays = $request->input('days');
        $inputDesc = $request->input('description');
        $inputAdmin = Admin::user()->id;

        foreach ($collection as $model) {
            $permitId = $model->id;
            $endDate = Carbon::parse($model->end_date);
            $resultDate = $endDate->subDays($inputDays);
            $model->end_date = $resultDate;
            $model->save();

            $newExtendLog = new PermitExtendLog();
            $newExtendLog->description = $inputDesc;
            $newExtendLog->days = $inputDays;
            $newExtendLog->admin_user = $inputAdmin;
            $newExtendLog->permit_application_id = $permitId;
            $newExtendLog->save();
        }

        return $this->response()->success('Report submitted!')->refresh();
    }

    public function form()
    {
        $this->text('days', __('Total Days'))->rules('');
        $this->textarea('description', __('Description'))->rules('required');
    }

    public function html()
    {
        // '.show-on-rows-selected' toggles '.d-none' when rows are seleted
        return "<a class='deduct-pages btn btn-sm btn-danger show-on-rows-selected d-none me-1 ml-1'><i class='icon-info-circle'></i>Deduct</a>";
    }
}