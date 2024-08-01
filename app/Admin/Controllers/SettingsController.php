<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Layout\Content;

use App\Http\Controllers\Controller;
//use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Controllers\Dashboard;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Row;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->title('Settings');
            $content->description('Description...');

            // Add your settings content here
            $content->row(function ($row) {
                $row->column(4, function ($column) {
                    $column->append(Dashboard::dependencies());
                });
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });
            });
            $content->row(function ($row) {
                $row->column(6, function ($column) {
                    $column->append(Dashboard::dependencies());
                });
                $row->column(6, function (Column $column) {
                    $column->append(Dashboard::test_form());
                });
                
            })
            ;
        });
    }
}
