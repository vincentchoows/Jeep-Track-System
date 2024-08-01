<?php

namespace App\Admin\Controllers;

use App\Models\PermitApplication;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PermitExtendLog;
use App\Admin\Extensions\Tools\UserGender;
use App\Admin\Extensions\Tools\ReleasePost;
use App\Admin\Actions\Page\ReportPage;
use App\Admin\Actions\Page\ExtendPage;
use App\Admin\Actions\Page\DeductPage;
use OpenAdmin\Admin\Actions\BatchAction;
use Illuminate\Http\Request;

class PermitExtendLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Permit Extend Log';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PermitExtendLog());

        //grid settings
        $grid->batchActions(function ($batch) {
            $batch->disableEdit();
            $batch->disableDelete();
        });
        $grid->disableCreateButton();
        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('ID'))->sortable();
        $grid->column('adminUser.name', __('Admin User'))->sortable();
        $grid->column('description', __('Description'))->width(150)->modal(__('Description'), function ($model) {
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



        $grid->column('days', __('Days'))->sortable();
        $grid->column('created_at', __('Created At'))->sortable();

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
        $show = new Show(PermitExtendLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('admin_user', __('Admin user'));
        $show->field('description', __('Description'));
        $show->field('days', __('Days'));
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
        $form = new Form(new PermitExtendLog());

        $form->number('admin_user', __('Admin user'));
        $form->textarea('description', __('Description'));
        $form->number('days', __('Days'));

        return $form;
    }

    
}
