<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApplicantCategory;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Controllers\Dashboard;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Widgets\Tab;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Form\Field\Hidden;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PermitApplication;
use \App\Models\PermitHolder;
use App\Admin\Controllers\UserController;
use App\Models\User;
use App\Models\AdminUser;
use App\Models\VehicleType;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Facades\Admin;
use App\Admin\Controllers\Illuminate\Http\UploadedFile;
use OpenAdmin\Admin\Config\Config;
use Carbon\Carbon;
use DateTime;
use OpenAdmin\Admin\Widgets\Table;
use App\Admin\Forms\Steps;
use OpenAdmin\Admin\Widgets\MultipleSteps;
use Illuminate\Support\Facades\Validator;


class PermitApplicationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Permit Application';

    protected $roles = [
        'phc_checker' => 'phc_check',
        'phc_approver' => 'phc_approve',
        'phc_second_approver' => 'phc_second_approve',
        'jkr_checker' => 'jkr_check',
        'jkr_approver' => 'jkr_approve',
        'fin_checker' => 'finance_check',
        'fin_approver' => 'finance_approve',
    ];

    protected $dependencies = [
        'phc_check' => [''],
        'phc_approve' => ['phc_check'],
        'phc_second_approve' => ['phc_check', 'phc_approve'],
        'jkr_check' => ['phc_check', 'phc_approve', 'phc_second_approve'],
        'jkr_approve' => ['phc_check', 'phc_approve', 'phc_second_approve', 'jkr_check'],
        'finance_check' => ['phc_check', 'phc_approve', 'phc_second_approve', 'jkr_check', 'jkr_approve'],
        'finance_approve' => ['phc_check', 'phc_approve', 'phc_second_approve', 'jkr_check', 'jkr_approve', 'finance_check'],
    ];

    protected $statusLabels = [
        0 => 'Reviewing',
        1 => 'Rejected',
        2 => 'Pending Payment',
        3 => 'Processing Payment',
        4 => 'Activated',
        5 => 'Disabled',
        6 => 'Terminated',
    ];
    protected $statusLabelsDropdown = [
        0 => '0. Reviewing',
        1 => '1. Rejected',
        2 => '2. Pending Payment',
        3 => '3. Processing Payment',
        4 => '4. Activated',
        5 => '5. Disabled',
        6 => '6. Terminated',
    ];

    protected $statusLabelsExpressDropdown = [
        4 => 'Activated',
        5 => 'Disabled',
        6 => 'Terminated',
    ];

    protected $statusLabelsPayment = [
        0 => 'Unpaid',
        1 => 'Paid',
    ];
    protected $statusDotsPayment = [
        0 => 'danger',
        1 => 'success',
    ];
    protected $statusLabelsRoles = [
        0 => 'Reviewing',
        1 => 'Rejected',
        2 => 'Approved',
    ];

    protected $statusLabelsRolesDot = [
        0 => 'info',
        2 => 'success',
        4 => 'success',
        5 => 'warning',
        6 => 'danger',
    ];

    protected $statusRadioButtons = [
        0 => 'Disabled',
        1 => 'Active',
    ];

    protected $statusPayment = [
        0 => 'Unpaid',
        1 => 'Paid',
    ];
    protected $statusPaymentDots = [
        0 => 'warning',
        1 => 'success',
    ];

    protected $fileStatus = [
        '1' => 'Reject',
        '2' => 'Approve',
    ];

    protected $applicationType = [
        '0' => 'Basic',
        '1' => 'Express',
    ];

    protected $statusLabelsPermit = [
        0 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: blue; padding: 2px 6px; border-radius: 4px;">Reviewing</div>',
        1 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: orange; padding: 2px 6px; border-radius: 4px;">Rejected</div>',
        2 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: green; padding: 2px 6px; border-radius: 4px;">Pending Payment</div>',
        3 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: blue; padding: 2px 6px; border-radius: 4px;">Processing Payment</div>',
        4 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: green; padding: 2px 6px; border-radius: 4px;">Activated</div>',
        5 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: red; padding: 2px 6px; border-radius: 4px;">Disabled</div>',
        6 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: red; padding: 2px 6px; border-radius: 4px;">Terminated</div>',
    ];
    protected $fileStatusFields = [
        'surat_permohonan_status',
        'surat_indemnity_status',
        'surat_sokongan_status',
        'salinan_kad_pengenalan_status',
        'salinan_lesen_memandu_status',
        'salinan_geran_kenderaan_status',
        'salinan_insurans_kenderaan_status',
        'salinan_road_tax_status',
        'gambar_kenderaan_status',
    ];

    public function index(Content $content)
    {
        $tab = new Tab();
        
        if (Admin::user()->inRoles(['sysadmin'])) {
            $tab->add(__('Overall'), $this->adminOverallGrid()->render());
            $tab->add(__('Express'), $this->adminExpressGrid()->render());
            $tab->add(__('Terminated'), $this->adminTerminatedGrid()->render());
        } else {
            $tab->add(__('Reviewing'), $this->staffReviewingGrid()->render());
            $tab->add(__('Approved'), $this->staffApprovedGrid()->render());

            if (Admin::user()->inRoles(['fin'])) {
                $tab->add(__('Overall'), $this->staffOverallGrid()->render());
            }
        }
        //Render content
        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            ->title(__('Permit Application'))
            ->description(__('Description...'))
            ->row(function (Row $row2) {
                //Infobox
                $row2->column(6, function (Column $column) {
                    $column->append(Dashboard::new_tab_infobox());
                });
                $row2->column(6, function (Column $column) {
                    $column->append(Dashboard::approved_tab_infobox());
                });
            })
            ->row(function (Row $row2) use ($tab) {
                $row2->column(12, $tab->render());
            });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

    /*
    |--------------------------------------------------------------------------
    | Administrator/Sysadmin grid
    |--------------------------------------------------------------------------
    */

    //DISABLED
    public function adminNewGrid($status = 0)
    {
        $grid = new Grid(new PermitApplication());
        // 'Displays New applications status = 0' .
        $grid->header(function ($query) {
            return $this->generateGridHeaderSysadminHTML();
        });

        //Grid setting
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        //filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->column(12, function ($filter) {
                $filter->like('user.name', __('Account name'));
                $filter->like('holder.name', __('Holder name'));
            });
            $filter->column(6, function ($filter) {
                $filter->equal('status', __('Status'))->select($this->statusLabelsRoles);
                $filter->equal('phc_check', __('PHC1'))->select($this->statusLabelsRoles);
                $filter->equal('phc_approve', __('PHC2'))->select($this->statusLabelsRoles);
                $filter->equal('phc_second_approve', __('PHC3'))->select($this->statusLabelsRoles);
            });
            $filter->column(6, function ($filter) {
                $filter->equal('jkr_check', __('JKR1'))->select($this->statusLabelsRoles);
                $filter->equal('jkr_approve', __('JKR2'))->select($this->statusLabelsRoles);
                $filter->equal('finance_check', __('FIN1'))->select($this->statusLabelsRoles);
                $filter->equal('finance_approve', __('FIN2'))->select($this->statusLabelsRoles);
            });
        });

        //Db query
        $grid->model()->where('status', $status)
            ->orderBy('created_at', 'desc');

        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(100);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(150);

        $grid->column('status', __('STATUS'))->replace($this->statusLabelsPermit);
        $grid->column('phc_check', __('PHC1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('transaction_status', __('PAYMENT'))->using($this->statusLabelsPayment, 'Unknown')
            ->dot($this->statusDotsPayment, 'warning')
            ->sortable();
        $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();

        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });

        return $grid;
    }

    //DISABLED
    public function adminRejectedGrid($status = 1)
    {
        $grid = new Grid(new PermitApplication());

        $grid->header(function ($query) {
            return $this->generateGridHeaderSysadminHTML();
        });

        //Grid setting
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        //filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->column(12, function ($filter) {
                $filter->like('user.name', __('Account name'));
                $filter->like('holder.name', __('Holder name'));
            });
            $filter->column(6, function ($filter) {
                $filter->equal('status', __('Status'))->select($this->statusLabelsRoles);
                $filter->equal('phc_check', __('PHC1'))->select($this->statusLabelsRoles);
                $filter->equal('phc_approve', __('PHC2'))->select($this->statusLabelsRoles);
                $filter->equal('phc_second_approve', __('PHC3'))->select($this->statusLabelsRoles);
            });
            $filter->column(6, function ($filter) {
                $filter->equal('jkr_check', __('JKR1'))->select($this->statusLabelsRoles);
                $filter->equal('jkr_approve', __('JKR2'))->select($this->statusLabelsRoles);
                $filter->equal('finance_check', __('FIN1'))->select($this->statusLabelsRoles);
                $filter->equal('finance_approve', __('FIN2'))->select($this->statusLabelsRoles);
            });

        });
        //Db query
        $grid->model()->where('status', $status)
            ->orderBy('created_at', 'desc');

        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(100);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(150);

        $grid->column('status', __('STATUS'))->replace($this->statusLabelsPermit);
        $grid->column('phc_check', __('PHC1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('transaction_status', __('PAYMENT'))->using($this->statusLabelsPayment, 'Unknown')
            ->dot($this->statusDotsPayment, 'warning')
            ->sortable();
        $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        return $grid;
    }

    //DISABLED
    public function adminTransactionGrid($status = 2)
    {
        $grid = new Grid(new PermitApplication());

        $grid->header(function ($query) {
            return $this->generateGridHeaderSysadminHTML();
        });

        //Grid setting
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        //filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->column(12, function ($filter) {
                $filter->like('user.name', __('Account name'));
                $filter->like('holder.name', __('Holder name'));
            });
            $filter->column(6, function ($filter) {
                $filter->equal('status', __('Status'))->select($this->statusLabelsRoles);
                $filter->equal('phc_check', __('PHC1'))->select($this->statusLabelsRoles);
                $filter->equal('phc_approve', __('PHC2'))->select($this->statusLabelsRoles);
                $filter->equal('phc_second_approve', __('PHC3'))->select($this->statusLabelsRoles);
            });
            $filter->column(6, function ($filter) {
                $filter->equal('jkr_check', __('JKR1'))->select($this->statusLabelsRoles);
                $filter->equal('jkr_approve', __('JKR2'))->select($this->statusLabelsRoles);
                $filter->equal('finance_check', __('FIN1'))->select($this->statusLabelsRoles);
                $filter->equal('finance_approve', __('FIN2'))->select($this->statusLabelsRoles);
            });

        });
        //Db query
        $grid->model()->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->where('transaction_status');

        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(100);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(150);

        $grid->column('status', __('STATUS'))->replace($this->statusLabelsPermit);
        $grid->column('phc_check', __('PHC1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('transaction_status', __('PAYMENT'))->using($this->statusLabelsPayment, 'Unknown')
            ->dot($this->statusDotsPayment, 'warning')
            ->sortable();
        $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        return $grid;
    }

    //DISABLED
    public function adminApprovedGrid($status = 2)
    {
        $grid = new Grid(new PermitApplication());
        $grid->header(function ($query) {
            return $this->generateGridHeaderSysadminHTML();
        });

        //Grid setting
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        //filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->column(12, function ($filter) {
                $filter->like('user.name', __('Account name'));
                $filter->like('holder.name', __('Holder name'));
            });
            $filter->column(6, function ($filter) {
                $filter->equal('status', __('Status'))->select($this->statusLabelsRoles);
                $filter->equal('phc_check', __('PHC1'))->select($this->statusLabelsRoles);
                $filter->equal('phc_approve', __('PHC2'))->select($this->statusLabelsRoles);
                $filter->equal('phc_second_approve', __('PHC3'))->select($this->statusLabelsRoles);
            });
            $filter->column(6, function ($filter) {
                $filter->equal('jkr_check', __('JKR1'))->select($this->statusLabelsRoles);
                $filter->equal('jkr_approve', __('JKR2'))->select($this->statusLabelsRoles);
                $filter->equal('finance_check', __('FIN1'))->select($this->statusLabelsRoles);
                $filter->equal('finance_approve', __('FIN2'))->select($this->statusLabelsRoles);
            });
        });

        //Db query
        $grid->model()->where('status', $status)
            ->orderBy('created_at', 'desc');

        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(100);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(150);

        $grid->column('status', __('STATUS'))->replace($this->statusLabelsPermit);
        $grid->column('phc_check', __('PHC1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('transaction_status', __('PAYMENT'))->using($this->statusLabelsPayment, 'Unknown')
            ->dot($this->statusDotsPayment, 'warning')
            ->sortable();
        $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });

        return $grid;
    }

    public function adminTerminatedGrid($status = 6)
    {
        $grid = new Grid(new PermitApplication());
        $grid->header(function ($query) {
            return $this->generateGridHeaderSysadminHTML();
        });

        //Grid setting
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        //filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->column(12, function ($filter) {
                $filter->like('user.name', __('Account name'));
                $filter->like('holder.name', __('Holder name'));
                $filter->between('created_at', __('Created At'))->date();
                $filter->between('modified_at', __('Modified At'))->date();
            });
            $filter->column(6, function ($filter) {
                $filter->equal('status', __('Status'))->select($this->statusLabelsDropdown);
                $filter->equal('phc_check', __('PHC1'))->select($this->statusLabelsRoles);
                $filter->equal('phc_approve', __('PHC2'))->select($this->statusLabelsRoles);
                $filter->equal('phc_second_approve', __('PHC3'))->select($this->statusLabelsRoles);
            });
            $filter->column(6, function ($filter) {
                $filter->equal('jkr_check', __('JKR1'))->select($this->statusLabelsRoles);
                $filter->equal('jkr_approve', __('JKR2'))->select($this->statusLabelsRoles);
                $filter->equal('finance_check', __('FIN1'))->select($this->statusLabelsRoles);
                $filter->equal('finance_approve', __('FIN2'))->select($this->statusLabelsRoles);
            });

        });

        //Db query
        $grid->model()->where('status', $status)
            ->where('application_type', '=', 0)
            ->orderBy('created_at', 'desc');

        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(100);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(150);

        $grid->column('status', __('STATUS'))->replace($this->statusLabelsPermit);
        $grid->column('phc_check', __('PHC1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('transaction_status', __('PAYMENT'))->using($this->statusLabelsPayment, 'Unknown')
            ->dot($this->statusDotsPayment, 'warning')
            ->sortable();
        $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });

        return $grid;
    }
    public function adminOverallGrid()
    {
        $grid = new Grid(new PermitApplication());
        $grid->header(function ($query) {
            return $this->generateGridHeaderSysadminHTML();
        });

        //Grid setting
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        //filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->column(6, function ($filter) {
                $filter->like('user.name', __('Account name'));
                $filter->like('holder.name', __('Holder name'));
                $filter->equal('application_type', __('Application type'))->select($this->applicationType);
            });
            $filter->column(6, function ($filter) {
                $filter->between('created_at', __('Created At'))->date();
                $filter->between('modified_at', __('Modified At'))->date();
            });
            $filter->column(6, function ($filter) {
                $filter->equal('status', __('Status'))->select($this->statusLabelsDropdown);
                $filter->equal('phc_check', __('PHC1'))->select($this->statusLabelsRoles);
                $filter->equal('phc_approve', __('PHC2'))->select($this->statusLabelsRoles);
                $filter->equal('phc_second_approve', __('PHC3'))->select($this->statusLabelsRoles);
            });
            $filter->column(6, function ($filter) {
                $filter->equal('jkr_check', __('JKR1'))->select($this->statusLabelsRoles);
                $filter->equal('jkr_approve', __('JKR2'))->select($this->statusLabelsRoles);
                $filter->equal('finance_check', __('FIN1'))->select($this->statusLabelsRoles);
                $filter->equal('finance_approve', __('FIN2'))->select($this->statusLabelsRoles);
            });

        });

        //Db query
        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(100);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(150);
        $grid->column('application_type', __('Application Type'))
            ->sortable()
            ->using($this->applicationType)
            ->display(function ($value) {
                return '<div style="text-align: center;">' . $value . '</div>';
            });

        $grid->column('status', __('STATUS'))->replace($this->statusLabelsPermit);
        $grid->column('phc_check', __('PHC1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('transaction_status', __('PAYMENT'))->using($this->statusLabelsPayment, 'Unknown')
            ->dot($this->statusDotsPayment, 'warning')
            ->sortable();
        $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });

        return $grid;
    }

    public function adminExpressGrid()
    {
        $grid = new Grid(new PermitApplication());
        $grid->header(function ($query) {
            return $this->generateGridHeaderSysadminHTML();
        });

        //Grid setting
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        //filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->column(12, function ($filter) {
                $filter->like('application_type', __('Application Type'))->select($this->applicationType);
                $filter->equal('status', __('Status'))->select($this->statusLabelsExpressDropdown);
                $filter->like('other_attachment_comment', __('Comment'));
                $filter->between('created_at', __('Created At'))->date();
                $filter->between('modified_at', __('Modified At'))->date();
            });
        });

        //Db query
        $grid->model()->orderBy('created_at', 'desc')->where('application_type', '=', 1);

        //Grid columns
        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('application_type', __('Application Type'))
            ->sortable()
            ->using($this->applicationType);
        $grid->column('status', __('STATUS'))->replace($this->statusLabelsPermit)->sortable();

        $grid->column('other_attachment_comment', __('Comment'))->sortable()->width(300);

        $grid->column('created_at', __('Created At'))
            ->sortable()
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        $grid->column('modified_at', __('Modified At'))
            ->sortable()
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });

        return $grid;
    }

    /*
    |--------------------------------------------------------------------------
    | Normal Admin User grid
    |--------------------------------------------------------------------------
    */

    public function staffReviewingGrid()
    {
        $grid = new Grid(new PermitApplication());
        $grid->header(function ($query) {
            return $this->generateGridHeaderHTML();
        });

        //Grid settings
        if (Admin::user()->inRoles(['phc', 'jkr', 'fin'])) {
            $grid->batchActions(function ($batch) {
                $batch->disableEdit();
                $batch->disableDelete();
            });

            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });
        }

        //Filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->disableIdFilter();

            $filter->column(12, function ($filter) {
                $filter->equal('id', __('Application ID'));
                $filter->like('holder.name', __('Permit Holder name'));
                $filter->like('purpose', __('Purpose'));
                $filter->between('created_at', __('Created At'));
                $filter->between('modified_at', __('Modified At'))->date();
            });

            if (Admin::user()->inRoles(['sysadmin'])) {
                $filter->column(6, function ($filter) {
                    $filter->equal('phc_check', __('PHC Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('phc_approve', __('PHC Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('phc_second_approve', __('PHC Second Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                });
                $filter->column(6, function ($filter) {
                    $filter->equal('jkr_check', __('JKR Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('jkr_approve', __('JKR Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('finance_check', __('Finance Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('finance_approve', __('Finance Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                });
            }
            if (Admin::user()->inRoles(['phc'])) {
                $filter->equal('phc_check', __('PHC Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('phc_approve', __('PHC Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('phc_second_approve', __('PHC Second Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
            }
            if (Admin::user()->inRoles(['jkr'])) {
                $filter->equal('jkr_check', __('JKR Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('jkr_approve', __('JKR Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
            }
            if (Admin::user()->inRoles(['fin'])) {
                $filter->equal('finance_check', __('Finance Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('finance_approve', __('Finance Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
            }
        });

        //get the admin user
        $grid->model()->orderBy('created_at', 'desc')
            ->whereNotIn('status', ['4,5,6'])
            ->where('application_type', 0);
        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(200);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(200);
        $grid->column('purpose', __('Purpose'))->width(300)->sortable();;
        $grid->column('applicantcat.name', __('Category'))->width(100)->sortable();;

        //PHC grid
        if (Admin::user()->inRoles(['phc'])) {
            if (Admin::user()->inRoles(['phc-checker'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->whereIn('phc_check', [0, 1]);
                    });
                $grid->column('phc_check', __('PHC1'))
                    ->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            } elseif (Admin::user()->inRoles(['phc-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where(function ($query) {
                            $query->where('phc_check', '=', 2)
                                ->whereIn('phc_approve', [0, 1]);
                        });
                    });

                $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();


            } elseif (Admin::user()->inRoles(['phc-second-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where(function ($query) {
                            $query->where('phc_check', '=', 2)
                                ->where('phc_approve', '=', 2)
                                ->whereIn('phc_second_approve', [0, 1]);;
                        });
                    });

                $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();
            }


        } elseif (Admin::user()->inRoles(['jkr'])) {
            //JKR Grid

            //Verify PHC check status
            $grid->model()
                ->where(function ($query) {
                    $query->where('phc_check', '=', 2)
                        ->where('phc_approve', '=', 2)
                        ->where('phc_second_approve', '=', 2);
                });

            if (Admin::user()->inRoles(['jkr-checker'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('jkr_check', '=', 0);
                    });
                $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            } elseif (Admin::user()->inRoles(['jkr-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('jkr_check', '=', 2)
                            ->where('jkr_approve', '=', 0);
                    });
                $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();
            }

        } elseif (Admin::user()->inRoles(['fin'])) {
            //FIN grid
            $grid->model()
                ->where(function ($query) {
                    $query->where('phc_check', '=', 2)
                        ->where('phc_approve', '=', 2)
                        ->where('phc_second_approve', '=', 2)
                        ->where('jkr_check', '=', 2)
                        ->where('jkr_approve', '=', 2)
                        ->where('transaction_status', '=', 1);
                });
            if (Admin::user()->inRoles(['fin-checker'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('finance_check', '=', 0);
                    });

                $grid->column('transaction_status', __('Transaction'))->using($this->statusPayment, 'Unknown')
                    ->dot($this->statusPaymentDots, 'warning')
                    ->sortable();

                $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();
            } elseif (Admin::user()->inRoles(['fin-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('finance_check', '=', 2)
                            ->where('finance_approve', '=', 0);
                    });
                $grid->column('transaction_status', __('Transaction'))->using($this->statusPayment, 'Unknown')
                    ->dot($this->statusPaymentDots, 'warning')
                    ->sortable();

                $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();
            }

        } else {
        }

        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        $grid->column('modified_at', __('Modified At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        return $grid;
    }

    public function staffRejectedGrid()
    {
        $grid = new Grid(new PermitApplication());
        $grid->header(function ($query) {
            return $this->generateGridHeaderHTML();
        });

        // Grid settings
        if (Admin::user()->inRoles(['phc', 'jkr', 'fin'])) {
            $grid->batchActions(function ($batch) {
                $batch->disableEdit();
                $batch->disableDelete();
            });

            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });
        }

        //Filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->disableIdFilter();

            $filter->column(12, function ($filter) {
                $filter->equal('id', __('Application ID'));
                $filter->like('holder.name', __('Permit Holder name'));
                $filter->like('purpose', __('Purpose'));
            });

            if (Admin::user()->inRoles(['sysadmin'])) {
                $filter->column(6, function ($filter) {
                    $filter->equal('phc_check', __('PHC Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('phc_approve', __('PHC Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('phc_second_approve', __('PHC Second Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                });
                $filter->column(6, function ($filter) {
                    $filter->equal('jkr_check', __('JKR Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('jkr_approve', __('JKR Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('finance_check', __('Finance Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('finance_approve', __('Finance Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                });
            }
            if (Admin::user()->inRoles(['phc'])) {
                $filter->equal('phc_check', __('PHC Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('phc_approve', __('PHC Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('phc_second_approve', __('PHC Second Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);

            }
            if (Admin::user()->inRoles(['jkr'])) {
                $filter->equal('jkr_check', __('JKR Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('jkr_approve', __('JKR Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
            }
            if (Admin::user()->inRoles(['fin'])) {
                $filter->equal('finance_check', __('Finance Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('finance_approve', __('Finance Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
            }
        });

        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(200);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(250);

        //PHC grid
        if (Admin::user()->inRoles(['phc'])) {
            if (Admin::user()->inRoles(['phc-checker'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('phc_check', '=', 1);
                    });
                $grid->column('phc_check', __('PHC1'))
                    ->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            } elseif (Admin::user()->inRoles(['phc-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('phc_approve', '=', 1);
                    });

                $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            } elseif (Admin::user()->inRoles(['phc-second-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('phc_second_approve', '=', 1);
                    });
                $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();
            }

            //JKR grid
        } elseif (Admin::user()->inRoles(['jkr'])) {

            if (Admin::user()->inRoles(['jkr-checker'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('jkr_check', '=', 1);
                    });
                $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();
            } elseif (Admin::user()->inRoles(['jkr-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('jkr_approve', '=', 1);
                    });
                $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();
            } else {

            }

        } elseif (Admin::user()->inRoles(['fin'])) {
            //FIN grid

            if (Admin::user()->inRoles(['fin-checker'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('finance_check', '=', 1);
                    });
                $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            } elseif (Admin::user()->inRoles(['fin-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('finance_approve', '=', 1);
                    });
                $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            }
        } else {
            $grid->column('phc_check', __('PHC Check'))->switch()->sortable()->style('text-align: center;');
            $grid->column('phc_approve', __('PHC Approve'))->switch()->sortable()->style('text-align: center;');
            $grid->column('phc_second_approve', __('PHC Second Approve'))->switch()->sortable()->style('text-align: center;');
            $grid->column('jkr_check', __('JKR Check'))->switch()->sortable()->style('text-align: center;');
            $grid->column('jkr_approve', __('JKR Approve'))->switch()->sortable()->style('text-align: center;');
            $grid->column('finance_check', __('Finance Check'))->switch()->sortable()->style('text-align: center;');
            $grid->column('finance_approve', __('Finance Approve'))->switch()->sortable()->style('text-align: center;');
        }

        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        $grid->column('modified_at', __('Modified At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        return $grid;
    }

    public function staffApprovedGrid()
    {
        $grid = new Grid(new PermitApplication());
        $grid->header(function ($query) {
            return $this->generateGridHeaderHTML();
        });
        //grid settings
        if (Admin::user()->inRoles(['phc', 'jkr', 'fin'])) {
            $grid->batchActions(function ($batch) {
                $batch->disableEdit();
                $batch->disableDelete();
            });

            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                if (!Admin::user()->inRoles(['fin-checker', 'fin-approver'])) {
                    $actions->disableEdit();
                }
            });
        }

        //Filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->disableIdFilter();

            $filter->column(12, function ($filter) {
                $filter->equal('id', __('Application ID'));
                $filter->like('holder.name', __('Permit Holder name'));
                $filter->like('purpose', __('Purpose'));
            });

            if (Admin::user()->inRoles(['sysadmin'])) {
                $filter->column(6, function ($filter) {
                    $filter->equal('phc_check', __('PHC Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('phc_approve', __('PHC Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('phc_second_approve', __('PHC Second Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                });
                $filter->column(6, function ($filter) {
                    $filter->equal('jkr_check', __('JKR Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('jkr_approve', __('JKR Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('finance_check', __('Finance Checking'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                    $filter->equal('finance_approve', __('Finance Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                });
            }
            if (Admin::user()->inRoles(['phc'])) {
                $filter->equal('phc_check', __('PHC Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('phc_approve', __('PHC Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('phc_second_approve', __('PHC Second Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);

            }
            if (Admin::user()->inRoles(['jkr'])) {
                $filter->equal('jkr_check', __('JKR Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('jkr_approve', __('JKR Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
            }
            if (Admin::user()->inRoles(['fin'])) {
                $filter->equal('finance_check', __('Finance Check'))->select(['0' => __('Pending'), '1' => __('Approved')]);
                $filter->equal('finance_approve', __('Finance Approve'))->select(['0' => __('Pending'), '1' => __('Approved')]);
            }
        });

        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(200);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(250);

        //PHC grid
        if (Admin::user()->inRoles(['phc'])) {

            if (Admin::user()->inRoles(['phc'])) {

                if (Admin::user()->inRoles(['phc-checker'])) {
                    $grid->model()
                        ->where(function ($query) {
                            $query->where('phc_check', '=', 2);
                        });
                    $grid->column('phc_check', __('PHC1'))->using($this->statusLabelsRoles, 'Unknown')
                        ->dot($this->statusLabelsRolesDot, 'warning')
                        ->sortable();

                } elseif (Admin::user()->inRoles(['phc-approver'])) {
                    $grid->model()
                        ->where(function ($query) {
                            $query->where('phc_approve', '=', 2);
                        });
                    $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
                        ->dot($this->statusLabelsRolesDot, 'warning')
                        ->sortable();

                } elseif (Admin::user()->inRoles(['phc-second-approver'])) {
                    $grid->model()
                        ->where(function ($query) {
                            $query->where('phc_second_approve', '=', 2);
                        });
                    $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
                        ->dot($this->statusLabelsRolesDot, 'warning')
                        ->sortable();
                }
            }

            //JKR grid
        } elseif (Admin::user()->inRoles(['jkr'])) {


            if (Admin::user()->inRoles(['jkr-checker'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('jkr_check', '=', 2);
                    });
                $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            } elseif (Admin::user()->inRoles(['jkr-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('jkr_approve', '=', 2);
                    });
                $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();
            }

        } elseif (Admin::user()->inRoles(['fin'])) {
            //FIN grid

            if (Admin::user()->inRoles(['fin-checker'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('finance_check', '=', 2);
                    });
                $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            } elseif (Admin::user()->inRoles(['fin-approver'])) {
                $grid->model()
                    ->where(function ($query) {
                        $query->where('finance_approve', '=', 2);
                    });
                $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
                    ->dot($this->statusLabelsRolesDot, 'warning')
                    ->sortable();

            }
        } else {
        }
        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        $grid->column('modified_at', __('Modified At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });
        return $grid;
    }

    public function staffOverallGrid()
    {
        $grid = new Grid(new PermitApplication());

        $grid->header(function ($query) {
            return $this->generateGridHeaderHTML();
        });

        //Grid setting
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        //filter settings
        $grid->filter(function ($filter) {
            $filter->setCols(8, 2);
            $filter->column(12, function ($filter) {
                $filter->like('user.name', __('Account name'));
                $filter->like('holder.name', __('Holder name'));
            });
            $filter->column(6, function ($filter) {
                $filter->equal('status', __('Status'))->select($this->statusLabelsRoles);
                $filter->equal('phc_check', __('PHC1'))->select($this->statusLabelsRoles);
                $filter->equal('phc_approve', __('PHC2'))->select($this->statusLabelsRoles);
                $filter->equal('phc_second_approve', __('PHC3'))->select($this->statusLabelsRoles);
            });
            $filter->column(6, function ($filter) {
                $filter->equal('jkr_check', __('JKR1'))->select($this->statusLabelsRoles);
                $filter->equal('jkr_approve', __('JKR2'))->select($this->statusLabelsRoles);
                $filter->equal('finance_check', __('FIN1'))->select($this->statusLabelsRoles);
                $filter->equal('finance_approve', __('FIN2'))->select($this->statusLabelsRoles);
            });

        });

        //Db query
        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
        $grid->column('user.name', __('Account'))->sortable()->width(100);
        $grid->column('holder.name', __('Holder Name'))->sortable()->width(150);

        $grid->column('status', __('STATUS'))->replace($this->statusLabelsPermit);
        $grid->column('phc_check', __('PHC1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_approve', __('PHC2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('phc_second_approve', __('PHC3'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_check', __('JKR1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('jkr_approve', __('JKR2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('transaction_status', __('PAYMENT'))->using($this->statusLabelsPayment, 'Unknown')
            ->dot($this->statusDotsPayment, 'warning')
            ->sortable();
        $grid->column('finance_check', __('FIN1'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('finance_approve', __('FIN2'))->using($this->statusLabelsRoles, 'Unknown')
            ->dot($this->statusLabelsRolesDot, 'warning')
            ->sortable();
        $grid->column('created_at', __('Created At'))
            ->display(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */


    public function show($id, Content $content)
    {
        return $content
            ->title('Details')
            ->description('Description...')
            ->body($this->detail($id));
    }

    protected function detail($id)
    {
        $show = new Show(PermitApplication::findOrFail($id));

        //show settings
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableDelete();
            });
        $show->divider();
        $show->field('end_date');
        $show->field('id', __('Application ID'));


        //test
        // $show->vehicle('Vehicle', function ($vehicle) {
        //     $vehicle->setResource('/admin/vehicles');
        //     $vehicle->id();
        //     $vehicle->model();
        // });

        $show->field('user.name', __('Account Name'));
        $show->field('holder.name', __('Permit Holder Name'));
        $show->field('purpose', __('Purpose'));
        $show->field('applicantcat.name', __('Applicant Category'));
        $show->divider();
        if (Admin::user()->inRoles(['sysadmin'])) {
            //hide id from normal roles
            $show->field('vehicle_id', __('Vehicle ID'));
        }

        // $show->field('vehicle.type', __('Vehicle Type'))->as(function ($vehicleTypeId) {
        //     $vehicleType = VehicleType::findOrFail($vehicleTypeId);
        //     return $vehicleType->name;
        // });


        $show->field('vehicle.reg_no', __('Vehicle Registration No.'));
        $show->field('vehicle.model', __('Vehicle model'));
        $show->divider();

        $adminDiskUrl = config('filesystems.disks.files.url');


        $show->field('surat_permohonan', __('Surat Permohonan'))->file($server = '', $download = true);

        $show->field('surat_permohonan', __('Surat Permohonan'))->file($server = '', $download = true)->as(function ($surat_permohonan) {

            if ($surat_permohonan == null) {
                return '<p>' . __('No file available') . '</p>';
            } else {
                return $surat_permohonan;
            }

        })->unescape();

        $show->field('surat_indemnity', __('Surat Indemnity'))->file($server = '', $download = true);
        $show->field('surat_sokongan', __('Surat Sokongan'))->file($server = '', $download = true);

        $show->field('salinan_kad_pengenalan', __('Salinan Kad Pengenalan'))->file($server = '', $download = true);
        $show->field('salinan_lesen_memandu', __('Salinan Lesen Memandu'))->file($server = '', $download = true);
        $show->field('salinan_geran_kenderaan', __('Salinan Geran Kenderaan'))->file($server = '', $download = true);

        $show->field('salinan_insurans_kenderaan', __('Salinan Insurans Kenderaan'))->file($server = '', $download = true);
        $show->field('salinan_road_tax', __('Salinan Road Tax'))->file($server = '', $download = true);
        $show->field('gambar_kenderaan', __('Gambar Kenderaan'))->file($server = '', $download = true);

        $show->field('gambar_kenderaan', __('Gambar Kenderaan'))->file($server = '', $download = true);

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */

    public function edit($id, Content $content)
    {
        $model = PermitApplication::findOrFail($id);

        //Calculate dependency status of approval chain
        $dependencyStatus = $this->calculateDependencyStatus($model);

        //Multistep
        $steps = [
            'info' => Steps\Info::class,
            'profile' => Steps\Info::class,
        ];

        return $content
            ->title('Edit')
            ->description('Description...')
            ->body($this->form($model, $dependencyStatus)->edit($id));
    }

    protected function form($model = null, $dependencyStatus = null)
    {
        $form = new Form(new PermitApplication());
        $form->setWidth(7, 3);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });
        $form->disableReset();

        //Form setting
        if (Admin::user()->inRoles(['phc', 'jkr', 'fin'])) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableDelete();
            });
            $form->footer(function ($footer) {
                $footer->disableCreatingCheck();
                $footer->disableViewCheck();
                $footer->disableEditingCheck();
            });
        }

        //Form footer setting
        $form->disableEditingCheck(true);
        $form->disableCreatingCheck(true);
        $form->disableViewCheck(true);

        $phcCheckStatus = true;
        $phcApproveStatus = true;
        $phcSecondApproveStatus = true;
        $jkrCheckStatus = true;
        $jkrApproveStatus = true;
        $finCheckStatus = true;
        $finApproveStatus = true;

        $userRoles = Admin::user()->roles->pluck('name')->toArray();
        //determine which fields gets enabled
        if (in_array('sysadmin', $userRoles)) {
            $phcCheckStatus = false;
            $phcApproveStatus = false;
            $phcSecondApproveStatus = false;
            $jkrCheckStatus = false;
            $jkrApproveStatus = false;
            $finCheckStatus = false;
            $finApproveStatus = false;
        }
        if (in_array('phc-checker', $userRoles)) {
            $phcCheckStatus = false;
        }
        if (in_array('phc-approver', $userRoles)) {
            $phcCheckStatus = false;
            $phcApproveStatus = false;
        }
        if (in_array('phc-second-approver', $userRoles)) {
            $phcCheckStatus = false;
            $phcApproveStatus = false;
            $phcSecondApproveStatus = false;
        }
        if (in_array('jkr-checker', $userRoles)) {
            $jkrCheckStatus = false;
        }
        if (in_array('jkr-approver', $userRoles)) {
            $jkrCheckStatus = false;
            $jkrApproveStatus = false;
        }
        if (in_array('fin-checker', $userRoles)) {
            $finCheckStatus = false;
        }
        if (in_array('fin-approver', $userRoles)) {
            $finCheckStatus = false;
            $finApproveStatus = false;
        }

        $generalInfoStatus = true;
        $userRoles = Admin::user()->roles->pluck('name')->toArray();
        if (in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {
            $generalInfoStatus = false;
        }

        //Tab 1 - Application  -----------------------------------------------------------------------------------------
        $statusLabelsPermit = $this->statusLabelsPermit;
        $form->tab(__('Application'), function ($form) use ($generalInfoStatus, $model, $statusLabelsPermit) {

            if (Admin::user()->inRoles(['sysadmin'])) {
                $form->html(function ($form) use ($statusLabelsPermit) {
                    $status = $form->model()->status;
                    return $statusLabelsPermit[$status] ?? 'Unknown Status';
                }, __('Status'));
                
                $form->select('application_type', __('Application Type') . ' <span style="color:red;">*</span>')
                    ->default(0)
                    ->options([0 => __('Basic Application'), 1 => __('Express Application')])
                    ->when(0, function (Form $form) use ($model, $generalInfoStatus) {
                        $this->renderBasicForm($form, $model, $generalInfoStatus);
                    })
                    ->when(1, function (Form $form) {
                        $this->renderExpressForm($form);
                    })
                    ->disabled($form->isEditing());
            } else {
                $this->renderBasicForm($form, $model, $generalInfoStatus);
            }
        });


        //Tab 2 - Approval (Only for sysadmin & administrator)------------------------------------------
        if (Admin::user()->inRoles(['sysadmin', 'administrator'])) {
            $statusLabelsDropdown = $this->statusLabelsDropdown;
            $form->tab(__('Approval'), function ($form) use ($statusLabelsDropdown) {

                $form->divider(__('Application Status'));
                $form->select('status')->options($statusLabelsDropdown);

                $form->divider('PHC');
                $form->radio('phc_check', __('PHC Check'))
                    ->options([0 => __('Reject'), 2 => __('Approve')]);
                $form->radio('phc_approve', __('PHC Approve'))
                    ->options([0 => __('Reject'), 2 => __('Approve')]);
                $form->radio('phc_second_approve', __('PHC Second Approve'))
                    ->options([0 => __('Reject'), 2 => __('Approve')]);

                $form->divider('JKR');
                $form->radio('jkr_check', __('JKR Check'))
                    ->options([0 => __('Reject'), 2 => __('Approve')]);
                $form->radio('jkr_approve', __('JKR Approve'))
                    ->options([0 => __('Reject'), 2 => __('Approve')]);

                $form->divider('Transaction');
                $form->radio('transaction_status', __('Transaction Status'))
                    ->options([0 => __('Unpaid'), 1 => __('Paid')])->default(0);

                $form->divider('FIN');
                $form->radio('finance_check', __('FIN Check'))
                    ->options([0 => __('Reject'), 2 => __('Approve')]);
                $form->radio('finance_approve', __('FIN Approve'))
                    ->options([0 => __('Reject'), 2 => __('Approve')]);
            });
        }

        $disabledStatus = ($model ? $model->status == 4 : null) || Admin::user()->inRoles(['sysadmin']);
        //Tab 3 - Card (Only for sysadmin & fin) ----------------------------------------------------------------------------------------
        if (Admin::user()->inRoles(['sysadmin', 'fin'])) {
            $form->tab(__('Card'), function ($form) use ($model, $disabledStatus) {
                $form->html(__('Card numbers can only be assigned after the application has been fully approved.'));
                $form->radio('card_status', __('Card Status'))
                    ->default(0)
                    ->disabled(!$disabledStatus)
                    ->when(1, function ($form) use ($disabledStatus) {
                        $form->text('card_no', __('Card No.'))
                            ->disabled(!$disabledStatus);
                    })
                    ->options($this->statusRadioButtons);

                $form->date('end_date', __('Expiry Date'));
            });

        }

        //hidden column for callback save
        $form->hidden('phc_check');
        $form->hidden('phc_approve');
        $form->hidden('phc_second_approve');
        $form->hidden('jkr_check');
        $form->hidden('jkr_approve');
        $form->hidden('finance_check');
        $form->hidden('finance_approve');

        //-------------------
        // Submitted
        //-------------------

        $form->submitted(function (Form $form) {

            $applicationType = request()->input('application_type');

            // Define validation rules based on the applicationType value
            $rules = [
                'other_attachment_comment' => $applicationType == 1 ? 'required|string|max:255' : 'nullable|string|max:255',
                'other_attachment' => $applicationType == 1 ? 'required|array' : 'nullable|array',
                'other_attachment.*' => 'mimes:jpg,png,docx|max:2048',
            ];

            // Define custom messages for the validation rules
            $messages = [
                'other_attachment_comment.required' => 'The comment/description is required when Express application is selected.',
                'other_attachment_comment.string' => 'The comment must be a string.',
                'other_attachment_comment.max' => 'The comment may not be greater than 255 characters.',
                'other_attachment.required' => 'The attachment is required when Express application is selected.',
                'other_attachment.mimes' => 'Each attachment must be a file of type: jpg, png, or docx.',
                'other_attachment.*.mimes' => 'Each attachment must be a file of type: jpg, png, or docx.',
                'other_attachment.*.max' => 'Each attachment may not be greater than 2048 kilobytes.',
            ];

            // Validate the input
            $validator = Validator::make(request()->all(), $rules, $messages);

            // Handle validation failures
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }

        });

        //-------------------
        // Saving
        //-------------------

        $form->saving(function (Form $form) {

            //Set all form status as approve by default
            //Check if any file status isDirty
            $this->updateFormStatusAsApprove($form);

            // If application type is express
            if ($form->application_type == 1) {
                $form->status = 4;
            }

            // When jkr_approver approves 
            if(Admin::user()->inRoles(['jkr'])){
                if ($form->jkr_approve == 2) {
                    $form->status = 2;
                }
            }

            // When jkr_approver approves 
            if(Admin::user()->inRoles(['fin'])){
                if ($form->finance_approve == 2) {
                    $form->status = 4;
                }
            }
        
        });


        return $form;
    }


    

    protected function updateFormStatusAsApprove(Form $form)
    {
        $fileStatusFields = $this->fileStatusFields;
        // Initialize a flag to track if any file is rejected
        $anyRejected = false;

        // Check if any file is rejected
        foreach ($fileStatusFields as $statusField) {
            if ($form->{$statusField} == 1) {
                $anyRejected = true;
                // Set application status as 1 if any file is rejected
                if (Admin::user()->inRoles(['phc-checker'])) {
                    $form->phc_check = 1;
                } elseif (Admin::user()->inRoles(['phc-approver'])) {
                    $form->phc_approve = 1;
                } elseif (Admin::user()->inRoles(['phc-second-approver'])) {
                    $form->phc_second_approve = 1;
                } elseif (Admin::user()->inRoles(['jkr-checker'])) {
                    $form->jkr_check = 1;
                } elseif (Admin::user()->inRoles(['jkr-approver'])) {
                    $form->jkr_approve = 1;
                } elseif (Admin::user()->inRoles(['fin-checker'])) {
                    $form->finance_check = 1;
                } elseif (Admin::user()->inRoles(['fin-approver'])) {
                    $form->finance_approve = 1;
                }
                break;
            }
        }

        // If no file is rejected, set the corresponding roles to 2
        if (!$anyRejected) {
            if (Admin::user()->inRoles(['phc-checker'])) {
                $form->phc_check = 2;
            }
            if (Admin::user()->inRoles(['phc-approver'])) {
                $form->phc_approve = 2;
            }
            if (Admin::user()->inRoles(['phc-second-approver'])) {
                $form->phc_second_approve = 2;
            }
            if (Admin::user()->inRoles(['jkr-checker'])) {
                $form->jkr_check = 2;
            }
            if (Admin::user()->inRoles(['jkr-approver'])) {
                $form->jkr_approve = 2;
            }
            if (Admin::user()->inRoles(['fin-checker'])) {
                $form->finance_check = 2;
            }
            if (Admin::user()->inRoles(['fin-approver'])) {
                $form->finance_approve = 2;
            }
        }
    }

    private function calculateDependencyStatus($model)
    {
        // Initialize the array with default values of 0
        $dependencyStatus = array_fill_keys(array_keys($this->dependencies), 0);

        // Track the latest fulfilled element
        $latestFulfilled = null;

        // Handle each element
        foreach ($this->dependencies as $column => $deps) {
            // Check if all dependencies are fulfilled
            $fulfilled = true;
            foreach ($deps as $dep) {
                if ($model->{$dep} !== 2) {
                    $fulfilled = false;
                    break;
                }
            }

            if ($fulfilled) {
                $latestFulfilled = $column;
            }
        }

        // Ensure that only the latest fulfilled element is set to 1
        if ($latestFulfilled !== null) {
            $dependencyStatus[$latestFulfilled] = 1;
        }

        // Check if all values in $dependencyStatus are 0
        if (array_sum($dependencyStatus) === 0) {
            $dependencyStatus['phc_check'] = 1; // Set phc_check to 1
        }
        return $dependencyStatus;
    }

    public function generateGridHeaderHTML()
    {
        $printPHC = Admin::user()->inRoles(['phc']);
        $printJKR = Admin::user()->inRoles(['jkr']);
        $printFIN = Admin::user()->inRoles(['fin']);

        // Define column titles
        $columnTitlePHC = 'Penang Hill Traffic Section';
        $columnTitleJKR = 'Jabatan Kerja Raya Malaysia (JKR)';
        $columnTitleFIN = 'Penang Hill Accounting Department';

        // Start building the HTML string
        $html = <<<HTML
    <div class="header-content">
        <h3 style="font-weight:bold;">Roles</h3>
        <table style="width:100%; text-align:left; border-collapse:collapse;" class="text-left">
            <thead>
                <tr>
    HTML;

        // Conditionally add PHC column header and rows
        if ($printPHC) {
            $html .= <<<HTML
        <th>{$columnTitlePHC}</th>
        HTML;

            $html .= <<<HTML
        </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <ul>
                            <li><strong>PHC1</strong> = Penang Hill Traffic Officer</li>
                            <li><strong>PHC2</strong> = Penang Hill Head Officer</li>
                            <li><strong>PHC3</strong> = General Manager</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        HTML;
        }

        // Conditionally add JKR column header and rows
        if ($printJKR) {
            $html .= <<<HTML
        <th>{$columnTitleJKR}</th>
        HTML;

            $html .= <<<HTML
        </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <ul>
                            <li><strong>JKR1</strong> = JKR Officer</li>
                            <li><strong>JKR2</strong> = JKR Director</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        HTML;
        }

        // Conditionally add FIN column header and rows
        if ($printFIN) {
            $html .= <<<HTML
        <th>{$columnTitleFIN}</th>
        HTML;

            $html .= <<<HTML
        </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <ul>
                            <li><strong>FIN1</strong> = Assistant Accountant</li>
                            <li><strong>FIN2</strong> = Senior Accountant</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        HTML;
        }

        // Close the table and header content
        $html .= <<<HTML
        </table>
    </div>
    HTML;

        // Return the complete HTML string based on user roles
        return $html;
    }
    public function generateGridHeaderSysadminHTML()
    {
        $columnTitle1 = 'Penang Hill Traffic Section';
        $columnTitle2 = 'Jabatan Kerja Raya Malaysia (JKR)';
        $columnTitle3 = 'Penang Hill Accounting Department';

        return <<<HTML
    <div class="header-content">
        <h3 style="font-weight:bold;">Roles</h3>
        <table style="width:100%; text-align:left; border-collapse:collapse;">
            <thead>
                <tr>
                    <th>{$columnTitle1}</th>
                    <th>{$columnTitle2}</th>
                    <th>{$columnTitle3}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <ul>
                            <li><strong>PHC1</strong> = Penang Hill Traffic Officer</li>
                            <li><strong>PHC2</strong> = Penang Hill Head Officer</li>
                            <li><strong>PHC3</strong> = General Manager</li>
                        </ul>
                    </td>
                    <td style="vertical-align: top;">
                        <ul>
                            <li><strong>JKR1</strong> = JKR Officer</li>
                            <li><strong>JKR2</strong> = JKR Director</li>
                        </ul>
                    </td>
                    <td style="vertical-align: top;">
                        <ul>
                            <li><strong>FIN1</strong> = Assistant Accountant</li>
                            <li><strong>FIN2</strong> = Senior Accountant</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    HTML;
    }
    private function renderBasicForm($form, $model, $generalInfoStatus)
    {
        //this form
        $form->divider(__('Applicant'));
        $form->text('id', __('Application ID'))->disabled(true);
        $form->text('serial_no', __('Serial No.'))->disabled(true);

        $form->select('customer_id', __("Customer name"))
            ->options(function ($id) {
                $user = User::find($id);
                if ($user) {
                    return [$user->id => $user->name];
                }
            })->ajax('/admin/api/users')->disabled($generalInfoStatus);

        $form->select('holder_id', __("Permit Holder name"))
            ->options(function ($id2) {
                $holder = PermitHolder::find($id2);
                if ($holder) {
                    $outsideHolderName = $holder->name;
                    return [$holder->id => $holder->name];
                }
            })->ajax('/admin/api/holders')->disabled($generalInfoStatus);

        $form->textarea('purpose', __('Purpose'))->updateRules([
            'nullable',
            'string',
        ], [
            'description.string' => 'The Description must be a valid string.',
            'description.max' => 'The Description may not be greater than :max characters.',
        ])->disabled($generalInfoStatus);

        $form->select('applicant_category_id', __("Applicant category"))
            ->options(ApplicantCategory::all()->pluck('name', 'id'))
            ->updateRules('required')
            ->when('notIn', [1], function (Form $form) {
                $this->renderOptionalCompanyFields($form);
            })
            ->default(1)
            ->disabled($generalInfoStatus);


        //--------------------------------------------------
        $disableStatus = true;
        $removeableStatus = false;
        $readonlyStatus = true;
        $userRoles = Admin::user()->roles->pluck('name')->toArray();
        if (in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {
            $disableStatus = false;
            $removeableStatus = true;
            $readonlyStatus = false;
        }

        $form->divider(__('Vehicle'));
        $form->text('vehicle_id', __('Vehicle ID'))->disabled();
        $form->select('vehicle.type', __('Vehicle Type'))->options(VehicleType::all()->pluck('name', 'id'))->disabled($readonlyStatus);
        $form->text('vehicle.reg_no', __('Vehicle Registration No.'))->updateRules([
            'nullable',
            'alpha_num',
            'max:20',
        ], [
            'alpha_num' => 'The vehicle registration number may only contain letters and numbers.',
            'max' => 'The vehicle registration number must not exceed 20 characters in length.'
        ])->disabled($readonlyStatus)->attribute('style', 'text-transform: uppercase;')->placeholder(' ');


        $form->text('vehicle.model', __('Vehicle model'))->updateRules([
            'nullable',
            'max:100',
        ], [
            'regex' => 'The vehicle model may only contain letters, numbers, and spaces.',
            'max' => 'The vehicle model must not exceed 20 characters in length.'
        ])->disabled($readonlyStatus);


        //----------------------------------------------------------------------------------------------------------------------
        $form->divider(__('Required Document'));

        if ($form->isCreating()) {
            $holderId = 999;
        } else {
            $holderId = $form->model->holder_id;
        }


        //----------------------------------------------------------------------------------------------------------------------

        $statusSP = $model ? $model->surat_permohonan_status : null;
        $label = 'Surat Permohonan';
        $fieldStatusSP = $statusSP === 0 || $statusSP === 1;
        if ($fieldStatusSP) {
            $label .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusSP] . ']</i>';
        }
        $form->multipleFile('surat_permohonan', $label)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus)
            ->move(function ($form) use ($holderId) {
                $path = 'files/' . $holderId . '/surat_permohonan/';
                return $path;
            }, 'file')
        ;

        $form->radio('surat_permohonan_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('surat_permohonan_comment', __('Comments'))
                    ->disabled(false);
            });

        //----------------------------------------------------------------------------------------------------------------------

        $form->divider();

        $statusSI = $model ? $model->surat_indemnity_status : null;
        $labelSI = 'Surat Indemnity Bond';
        $fieldStatusSI = $statusSI === 0 || $statusSI === 1;
        if ($fieldStatusSI) {
            $labelSI .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusSI] . ']</i>';
        }
        $form->multipleFile('surat_indemnity', $labelSI)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus)
            ->move(function ($form) {
                $holderId = $form->model->holder_id;
                $path = 'files/' . $holderId . '/surat_indemnity/';
                return $path;
            }, 'file');

        $form->radio('surat_indemnity_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('surat_indemnity_comment', __('Comments'))
                    ->disabled(false);
            });

        //----------------------------------------------------------------------------------------------------------------------
        $form->divider();

        $statusSS = $model ? $model->surat_sokongan_status : null;
        $labelSS = 'Surat Sokongan';
        $fieldStatusSS = $statusSS === 0 || $statusSS === 1;
        if ($fieldStatusSS) {
            $labelSS .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusSS] . ']</i>';
        }
        $form->multipleFile('surat_sokongan', $labelSS)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus)
            ->move(function ($form) {
                $holderId = $form->model->holder_id;
                $path = 'files/' . $holderId . '/surat_sokongan/';
                return $path;
            }, 'file');

        $form->radio('surat_sokongan_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('surat_sokongan_comment', __('Comments'))
                    ->disabled(false);
            });

        //----------------------------------------------------------------------------------------------------------------------
        $form->divider();

        $statusKP = $model ? $model->salinan_kad_pengenalan_status : null;
        $labelKP = 'Salinan Kad Pengenalan';
        $fieldStatusKP = $statusKP === 0 || $statusKP === 1;
        if ($fieldStatusKP) {
            $labelKP .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusKP] . ']</i>';
        }
        $form->multipleFile('salinan_kad_pengenalan', $labelKP)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus)
            ->move(function ($form) {
                $holderId = $form->model->holder_id;
                $path = 'files/' . $holderId . '/salinan_kad_pengenalan/';
                return $path;
            }, 'file');
        $form->radio('salinan_kad_pengenalan_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('salinan_kad_pengenalan_comment', __('Comments'))
                    ->disabled(false);
            });


        //----------------------------------------------------------------------------------------------------------------------
        $form->divider();

        $statusLM = $model ? $model->salinan_lesen_memandu_status : null;
        $labelLM = 'Salinan Lesen Memandu';
        $fieldStatusLM = $statusLM === 0 || $statusLM === 1;
        if ($fieldStatusLM) {
            $labelLM .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusLM] . ']</i>';
        }
        $form->multipleFile('salinan_lesen_memandu', $labelLM)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus)
            ->move(function ($form) {
                $holderId = $form->model->holder_id;
                $path = 'files/' . $holderId . '/salinan_lesen_memandu/';
                return $path;
            }, 'file');
        $form->radio('salinan_lesen_memandu_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('salinan_lesen_memandu_comment', __('Comments'))
                    ->disabled(false);
            });

        //----------------------------------------------------------------------------------------------------------------------
        $form->divider();

        $statusGK = $model ? $model->salinan_geran_kenderaan_status : null;
        $labelGK = 'Salinan Geran Kenderaan';
        $fieldStatusGK = $statusGK === 0 || $statusGK === 1;
        if ($fieldStatusGK) {
            $labelGK .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusGK] . ']</i>';
        }
        $form->multipleFile('salinan_geran_kenderaan', $labelGK)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus)
            ->move(function ($form) {
                $holderId = $form->model->holder_id;
                $path = 'files/' . $holderId . '/salinan_geran_kenderaan/';
                return $path;
            }, 'file');

        $form->radio('salinan_geran_kenderaan_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('salinan_geran_kenderaan_comment', __('Comments'))
                    ->disabled(false);
            });


        //----------------------------------------------------------------------------------------------------------------------
        $form->divider();

        $statusIK = $model ? $model->salinan_insurans_kenderaan_status : null;
        $labelIK = 'Salinan Insurans Kenderaan';
        $fieldStatusIK = $statusIK === 0 || $statusIK === 1;
        if ($fieldStatusIK) {
            $labelIK .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusIK] . ']</i>';
        }
        $form->multipleFile('salinan_insurans_kenderaan', $labelIK)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus)
            ->move(function ($form) {
                $holderId = $form->model->holder_id;
                $path = 'files/' . $holderId . '/salinan_insurans_kenderaan/';
                return $path;
            }, 'file');



        $form->radio('salinan_insurans_kenderaan_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('salinan_insurans_kenderaan_comment', __('Comments'))
                    ->disabled(false);
            });


        //----------------------------------------------------------------------------------------------------------------------
        $form->divider();

        $statusRT = $model ? $model->salinan_road_tax_status : null;
        $labelRT = 'Salinan Road Tax';
        $fieldStatusRT = $statusRT === 0 || $statusRT === 1;
        if ($fieldStatusRT) {
            $labelRT .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusRT] . ']</i>';
        }
        $form->multipleFile('salinan_road_tax', $labelRT)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus)
            ->move(function ($form) {
                $holderId = $form->model->holder_id;
                $path = 'files/' . $holderId . '/salinan_road_tax/';
                return $path;
            }, 'file');
        $form->radio('salinan_road_tax_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('salinan_road_tax_comment', __('Comments'))
                    ->disabled(false);
            });

        //----------------------------------------------------------------------------------------------------------------------
        $form->divider();

        $statusGK = $model ? $model->gambar_kenderaan_status : null;
        $labelGK = 'Gambar Kenderaan';
        $fieldStatusGK = $statusGK === 0 || $statusGK === 1;
        if ($fieldStatusGK) {
            $labelGK .= ' <br><i style="color:red;">[' . $this->fileStatus[$statusGK] . ']</i>';
        }
        $form->multipleFile('gambar_kenderaan', $labelGK)
            ->attribute('data-approval-text', __('[Approval Required]'))
            ->uniqueName()
            ->disabled($disableStatus)
            ->removable($removeableStatus)
            ->move(function ($form) {
                $holderId = $form->model->holder_id;
                $path = 'files/' . $holderId . '/gambar_kenderaan/';
                return $path;
            }, 'file');
        $form->radio('gambar_kenderaan_status', __('File Status'))
            ->default(2)
            ->options($this->fileStatus)->when(1, function (Form $form) use ($fieldStatusSP) {
                $form->text('gambar_kenderaan_comment', __('Comments'))
                    ->disabled(false);
            });

        //===================================================================================================================================

        // $form->divider(__('Comment'));
        // $form->radio('feedback_status', __('Feedback Status'))
        //     ->default(1)
        //     ->options([
        //         1 => __('Reject'),
        //         2 => __('Approve'),
        //     ])
        //     ->when(0, function (Form $form) {
        //         $form->textarea('feedback', __('Feedback'))->default('null');
        //     });

        return $form;
    }
    private function renderExpressForm($form)
    {
        $form->multipleFile('other_attachment', __('Other Attachment') . ' <span style="color:red;">*</span>')
            ->uniqueName()
            ->retainable()
            ->removable()
            ->move(function ($form) {
                $path = 'other_attachment/';
                return $path;
            }, 'file');
        $form->html('[' . __('Allowed Format: PDF, JPG, PNG') . ']');

        $form->textarea('other_attachment_comment', __('Comment/Description') . ' <span style="color:red;">*</span>')
            ->placeholder(' ');
    }
    private function renderOptionalCompanyFields($form)
    {
        $form->text('company_name', __('Company Name'));
        $form->text('company_address', __('Company Address'));
        return $form;
    }


}