<?php

namespace App\Admin\Controllers;

use App\Models\PermitExtendLog;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PermitApplication;
use App\Admin\Extensions\Tools\UserGender;
use App\Admin\Extensions\Tools\ReleasePost;
use App\Admin\Actions\Page\ReportPage;
use App\Admin\Actions\Page\ExtendPage;
use App\Admin\Actions\Page\ExtendInPage;
use App\Admin\Actions\Page\DeductPage;
use OpenAdmin\Admin\Actions\BatchAction;
use App\Admin\Actions\ExtendPermit;
use App\Admin\Actions\ExtendPermitDays;
use Illuminate\Http\Request;
use \App\Models\User;
use \App\Models\PermitHolder;
use Carbon\Carbon;
use DateTime;

class PermitExtendController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Permit Extend';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PermitApplication());
        //grid setting
        $grid->disableCreateButton();

        //disable batch edit 
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableEdit();
                $batch->disableDelete();
            });
        });

        //custome batch tool
        $grid->tools(function ($tools) {
            $tools->append(new ExtendPage());
        });

        // Header messages
        $message1 = __('Please note that the Deduct and Extend buttons will only appear when one or more items are selected.');
        $message2 = __('Only rows with expiry dates within the next 30 days or sooner will be displayed.');

        // Concatenate the messages with HTML list tags
        $messagesHtml = '<ul>';
        $messagesHtml .= '<li>' . $message1 . '</li>';
        $messagesHtml .= '<li>' . $message2 . '</li>';
        $messagesHtml .= '</ul>';

        // Define the header function
        $grid->header(function ($query) use ($messagesHtml) {
            return '<div class="with-border clearfix" style="display: inline-block; margin: 10px;">
                        <p>' . $messagesHtml . '</p>
                    </div>';
        });

        //----------------------- tools 
        $thirtyDaysFromNow = Carbon::now()->addDays(30);
        $grid->model()
            
            ->whereRaw('DATEDIFF(end_date, NOW()) <= 30');

        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account Name'))->sortable();
        $grid->column('holder.name', __('Permit Holder Name'))->sortable();
        $grid->column('start_date', __('Start date'))->sortable();
        $grid->column('end_date', __('End date'))->sortable();
        $grid->column('created_at', __('Created at'))->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(PermitApplication::findOrFail($id));

        $show->field('id', __('Application ID'));
        $show->field('user.name', __('Account Name'));
        $show->field('holder.name', __('Permit Holder Name'));
        
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $disableField = true;
        $form = new Form(new PermitApplication());
        $form2 = new Form(new PermitExtendLog());

        //form custom tools
        $form->tools(function (Form\Tools $tools) use ($form) {
            $tools->disableDelete();
            // $tools->append(new DeductPage());
            // $tools->append(new ExtendPage());
            
            // $tools->add(new DeductPage());
            // $permitId = $request('permit_application');
            // $extendInPage = new ExtendInPage();
            // $tools->add($extendInPage($request('permit_application')));
            // dd($extendInPage->html());

            // $tools->add('<a class="btn btn-sm btn-danger" style="margin-left:.5em;"><i class="fa icon-trash"></i>Delete</a>');
        });

        // $form->tools->append(new DeductPage());

        $form->divider(__('Permit Details'));
        $form->select('customer_id', __("Account Name"))->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users')->rules('')->disabled($disableField);

        $form->select('holder_id', __("Permit Holder name"))->options(function ($id2) {
            $holder = PermitHolder::find($id2);
            if ($holder) {
                $outsideHolderName = $holder->name;
                return [$holder->id => $holder->name];
            }
        })->ajax('/admin/api/holders')->rules('')->disabled($disableField);
        $form->date('start_date', __('Start date'))->default(date('Y-m-d'))->disabled($disableField);

        $form->date('end_date', __('End date'))->default(date('Y-m-d'))->disabled($disableField)
            ->value(function () use ($form) {
                $currentEndDate = Carbon::parse($form->model->end_date);

                if ($form->input('days') !== null) {
                    $inputDays = (int) $form->input('days');

                    $resultDate = $currentEndDate->subDays($inputDays);
                    $form->model->end_date = $resultDate;
                    $form->model->save();

                    return $resultDate;
                }
                return $currentEndDate;
            });

        // save into permit extend log
        $form->number('permitExtendLog.days', __('Days Remaining'))->default(function ($form) {
            $startDate = $form->model->start_date;
            $endDate = $form->model->end_date;
            $startDateTime = new DateTime($startDate);
            $endDateTime = new DateTime($endDate);
            $interval = $startDateTime->diff($endDateTime);
            $remainingDays = $interval->days;
            return $remainingDays + 1;
        })->disabled($disableField)->readonly();

        $form->divider(__('Permit Validity (currently not working)'));
        $form->textarea('permitExtendLog.description', __('Justification'))->rules('required');
        


        // $form->number('permitExtendLog.id', __('Extend/Deduct Days'));

        $form->number('days', __('Extend/Deduct Days'));

        


        // callback after form submission
        // $form->submitted(function (Form $form) {
        //     $inputDays = (int) $form->input('days');
            
        //     // $inputDays = (int)1;
        //     $currentEndDate = Carbon::parse($form->model->end_date);

        //     dd($inputDays);

        //     $resultDate = $currentEndDate->addDays($inputDays);
        //     $form->model->end_date = $resultDate;
        //     $form->model->save();

        //     return $resultDate;

        // });

        // callback after form submission
        $form->submitted(function (Form $form) {
            // $inputDays = (int)$form->input('id');
            // dd($inputDays);
            // $form->ignore('days');
        });

        // callback before save
        $form->saving(function (Form $form) {
            // $form->ignore('days');
            $inputDays = (int)$form->input('days');
            // $form->input('days') = '';

            // dd($inputDays);
            // $inputDays = 1;
            
            
        });

        // callback after save
        $form->saved(function (Form $form) {
            
        });



        return $form;
    }

}
