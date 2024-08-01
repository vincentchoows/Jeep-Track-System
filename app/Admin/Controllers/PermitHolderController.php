<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PermitHolder;
use App\Models\User;

class PermitHolderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Permit Holder';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PermitHolder());
        $grid->filter(function($filter){
            $filter->column(6, function ($filter) {
                $filter->like('user.name', __('Account Name'));
                $filter->like('name', __('Holder Name'));      
            });
            $filter->column(6, function ($filter) {
                $filter->like('identification_no', __('IC No.'));
                $filter->equal('status', __('Status'))->select(['0' => __('Disabled'), '1' => __('Enabled')]);
            });
        });

        $grid->column('id', __('Id'));
        $grid->column('user.name', __('Account holder'))->sortable();
        $grid->column('name', __('Holder Name'))->sortable();
        $grid->column('identification_no', __('Ic no'))->sortable();
        $grid->column('status', __('Status'))->bool()->sortable()->style('text-align: center;');

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
        $show = new Show(PermitHolder::findOrFail($id));

        $show->field('id', __('Holder ID'));
        $show->field('user.name', __('Account Name'));
        $show->field('name', __('Holder Name'));
        $show->field('identification_no', __('Identification No.'));
        $show->field('contact_no', __('Contact no'));
        $show->field('address', __('Address'));
        $show->field('status', __('Status'))->as(function ($phc_check) {
            switch ($phc_check) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>'; 
            }
        })->unescape();
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
        $form = new Form(new PermitHolder());
        $form->setWidth(7,3);

        $form->text('id', __('Holder id'))->readonly();
        $form->select('customer_id', __("Account Name") )->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users')->rules('required');

        $form->text('name', __('Holder Name'))
        ->rules([
            'required',
            'string',
            'max:255',
            'regex:/^[A-Za-z\s]+$/',
        ],[
            'name.required' => 'The Name field is required.',
            'name.string' => 'The Name must be a valid string.',
            'name.max' => 'The Name may not be greater than :max characters.',
            'name.regex' => 'The Name must contain only letters and spaces.',
        ]);

        $form->text('identification_no', __('Identification No.'))
        ->rules([
            'required',
            'regex:/^[a-zA-Z0-9]{12}$/'
        ],
        [
            'identification_no.required' => 'The IC No field is required.',
            'identification_no.regex' => 'The IC No must be exactly 12 alphanumeric characters.'
        ])
        ->placeholder('E.g., 111111223333, A1234567');

        $form->text('contact_no', __('Contact No'))
        ->rules([
            'required',
            'regex:/^\+?[0-9]+$/',
            'min:7', 
            'max:15',
        ],[
            'contact_no.required' => 'The Contact No field is required.',
            'contact_no.regex' => 'The Contact No must contain only digits.',
            'contact_no.min' => 'The Contact No must be at least :min digits long.',
            'contact_no.max' => 'The Contact No may not be greater than :max digits long.',
        ]);
        $form->text('address', __('Address'))
        ->rules([
            'required',
            'string',
            'max:255', 
        ],[
            'address.required' => 'The Address field is required.',
            'address.string' => 'The Address must be a valid string.',
            'address.max' => 'The Address may not be greater than :max characters.',
        ]);
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
