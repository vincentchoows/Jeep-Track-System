<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PermitRenewal;

class PermitRenewalController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Permit Renewal';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PermitRenewal());

        $grid->column('id', __('Id'));
        $grid->column('permit_application_id', __('Permit application id'));
        $grid->column('status', __('Status'));
        $grid->column('renewal_start_date', __('Renewal start date'));
        $grid->column('renewal_end_date', __('Renewal end date'));
        $grid->column('old_end_date', __('Old end date'));
        $grid->column('date_approved', __('Date approved'));
        $grid->column('approved_by', __('Approved by'));
        $grid->column('date_reject', __('Date reject'));
        $grid->column('rejected_by', __('Rejected by'));
        $grid->column('transaction_id', __('Transaction id'));
        $grid->column('transaction_status', __('Transaction status'));
        $grid->column('transaction_date', __('Transaction date'));
        $grid->column('is_cancel', __('Is cancel'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(PermitRenewal::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('permit_application_id', __('Permit application id'));
        $show->field('status', __('Status'));
        $show->field('renewal_start_date', __('Renewal start date'));
        $show->field('renewal_end_date', __('Renewal end date'));
        $show->field('old_end_date', __('Old end date'));
        $show->field('date_approved', __('Date approved'));
        $show->field('approved_by', __('Approved by'));
        $show->field('date_reject', __('Date reject'));
        $show->field('rejected_by', __('Rejected by'));
        $show->field('transaction_id', __('Transaction id'));
        $show->field('transaction_status', __('Transaction status'));
        $show->field('transaction_date', __('Transaction date'));
        $show->field('is_cancel', __('Is cancel'));
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
        $form = new Form(new PermitRenewal());

        $form->number('permit_application_id', __('Permit application id'));
        $form->number('status', __('Status'));
        $form->datetime('renewal_start_date', __('Renewal start date'))->default(date('Y-m-d H:i:s'));
        $form->datetime('renewal_end_date', __('Renewal end date'))->default(date('Y-m-d H:i:s'));
        $form->datetime('old_end_date', __('Old end date'))->default(date('Y-m-d H:i:s'));
        $form->datetime('date_approved', __('Date approved'))->default(date('Y-m-d H:i:s'));
        $form->number('approved_by', __('Approved by'));
        $form->datetime('date_reject', __('Date reject'))->default(date('Y-m-d H:i:s'));
        $form->number('rejected_by', __('Rejected by'));
        $form->text('transaction_id', __('Transaction id'));
        $form->number('transaction_status', __('Transaction status'));
        $form->datetime('transaction_date', __('Transaction date'))->default(date('Y-m-d H:i:s'));
        $form->number('is_cancel', __('Is cancel'));

        return $form;
    }
}
