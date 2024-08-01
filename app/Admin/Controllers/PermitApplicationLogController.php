<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PermitApplicationLog;
use OpenAdmin\Admin\Facades\Admin;
use App\Models\AdminUser;
use OpenAdmin\Admin\Auth\Database\Role;

class PermitApplicationLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title;
    
    public function __construct()
    {
        $this->title = __('Permit Application Logs');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PermitApplicationLog());
        //grid settings
        $grid->batchActions(function ($batch) {
            $batch->disableEdit();
            $batch->disableDelete();
        });
        $grid->disableCreateButton();
        if(Admin::user()->inRoles(['phc','jkr', 'fin'])) {
            $grid->disableCreateButton();
            $grid->disableExport();
    
            $grid->actions(function ($actions) {
            $actions->disableDelete();
            });
        }

        //filter settings
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(12, function ($filter) {
                $filter->equal('id', __('Application ID'));
                $filter->like('adminUser.name', __('Modified By'));
                $filter->like('description', __('Description'));
                $filter->date('created_at', __('Created By'));
            });
        });


        if (Admin::user()->inRoles(['sysadmin'])) {
            $roles = Role::all();
            if ($roles->isNotEmpty()) {
                $adminIds = $roles->flatMap->administrators()->pluck('id');
                $adminIds = $roles->pluck('administrators.*.id')->flatten();
                $grid->model()->whereIn('admin_user_id', $adminIds);
                
            }
        }elseif (Admin::user()->inRoles(['phc'])) {
            $roles = Role::where('name', 'phc')->get();
            if ($roles->isNotEmpty()) {
                $adminIds = $roles->flatMap->administrators()->pluck('id');
                $adminIds = $roles->pluck('administrators.*.id')->flatten();
                $grid->model()->whereIn('admin_user_id', $adminIds);
                
            }
        }elseif (Admin::user()->inRoles(['jkr'])) {
            $roles = Role::where('name', 'jkr')->get();
            if ($roles->isNotEmpty()) {
                $adminIds = $roles->flatMap->administrators()->pluck('id');
                $adminIds = $roles->pluck('administrators.*.id')->flatten();
                $grid->model()->whereIn('admin_user_id', $adminIds);
                
            }
        }elseif (Admin::user()->inRoles(['fin'])) {
            $roles = Role::where('name', 'fin')->get();
            if ($roles->isNotEmpty()) {
                $adminIds = $roles->flatMap->administrators()->pluck('id');
                $adminIds = $roles->pluck('administrators.*.id')->flatten();
                $grid->model()->whereIn('admin_user_id', $adminIds);
                
            }
        }

        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('ID'))->sortable();
        $grid->column('adminUser.name', __('Modified By'))->sortable();
        

        $grid->column('description', __('Description'))->width(300)->modal(__('Description'), function ($model) {
            $html = '<table style="width: 100%; table-layout: fixed;">'; 
            $desc = $model->description;
            // Ensure text wraps correctly and handles long words/URLs
            $header = __('Description');
            $html .= <<<HTML
                <tr>
                    <th style="width: 25%; white-space: normal; word-wrap: break-word; overflow-wrap: break-word;">$header</th>
                    <td style="width: 75%; white-space: normal; word-wrap: break-word; overflow-wrap: break-word;">$desc</td>
                </tr>
            HTML;
            $html .= '</table>';
            return $html;
        });

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
        $show = new Show(PermitApplicationLog::findOrFail($id));

        //show settings
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableDelete();
            });;

        $show->field('id', __('ID'));
        $show->field('adminUser.name', __('Modified By'));
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
        $disableEdit = false;
        $form = new Form(new PermitApplicationLog());
        //form settings
        if(Admin::user()->inRoles(['phc','jkr', 'fin'])) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableDelete();
            });
            $form->footer(function ($footer) {
                $footer->disableCreatingCheck();
            });
            $editStatus = true;
        }

        $form->text('permit_application_id', __('Permit application id'))->readonly();
        $form->select('admin_user_id', __('Modified By'))->disabled()->options(AdminUser::all()->pluck('name','id'));

        $form->textarea('description', __('Description'))->disabled(true);

        return $form;
    }
}
