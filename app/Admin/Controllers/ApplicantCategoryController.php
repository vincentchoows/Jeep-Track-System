<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\ApplicantCategory;

class ApplicantCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Applicant Category';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ApplicantCategory());

        $grid->filter(function($filter){
            $filter->column(12, function ($filter) {
                $filter->like('name', __('Category Name'));
                $filter->like('description', __('Description'));
                $filter->equal('approval_required', __('Approval Required'))->select(['0' => __('Not Required'), '1' => __('Required')]);
            });
        });

        $grid->column('id', __('ID'));
        $grid->column('name', __('Name'))->sortable();
        $grid->column('description', __('Description'))->sortable();
        $grid->column('approval_required', __('Approval required'))->sortable()->bool();
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
        $show = new Show(ApplicantCategory::findOrFail($id));

        $show->field('id', __('Applicant Category ID'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('approval_required', __('Approval Required'))->as(function ($value) {
            switch ($value) {
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
        $form = new Form(new ApplicantCategory());

        $form->text('applicant_category_id', __('Applicant Category ID'))->readonly();
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
        $form->switch('approval_required', __('Approval 
        Required'))->default(1);

        

        return $form;
    }
}
