<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use OpenAdmin\Admin\Controllers\Dashboard;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;
use App\Models\User;
use OpenAdmin\Admin\Facades\Admin;
use App\Models\PermitApplication;
use App\Models\AdminUser;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\ApplicantCategory;
use App\Models\PermitHolder;
use App\Models\VehicleType;
use Illuminate\Http\Client\Request;
use OpenAdmin\Admin\Widgets\Table;
use App\Admin\Controllers\PermitApplicationController;

class DashboardController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dashboard';
    protected $statusLabels = [
        0 => 'Reviewing',
        1 => 'Rejected',
        2 => 'Approved, Pending Payment',
        3 => 'Processing Payment',
        4 => 'Activated', 
        5 => 'Disabled', 
        6 => 'Terminated',
    ];
    protected $statusDots = [
        0 => 'info',
        1 => 'warning',
        2 => 'success',
        3 => 'danger',
    ];

    protected $statusLabelsPayment = [
        0 => 'Pending',
        1 => 'Reviewing',
        2 => 'Approved',
    ];
    protected $statusDotsPayment = [
        0 => 'danger',
        1 => 'danger',
        2 => 'success',
    ];

    protected $radioSelection = [
        0 => 'Reviewing',
        1 => 'Rejected',
        2 => 'Approved',
    ];

    protected $statusLabelsPermit = [
        0 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: blue; padding: 2px 6px; border-radius: 4px;">Reviewing</div>',
        1 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: orange; padding: 2px 6px; border-radius: 4px;">Rejected</div>',
        2 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: green; padding: 2px 6px; border-radius: 4px;">Approved</div>',
        3 => '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px; font-size: 14px; font-weight: bold; text-transform: uppercase; background-color: #f0f0f0; color: red; padding: 2px 6px; border-radius: 4px;">Terminated</div>',
    ];

    public function index(Content $content)
    {
        //homepage for phc: administrator , sysadmin
        if (Admin::user()->inRoles(['sysadmin'])) {
            return $content
                ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
                ->title(__('Dashboard'))
                ->description(__('Description...'))
                // ->row(Dashboard::title())
                ->row(function (Row $row2) {
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::total_unapproved_application_infobox()->with('customClass', 'custom-red'));
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::total_monthly_application_infobox());
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::total_pending_renewal_infobox());
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::total_monthly_transaction_infobox());
                    });
                })
                ->row(function (Row $row2) {

                    $row2->column(6, function (Column $column) {
                        $column->append(Dashboard::latest_application_activity());
                    });
                    $row2->column(6, function (Column $column) {
                        $column->append(Dashboard::latest_renewal_activity());
                    });
                });
        }

        //homepage for phc: all phc roles
        if (Admin::user()->inRoles(['phc'])) {
            return $content
                ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
                ->title(__('Dashboard'))
                ->description(__('Description...'))
                ->row(function (Row $row2) {
                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::pending_checking_infobox());
                    });
                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::pending_approving_infobox());
                    });
                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::pending_second_approving_infobox());
                    });
                })
                ->row(function (Row $row2) {

                    $row2->column(6, function (Column $column) {
                        $column->append(Dashboard::latest_application_activity());
                    });
                    $row2->column(6, function (Column $column) {
                        $column->append(Dashboard::latest_renewal_activity());
                    });
                });
        }

        //homepage for fin and jkr role
        if (Admin::user()->inRoles(['fin', 'jkr'])) {
            return $content
                ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
                ->title(__('Dashboard'))
                ->description(__('Description...'))
                ->row(function (Row $row2) {
                    $row2->column(6, function (Column $column) {
                        $column->append(Dashboard::pending_checking_infobox());
                    });
                    $row2->column(6, function (Column $column) {
                        $column->append(Dashboard::pending_approving_infobox());
                    });
                })
                ->row(function (Row $row2) {
                    $row2->column(6, function (Column $column) {
                        $column->append(Dashboard::latest_application_activity());
                    });
                    $row2->column(6, function (Column $column) {
                        $column->append(Dashboard::latest_renewal_activity());
                    });
                });

        }

        //--------------------------------------------------------------------
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PermitApplication());

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer id'));
        $grid->column('permit_charge_id', __('Permit charge id'));
        $grid->column('holder_id', __('Holder id'));
        $grid->column('company_name', __('Company name'));
        $grid->column('purpose', __('Purpose'));
        $grid->column('applicant_category_id', __('Applicant category id'));
        $grid->column('vehicle_id', __('Vehicle id'));
        $grid->column('surat_permohonan', __('Surat permohonan'));
        $grid->column('surat_indemnity', __('Surat indemnity'));
        $grid->column('salinan_kad_pengenalan', __('Salinan kad pengenalan'));
        $grid->column('salinan_lesen_memandu', __('Salinan lesen memandu'));
        $grid->column('salinan_geran_memandu', __('Salinan geran memandu'));
        $grid->column('salinan_insurans_memandu', __('Salinan insurans memandu'));
        $grid->column('salinan_road_tax', __('Salinan road tax'));
        $grid->column('gambar_kenderaan', __('Gambar kenderaan'));
        $grid->column('surat_sokongan', __('Surat sokongan'));
        $grid->column('phc_check', __('Phc check'));
        $grid->column('phc_check_date', __('Phc check date'));
        $grid->column('phc_check_id', __('Phc check id'));
        $grid->column('phc_approve', __('Phc approve'));
        $grid->column('phc_approve_date', __('Phc approve date'));
        $grid->column('phc_approve_id', __('Phc approve id'));
        $grid->column('phc_second_approve', __('Phc second approve'));
        $grid->column('phc_second_approve_date', __('Phc second approve date'));
        $grid->column('phc_second_approve_id', __('Phc second approve id'));
        $grid->column('jkr_check', __('Jkr check'));
        $grid->column('jkr_check_date', __('Jkr check date'));
        $grid->column('jkr_check_id', __('Jkr check id'));
        $grid->column('jkr_approve', __('Jkr approve'));
        $grid->column('jkr_approve_date', __('Jkr approve date'));
        $grid->column('jkr_approve_id', __('Jkr approve id'));
        $grid->column('finance_check', __('Finance check'));
        $grid->column('finance_check_date', __('Finance check date'));
        $grid->column('finance_check_id', __('Finance check id'));
        $grid->column('finance_approve', __('Finance approve'));
        $grid->column('finance_approve_date', __('Finance approve date'));
        $grid->column('finance_approve_id', __('Finance approve id'));
        $grid->column('transaction_id', __('Transaction id'));
        $grid->column('transaction_status', __('Transaction status'));
        $grid->column('permit_renewal_id', __('Permit renewal id'));
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
        $show = new Show(PermitApplication::findOrFail($id));
        //show setting
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableDelete();
            });
        ;

        $show->divider();
        $show->field('id', __('Application ID'));
        $show->field('user.name', __('Account Name'));
        $show->field('holder.name', __('Holder Name'));
        $show->field('purpose', __('Purpose'));
        $show->field('applicantcat.name', __('Applicant Category'));

        $show->divider();

        $show->field('vehicle_id', __('Vehicle ID'));

        $permitApplication = PermitApplication::findOrFail($id);
        $show->field('vehicle.type', __('Vehicle Type'))->as(function ($vehicleTypeId) {
            $vehicleType = VehicleType::findOrFail($vehicleTypeId);
            return $vehicleType->name;
        });


        $show->field('vehicle.reg_no', __('Vehicle Registration No.'));
        $show->field('vehicle.model', __('Vehicle model'));

        $show->divider();

        $show->field('surat_indemnity', __('Surat Indemnity'))->file($server = '', $download = true);
        $show->field('salinan_kad_pengenalan', __('Salinan Kad Pengenalan'))->file($server = '', $download = true);
        $show->field('salinan_lesen_memandu', __('Salinan Lesen Memandu'))->file($server = '', $download = true);
        $show->field('salinan_geran_memandu', __('Salinan Geran Memandu'))->file($server = '', $download = true);
        $show->field('salinan_insurans_memandu', __('Salinan Insurans Memandu'))->file($server = '', $download = true);
        $show->field('salinan_road_tax', __('Salinan Road Tax'))->file($server = '', $download = true);
        $show->field('surat_sokongan', __('Surat Sokongan'))->file($server = '', $download = true);


        $adminDiskUrl = config('filesystems.disks.admin.url');
        $show->field('gambar_kenderaan', __('Gambar Kenderaan'))->as(function ($gambar_kenderaan) use ($adminDiskUrl) {
            $images = json_decode($gambar_kenderaan);
            $html = '';
            foreach ($images as $imagePath) {
                // Correct the path and concatenate the base URL
                $correctedPath = str_replace("\\", "/", $imagePath);
                $imageUrl = $adminDiskUrl . $correctedPath;
                // Create the HTML for each image
                $html .= "<img src='{$imageUrl}' style='max-width:700px; max-height:700px; margin-right: 5px;' />";
            }
            return $html;
        })->unescape();

        $show->divider();
        $show->field('phc_check', __('PHC Check'))->as(function ($phc_check) {
            switch ($phc_check) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>';
            }
        })->unescape();
        $show->field('phc_check_date', __('Phc Check Date'));
        $show->field('phc_checked_by.name', __('PHC Checked By'));
        $show->field('phc_approve', __('PHC Approve'))->as(function ($value) {
            switch ($value) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>';
            }
        })->unescape();
        $show->field('phc_approve_date', __('PHC Approve Date'));
        $show->field('phc_approved_by.name', __('PHC Approved By'));
        $show->field('phc_second_approve', __('Phc Second Approve'))->as(function ($value) {
            switch ($value) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>';
            }
        })->unescape();
        $show->field('phc_second_approve_date', __('PHC Second Approve Date'));
        $show->field('phc_second_approved_by.name', __('PHC Second Approved By'));

        $show->divider();

        $show->field('jkr_check', __('JKR Check'))->as(function ($value) {
            switch ($value) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>';
            }
        })->unescape();
        $show->field('jkr_check_date', __('JKR Check Date'));
        $show->field('jkr_checked_by.name', __('JKR Checked By'));
        $show->field('jkr_approve', __('JKR Approve'))->as(function ($value) {
            switch ($value) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>';
            }
        })->unescape();
        $show->field('jkr_approve_date', __('JKR Approve Date'));
        $show->field('jkr_approved_by.name', __('JKR Approved By'));

        $show->divider();

        $show->field('finance_check', __('Finance Check'))->as(function ($value) {
            switch ($value) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>';
            }
        })->unescape();
        $show->field('finance_check_date', __('Finance Check Date'));
        $show->field('finance_checked_by.name', __('Finance Checked By'));
        $show->field('finance_approve', __('Finance Approve'))->as(function ($value) {
            switch ($value) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>';
            }
        })->unescape();
        $show->field('finance_approve_date', __('Finance Approve Date'));
        $show->field('finance_approved_by.name', __('Finance Approve By'));

        $show->divider();

        $show->field('transaction_id', __('Transaction ID'));
        $show->field('transaction_status', __('Transaction Status'))->as(function ($value) {
            switch ($value) {
                case 0:
                    return '<span style="color: red; font-size: 30px;">&times;</span>';
                case 1:
                    return '<span style="color: green;">&#10004;</span>';
                default:
                    return '<span style="color: grey;">&#63;</span>';
            }
        })->unescape();
        $show->field('permit_renewal_id', __('Permit Renewal ID'));
        $show->field('created_at', __('Application Created at'));
        $show->field('updated_at', __('Application Updated at'));

        $show->divider();

        return $show;
    }



    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PermitApplication());
        $form->setWidth(7, 3);

        //form setting
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });
        $form->footer(function ($footer) {
            $footer->disableCreatingCheck();
        });

        //-------------------------------------------------------------
        $phcCheckStatus = true;
        $phcApproveStatus = true;
        $phcSecondApproveStatus = true;
        $jkrCheckStatus = true;
        $jkrApproveStatus = true;
        $finCheckStatus = true;
        $finApproveStatus = true;

        $userRoles = Admin::user()->roles->pluck('name')->toArray();
        //determine which fields gets enabled
        if (in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {
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

        if (in_array('phc-checker', $userRoles) || in_array('phc-approver', $userRoles) || in_array('phc-second-approver', $userRoles) || in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {

            $form->divider(__('PHC Section'));
            $form->switch('phc_check', __('PHC Checking'))->options([0 => __('Pending'), 1 => __('Approved')])->disabled($phcCheckStatus);
            $form->datetime('phc_check_date', __('PHC Check Date'))->default(date('Y-m-d H:i:s'))->disabled();
            $form->select('phc_check_id', __('PHC Check By'))->disabled()->options(AdminUser::all()->pluck('name', 'id'));
            $form->switch('phc_approve', __('PHC Approval'))->options([0 => __('Pending'), 1 => __('Approved')])->disabled($phcApproveStatus);
            $form->datetime('phc_approve_date', __('Phc Approve Date'))->default(date('Y-m-d H:i:s'))->disabled();
            $form->select('phc_approve_id', __('PHC Approved By'))->disabled()->options(AdminUser::all()->pluck('name', 'id'));
            $form->switch('phc_second_approve', __('PHC Second Approval'))->disabled($phcSecondApproveStatus);
            $form->datetime('phc_second_approve_date', __('Phc Second Approval Date'))->default(date('Y-m-d H:i:s'))->disabled();
            $form->select('phc_second_approve_id', __('Phc Second Approved By'))->disabled()->options(AdminUser::all()->pluck('name', 'id'));

        }

        if (in_array('jkr-checker', $userRoles) || in_array('jkr-approver', $userRoles) || in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {

            $form->divider(__('JKR Section'));
            $form->switch('jkr_check', __('Jkr check'))->options([0 => __('Pending'), 1 => __('Approved')])->disabled($jkrCheckStatus);
            $form->datetime('jkr_check_date', __('Jkr check date'))->default(date('Y-m-d H:i:s'))->disabled();
            $form->select('jkr_check_id', __('Jkr check id'))->disabled()->options(AdminUser::all()->pluck('name', 'id'));
            $form->switch('jkr_approve', __('Jkr approve'))->options([0 => __('Pending'), 1 => __('Approved')])->disabled($jkrApproveStatus)->disabled($jkrApproveStatus);
            $form->datetime('jkr_approve_date', __('Jkr approve date'))->default(date('Y-m-d H:i:s'))->disabled();
            $form->select('jkr_approve_id', __('Jkr approve id'))->disabled()->options(AdminUser::all()->pluck('name', 'id'));
        }

        if (in_array('fin-checker', $userRoles) || in_array('fin-approver', $userRoles) || in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {

            $form->divider(__('Finance Section'));
            $form->switch('finance_check', __('Finance check'))->options([0 => __('Pending'), 1 => __('Approved')])->disabled($finCheckStatus);
            $form->datetime('finance_check_date', __('Finance check date'))->default(date('Y-m-d H:i:s'))->disabled();
            $form->select('finance_check_id', __('Finance check id'))->disabled()->options(AdminUser::all()->pluck('name', 'id'));

            $form->switch('finance_approve', __('Finance approve'))->options([0 => __('Pending'), 1 => __('Approved')])->disabled($finApproveStatus);
            $form->datetime('finance_approve_date', __('Finance approve date'))->default(date('Y-m-d H:i:s'))->disabled();
            $form->select('finance_approve_id', __('Finance approve id'))->disabled()->options(AdminUser::all()->pluck('name', 'id'));

            $form->divider(__('Others'));
            $form->number('transaction_id', __('Transaction id'))->disabled();
            $form->select('transaction_status', __('Transaction status'))->options([0 => __('Pending'), 1 => __('Approved')])->disabled();
            $form->number('permit_renewal_id', __('Permit renewal id'))->min(0)->disabled();
        }

        //--------------------------------------------------------------------------------

        $generalInfoStatus = true;
        $userRoles = Admin::user()->roles->pluck('name')->toArray();
        if (in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {
            $generalInfoStatus = false;
        }

        $form->divider(__('Applicant'));

        $form->text('id', __('Application ID'))->disabled(true);
        $form->select('customer_id', __("Customer name"))->options(function ($id) {

            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users')->rules('required')->disabled($generalInfoStatus);
        $form->select('holder_id', __("Holder name"))->options(function ($id2) {
            $holder = PermitHolder::find($id2);

            if ($holder) {
                $holderName = $holder->name;
                return [$holder->id => $holder->name];
            }
        })->ajax('/admin/api/holders')->rules('required')->disabled($generalInfoStatus);
        $form->ckeditor('purpose', __('Purpose'))->rules([
            'nullable',
            'string',
            'max:255',
        ], [
            'description.string' => 'The Description must be a valid string.',
            'description.max' => 'The Description may not be greater than :max characters.',
        ])->disabled($generalInfoStatus);

        $form->select('applicant_category_id', __("Applicant category"))->options(ApplicantCategory::all()->pluck('name', 'id'))->rules('required')->disabled($generalInfoStatus);

        //--------------------------------------------------------------------------------

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
        $form->text('vehicle.reg_no', __('Vehicle Registration No.'))->rules([
            'nullable',
            'alpha_num',
            'max:20',
        ], [
            'alpha_num' => 'The vehicle registration number may only contain letters and numbers.',
            'max' => 'The vehicle registration number must not exceed 20 characters in length.'
        ])->disabled($readonlyStatus);
        $form->text('vehicle.model', __('Vehicle model'))->rules([
            'nullable',
            'max:100',
        ], [
            'regex' => 'The vehicle model may only contain letters, numbers, and spaces.',
            'max' => 'The vehicle model must not exceed 20 characters in length.'
        ])->disabled($readonlyStatus);

        $form->divider(__('Required Document'));
        $form->file('surat_indemnity', __('Surat indemnity'))
            ->move('files\surat_indemnity', 'file')
            ->uniqueName()
            ->rules('mimes:pdf')
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus);

        $form->file('salinan_kad_pengenalan', __('Salinan kad pengenalan'))
            ->move('files\salinan_kp', 'file')
            ->uniqueName()->rules('mimes:pdf')
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus);

        $form->file('salinan_lesen_memandu', __('Salinan lesen memandu'))
            ->move('files\salinan_lesen_memandu', 'file')
            ->uniqueName()->rules('mimes:pdf')
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus);

        $form->file('salinan_geran_memandu', __('Salinan geran memandu'))
            ->move('files\salinan_geran_memandu', 'file')
            ->uniqueName()->rules('mimes:pdf')
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus);

        $form->file('salinan_insurans_memandu', __('Salinan insurans memandu'))
            ->move('files\salinan_insurans', 'file')
            ->uniqueName()->rules('mimes:pdf')
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus);

        $form->file('salinan_road_tax', __('Salinan road tax'))
            ->move('files\salinan_roadtax', 'file')
            ->uniqueName()
            ->rules('mimes:pdf')
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus);

        $form->file('surat_sokongan', __('Surat sokongan'))
            ->move('files\surat_sokongan', 'file')
            ->uniqueName()->rules('mimes:pdf')
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus);

        $form->multipleImage('gambar_kenderaan', __('Gambar kenderaan'))
            ->uniqueName()
            ->move('files\gambar_kenderaan', 'file')
            ->disabled($disableStatus)
            ->retainable()
            ->removable($removeableStatus);

        //--------------------------------------------------------------------------------
        return $form;
    }
}
