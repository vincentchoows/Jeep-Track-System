<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Transaction;
use App\Models\PermitHolder;
use App\Models\User;
use OpenAdmin\Admin\Facades\Admin;

class TransactionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Transaction';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Transaction());

        //grid settings
        $grid->enableDblClick();
        $grid->batchActions(function ($batch) {
            $batch->disableEdit();
            $batch->disableDelete();
        });

        if(Admin::user()->inRoles([ 'fin'])) {
            $grid->disableCreateButton();
            //$grid->disableExport();
            $grid->actions(function ($actions) {
            $actions->disableDelete();
            });
        }
        $grid->filter(function($filter){
            $filter->like('user.name', __('Account Name'));
            $filter->like('ipay_id', __('IPay ID'));
            $filter->like('ref_no', __('Ref No.'));
            $filter->like('product_description', __('Product Description'));
        });

        $grid->column('id', __('ID'))->sortable();
        $grid->column('user.name', __('Permit Holder Name'))->sortable();
        $grid->column('ipay_id', __('IPay ID'))->sortable();
        $grid->column('ref_no', __('Ref no'))->sortable();
        $grid->column('product_description', __('Product description'))->width(150)->modal(__('Product Description'), function ($model) {
            $html = '<table style="width: 100%; table-layout: fixed;">'; 
            $desc = $model->product_description;
            $header = __('Product Description');
            $html .= <<<HTML
                <tr>
                    <th style="width: 25%; white-space: normal; word-wrap: break-word; overflow-wrap: break-word;">$header</th>
                    <td style="width: 75%; white-space: normal; word-wrap: break-word; overflow-wrap: break-word;">$desc</td>
                </tr>
            HTML;
            $html .= '</table>';
            return $html;
        });
        $grid->column('status', __('Status'))->bool()->sortable();        


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
        $show = new Show(Transaction::findOrFail($id));
        //show setting
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableDelete();
            });

        $show->field('id', __('ID'));
        $show->field('user.name', __('Account Name'));
        $show->field('ipay_id', __('IPay ID'));
        $show->field('error_description', __('Error Description'));
        $show->field('signature', __('Signature'));
        $show->field('ref_no', __('Reference No.'));
        $show->field('currency', __('Currency'));
        $show->field('product_description', __('Product Description'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('contact_no', __('Contact no'));
        $show->field('permit_application_id', __('Permit Application ID'))->as(function ($value) {
            return $value ?? '<span style="color: red;">Not Available</span>';
        })->unescape();
        
        $show->field('renewal_id', __('Renewal ID'))->as(function ($value) {
            return $value ?? '<span style="color: red;">Not Available</span>';

        })->unescape();
        $show->field('lost_permit_id', __('Lost permit ID'))->as(function ($value) {
            return $value ?? '<span style="color: red;">Not Available</span>';

        })->unescape();
        $show->field('permit_holder_id', __('Permit holder ID'))->as(function ($value) {
            return $value ?? '<span style="color: red;">Not Available</span>';

        })->unescape();
        $show->field('total', __('Total'));
        $show->field('payment_method_id', __('Payment method ID'))->as(function ($value) {
            return $value ?? '<span style="color: red;">Not Available</span>';

        })->unescape();
        $show->field('payment_type', __('Payment Type'))->as(function ($value) {
            return $value ?? '<span style="color: red;">Not Available</span>';

        })->unescape();
        
        $show->field('status', __('Status'))->as(function ($check) {
            switch ($check) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>'; 
            }
        })->unescape();



        $show->field('remark', __('Remark'));
        $show->field('ccname', __('Ccname'));
        $show->field('s_bankname', __('S bankname'));
        $show->field('s_country', __('S country'));
        $show->field('bank_mid', __('Bank mid'));
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
        $disableEdit = true;

        if(Admin::user()->inRoles(['sysadmin'])) {
            $disableEdit = false;
        }
        $form = new Form(new Transaction());

        //form setting
        if(Admin::user()->inRoles(['phc','jkr', 'fin'])) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableDelete();
            });
            $form->footer(function ($footer) {
                $footer->disableCreatingCheck();
            });
        }

        $form->text('id', __('Transaction ID'))->disabled();
        $form->select('customer_id', __("Account Name") )->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users')->rules('required')->disabled($disableEdit);
        $form->text('ipay_id', __('Ipay id'))->disabled();
        $form->ckeditor('error_description', __('Error description'))->rules([
            'nullable',
            'string',
            'max:255',
        ], [
            'description.string' => 'The Description must be a valid string.',
            'description.max' => 'The Description may not be greater than :max characters.',
        ])->disabled($disableEdit);
        $form->ckeditor('signature', __('Signature'))->rules([
            'nullable',
            'string',
            'max:255',
        ], [
            'description.string' => 'The Description must be a valid string.',
            'description.max' => 'The Description may not be greater than :max characters.',
        ])->disabled($disableEdit);
        $form->text('ref_no', __('Ref no'))->disabled();
        $form->text('currency', __('Currency'))->disabled($disableEdit);
        $form->text('product_description', __('Product description'))->disabled($disableEdit);
        $form->text('name', __('Name'))->disabled($disableEdit);
        $form->email('email', __('Email'))->disabled($disableEdit);
        $form->text('contact_no', __('Contact No'))->disabled($disableEdit);
        $form->text('permit_application_id', __('Permit Application ID'))->disabled();
        $form->text('renewal_id', __('Renewal ID'))->disabled();
        $form->text('lost_permit_id', __('Lost Permit ID'))->disabled();
        $form->text('permit_holder_id', __('Permit Holder ID'))->disabled();
        $form->decimal('total', __('Total'))->disabled($disableEdit);
        $form->text('payment_method_id', __('Payment Method ID'))->disabled();
        $form->text('payment_type', __('Payment type'))->disabled($disableEdit)->disabled();

        $form->switch('status', __('Status'))->disabled($disableEdit);


        $form->ckeditor('remark', __('Remark'))->rules([
            'nullable',
            'string',
            'max:255',
        ], [
            'description.string' => 'The Description must be a valid string.',
            'description.max' => 'The Description may not be greater than :max characters.',
        ])->disabled($disableEdit);



        $form->text('ccname', __('Ccname'))->disabled($disableEdit);
        $form->text('s_bankname', __('S bankname'))->disabled($disableEdit);
        $form->text('s_country', __('S country'))->disabled($disableEdit);
        $form->text('bank_mid', __('Bank mid'))->disabled($disableEdit);

        return $form;
    }
}
