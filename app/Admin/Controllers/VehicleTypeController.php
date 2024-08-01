<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\VehicleType;

class VehicleTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Vehicle Type';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VehicleType());

        $grid->filter(function($filter){
            $filter->like('permit_holder_id');
            $filter->like('name');
        
        });

        $grid->column('id', __('ID'));
        $grid->column('name', __('Name'))->sortable();
        $grid->column('description', __('Description'))->sortable();
        
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
        $show = new Show(VehicleType::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
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
        $form = new Form(new VehicleType());

        $form->text('id', __('Vehicle Type id'))->readonly();
        $form->text('name', __('Name'))->required()
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

        $form->text('description', __('Description'))->required()
            ->rules([
                'required',
                'string',
                'max:255',
            ],[
                'description.required' => 'The Description field is required.',
                'description.string' => 'The Description must be a valid string.',
                'description.max' => 'The Description may not be greater than :max characters.',
            ]);

        return $form;
    }
}
