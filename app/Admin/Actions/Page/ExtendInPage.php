<?php

namespace App\Admin\Actions\Page;

use OpenAdmin\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Facades\Admin;
use App\Models\PermitExtendLog;
use Carbon\Carbon;

class ExtendInPage extends BatchAction
{
    protected $selector = '.extendin-pages';
    protected $id = '';

    protected public function __construct(string $var = null) {
        $this->id = $var;
    }

    // public function handle(Collection $collection, Request $request)
    // {
    //     $inputDays = $request->input('days');
    //     $inputDesc = $request->input('description');
    //     $inputAdmin = Admin::user()->id;

    //     foreach ($collection as $model) {
    //         $permitId = $model->id;

    //         //changes in permit application
    //             //change end date

    //         $endDate = Carbon::parse($model->end_date);
    //         $resultDate = $endDate->addDays($inputDays);
    //         $model->end_date = $resultDate;
    //         $model->save();

    //         //changes in permit extend log
    //             //add new row 
    //                 //admin user
    //                 //desc
    //                 //days 

    //         $newExtendLog = new PermitExtendLog();
    //         $newExtendLog->description = $inputDesc;
    //         $newExtendLog->days = $inputDays;
    //         $newExtendLog->admin_user = $inputAdmin;
    //         $newExtendLog->permit_application_id = $permitId;
    //         $newExtendLog->save();
    //     }

    //     return $this->response()->success('Report submitted!')->refresh();
    // }

    // public function form()
    // {
    //     $this->textarea('days', __('Total Days'))->rules('');
    //     $this->textarea('description', __('Description'))->rules('required');
    // }

    public function form()
    {
        $this->text('days', __('Total Days'))->rules('');
        $this->textarea('description', __('Description'))->rules('required');
    }



    public function html()
    {
        // '.show-on-rows-selected' toggles '.d-none' when rows are seleted
        // return "<a class='extendin-pages btn btn-sm btn-success show-on-rows-selected d-none me-1'><i class='icon-info-circle'></i>Extend second</a>";

        return "<a class='extendin-pages btn btn-sm btn-success show-on-rows-selected' style=''><i class='fa icon-info'></i>Extend</a>";
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