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
// use OpenAdmin\Admin\Form\Field\Table;
use OpenAdmin\Admin\Widgets\Table;

class HomeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Permit Application';
    public function index(Content $content)
    {
        //homepage for phc: administrator , sysadmin
        if (Admin::user()->inRoles(['sysadmin'])) {
            return $content
                ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
                ->title('Dashboard')
                ->description('Description...for administrator, sysadmin')
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
                ->title('Dashboard')
                ->description('Description...all phc roles')
                ->row(function (Row $row2) {

                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::total_unapproved_application_infobox());
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::total_application_to_be_checked_infobox());
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::total_application_pending_approval_infobox());
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::total_application_pending_second_approval_infobox());
                    });
                })
                ->row(function (Row $row2) {

                    if (Admin::user()->inRoles(['phc-second-approver'])) {
                        $grid = new Grid(new PermitApplication());
                        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
                        $grid->column('holder.name', __('Name'))->sortable();

                        // $grid->column('purpose', __('Purpose'))->width(350)->sortable();





                        // $grid->column('purpose', __('Purpose'))->width(350)->sortable()->modal('latest comment', function ($model) {

                        //     $purpose = $model->purpose()->take(10)->get()->map(function ($comment) {
                        //         return $comment->only(['id', 'content', 'created_at']);
                        //     });
                        
                        //     return new Table(['ID', 'content', 'release time'], $purpose->toArray());


                        
                        //     return $purposeString;





                        // });

                        

                        // $grid->column('purpose', __('Purpose'))->modal('Permit Application Purposes', function ($model) {
                        //     // Fetch the purpose of the permit application
                        //     $purpose = $model->purpose;
                        
                        //     // Create a table containing the purpose data
                        //     $tableData = [
                        //         ['Purpose', $purpose]
                        //     ];
                        
                        //     return new Table(['Attribute', 'Value'], $tableData);
                        // });

                        // $grid->column('title', 'Title')->modal('Permit Details', function ($model) {
                        //     // Prepare the data for the table
                        //     // $tableData = [
                        //     //     ['ID', $model->id],
                        //     //     ['Purpose', $model->purpose],
                        //     // ];




                        //     $headers = ['Id', 'Email'];
                        //     $rows = [
                        //         [1, 'labore21@yahoo.comtttttttttttttttttttttttttttttttt labore21@yahoo.comtttttttttttttttttttttttttttttttt labore21@yahoo.comttttttttttttttttttttttttttttttttlabore21@yahoo.comtttttttttttttttttttttttttttttttt  labore21@yahoo.comtttttttttttttttttttttttttttttttt labore21@yahoo.comtttttttttttttttttttttttttttttttt'],
                        //         [2, 'omnis.in@hotmail.comtttttttttttttttttttttttttttttttt'],
                        //         [3, 'quia65@hotmail.com'],
                        //         [4, 'xet@yahoo.com'],
                        //         [5, 'ipsa.aut@gmail.com'],
                        //     ];

                        //     $table = new Table($headers, $rows);










                        
                        //     // Return a new Table instance with the data
                        //     // return new Table(['Field', 'Value'], $tableData);
                        //     return $table;
                        // });



                        $grid->column('title', 'title')->modal('latest comment', function ($model) {
                            // Dummy data for comments
                            
                        
                            // Map the dummy data to match the structure expected by the Table component
                            // $mappedComments = $comments->map(function ($comment) {
                            //     return $comment->only(['id', 'content', 'created_at']);
                            // });
                        
                            // Return a new Table instance with the dummy data
                            // return new Table(['ID', 'Content', 'Release Time'], $comments->toArray());


                            //can you return a ckeditor here?
                            

                            $form = new Form(new PermitApplication());
                            $form->setWidth(10, 0);
                            return $form->ckeditor()->options(['lang' => 'fr', 'width' => 763,'contentsCss' => '/css/frontend-body-content.css']);


                        });
                        
                        





                       




                        $grid->column('phc_check', __('PHC Check'))->switch()->sortable()->style('text-align: center;');
                        $grid->column('phc_approve', __('PHC Approve'))->switch()->sortable()->style('text-align: center;');
                        $grid->column('phc_second_approve', __('PHC Second Approve'))->switch()->sortable()->style('text-align: center;');
                        $row2->column(12, function (Column $column) use ($grid) {
                            $column->append($grid);
                        });
                        $row2->column(12, function (Column $column) {
                            $column->append(Dashboard::latest_application_activity());
                        });
                        return $grid;

                    } else {
                        $row2->column(12, function (Column $column) {
                            $column->append(Dashboard::latest_application_activity());
                        });
                    }
                });
        }


        //homepage for fin and jkr role
        if (Admin::user()->inRoles(['fin', 'jkr'])) {
            return $content
                ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
                ->title('Dashboard')
                ->description('Description...all fin and jkr roles')
                ->row(function (Row $row2) {

                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::total_unapproved_application_infobox());
                    });
                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::total_application_to_be_checked_infobox());
                    });
                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::total_application_pending_approval_infobox());
                    });
                })
                ->row(function (Row $row2) {

                    if (Admin::user()->inRoles(['jkr'])) {
                        $grid = new Grid(new PermitApplication());
                        $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
                        $grid->column('holder.name', __('Name'));
                        $grid->column('purpose', __('Purpose'))->width(100)->textarea()->sortable();
                        $grid->column('phc_second_approve', __('PHC Second Approve'))->bool()->sortable()->style('text-align:center');
                        $grid->column('phc_approve', __('PHC Approve'))->bool()->sortable()->style('text-align: center;');
                        
                        $row2->column(12, function (Column $column) use ($grid) {
                            $column->append($grid);
                        });
                        $row2->column(12, function (Column $column) {
                            $column->append(Dashboard::latest_application_activity());
                        });
                        return $grid;
                    
                    } else {
                        $row2->column(12, function (Column $column) {
                            $column->append(Dashboard::latest_application_activity());
                        });
                    }
                });

        }

        
        //----------------------------------------------------------------------------------
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
            $form->switch('phc_check', __('PHC Checking'))->options([0 => __('Pending'), 1 => __('Approved')])->readonly($phcCheckStatus);
            $form->datetime('phc_check_date', __('PHC Check Date'))->default(date('Y-m-d H:i:s'))->readonly();
            $form->select('phc_check_id', __('PHC Check By'))->readonly()->options(AdminUser::all()->pluck('name', 'id'));
            $form->switch('phc_approve', __('PHC Approval'))->options([0 => __('Pending'), 1 => __('Approved')])->readonly($phcApproveStatus);
            $form->datetime('phc_approve_date', __('Phc Approve Date'))->default(date('Y-m-d H:i:s'))->readonly();
            $form->select('phc_approve_id', __('PHC Approved By'))->readonly()->options(AdminUser::all()->pluck('name', 'id'));
            $form->switch('phc_second_approve', __('PHC Second Approval'))->options([0 => __('Pending'), 1 => __('Approved')])->readonly($phcSecondApproveStatus);
            $form->datetime('phc_second_approve_date', __('Phc Second Approval Date'))->default(date('Y-m-d H:i:s'))->readonly();
            $form->select('phc_second_approve_id', __('Phc Second Approved By'))->readonly()->options(AdminUser::all()->pluck('name', 'id'));

        }

        if (in_array('jkr-checker', $userRoles) || in_array('jkr-approver', $userRoles) || in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {

            $form->divider(__('JKR Section'));

            $form->switch('jkr_check', __('JKR Check'))->options([0 => __('Pending'), 1 => __('Approved')])->readonly($jkrCheckStatus);
            $form->datetime('jkr_check_date', __('Jkr check date'))->default(date('Y-m-d H:i:s'))->readonly();
            $form->select('jkr_check_id', __('Jkr check id'))->readonly()->options(AdminUser::all()->pluck('name', 'id'));

            $form->switch('jkr_approve', __('Jkr approve'))->options([0 => __('Pending'), 1 => __('Approved')])->readonly($jkrApproveStatus)->disabled($jkrApproveStatus);
            $form->datetime('jkr_approve_date', __('Jkr approve date'))->default(date('Y-m-d H:i:s'))->readonly();
            $form->select('jkr_approve_id', __('Jkr approve id'))->readonly()->options(AdminUser::all()->pluck('name', 'id'));
        }

        if (in_array('fin-checker', $userRoles) || in_array('fin-approver', $userRoles) || in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {

            $form->divider(__('Finance Section'));
            $form->switch('finance_check', __('Finance check'))->options([0 => __('Pending'), 1 => __('Approved')])->readonly($finCheckStatus);
            $form->datetime('finance_check_date', __('Finance check date'))->default(date('Y-m-d H:i:s'))->readonly();
            $form->select('finance_check_id', __('Finance check id'))->readonly()->options(AdminUser::all()->pluck('name', 'id'));

            $form->switch('finance_approve', __('Finance approve'))->options([0 => __('Pending'), 1 => __('Approved')])->readonly($finApproveStatus);
            $form->datetime('finance_approve_date', __('Finance approve date'))->default(date('Y-m-d H:i:s'))->readonly();
            $form->select('finance_approve_id', __('Finance approve id'))->readonly()->options(AdminUser::all()->pluck('name', 'id'));

            $form->divider(__('Others'));
            $form->number('transaction_id', __('Transaction id'))->readonly();
            $form->select('transaction_status', __('Transaction status'))->options([0 => __('Pending'), 1 => __('Approved')])->readonly();
            $form->number('permit_renewal_id', __('Permit renewal id'))->min(0)->readonly();
        }

        //------------------------------------------------------------


        $generalInfoStatus = true;
        $userRoles = Admin::user()->roles->pluck('name')->toArray();
        if (in_array('sysadmin', $userRoles) || in_array('Administrator', $userRoles)) {
            $generalInfoStatus = false;
        }

        $generalInfoStatus = false;

        $form->divider(__('Applicant'));
        $form->text('id', __('Application ID'))->readonly();

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
        $form->text('purpose', __('Purpose'))->rules([
            'nullable',
            'string',
            'max:255',
        ], [
            'description.string' => 'The Description must be a valid string.',
            'description.max' => 'The Description may not be greater than :max characters.',
        ])->readonly($generalInfoStatus);

        $form->select('applicant_category_id', __("Applicant category"))->options(ApplicantCategory::all()->pluck('name', 'id'))->rules('required')->readonly($generalInfoStatus);

        //------------------------------------------------------------

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
        $form->number('vehicle_id', __('Vehicle id'))->min(0)->readonly();
        $form->select('vehicle.type', __('Vehicle type'))->options(VehicleType::all()->pluck('name', 'id'))->readonly($readonlyStatus);
        $form->text('vehicle.reg_no', __('Vehicle Registration No.'))->rules([
            'nullable',
            'alpha_num',
            'max:20',
        ], [
            'alpha_num' => 'The vehicle registration number may only contain letters and numbers.',
            'max' => 'The vehicle registration number must not exceed 20 characters in length.'
        ])->readonly($readonlyStatus);
        $form->text('vehicle.model', __('Vehicle model'))->rules([
            'nullable',
            'max:100',
        ], [
            'regex' => 'The vehicle model may only contain letters, numbers, and spaces.',
            'max' => 'The vehicle model must not exceed 20 characters in length.'
        ])->readonly($readonlyStatus);

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



        return $form;
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

        $show->divider();

        $show->field('id', __('Application ID'));
        $show->field('user.name', __('Account Name'));
        $show->field('holder.name', __('Holder Name'));
        $show->field('purpose', __('Purpose'));
        $show->field('applicantcat.name', __('Applicant Category'));

        $show->divider();

        $show->field('vehicle_id', __('Vehicle ID'));

        $permitApplication = PermitApplication::findOrFail($id);
        // $vehicleId = $permitApplication->vehicle_id;

        // $vehicleType = $permitApplication->vehicle()->vehicleType()->name;

        // $vehicleType = VehicleType::findOrFail($vehicle)

        // $vehicleType = $permitApplication->vehicle()->first();

        // $typeId = $vehicle->type;
        // $typeName = $vehicle->vehicleType->name;



        // $show->field('vehicle.vehicleType', __('Vehicle Type'))
        //     ->display(function ($name) {
        //         return $name;

        //     })->json();

        $show->field('vehicle.type', __('Vehicle Type'))->as(function ($vehicleTypeId) {
            $vehicleType = VehicleType::findOrFail($vehicleTypeId);
            return $vehicleType->name;
        });

        // $show->field('vehicle.vehicle_id', __('Vehicle Type'))->as(function ($vehicleId) {
        //     return "<{$vehicleId}>";
        // });



        // $show->field('vehicle_id', __('Vehicle Type'))->(function ($vehicleId) {
        //     // Retrieve the vehicle using the vehicle ID
        //     $vehicle = Vehicle::find($vehicleId);

        //     // If the vehicle is found, retrieve and return the name of its associated vehicle type
        //     if ($vehicle) {
        //         // return $vehicle->vehicleType->name;
        //         return "here inside";
        //     }

        //     // Return null or an empty string if the vehicle is not found
        //     return "here"; // or return '';
        // });


        // $show->field($vehicleType, __('Vehicle Type'));


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




    //--------------------------------------------------------------------

    public function shortcutDashboard(Content $content)
    {
        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            ->title('Dashboard')
            ->description('Description shortcut...')
            ->row(function (Row $row2) {

                //jkr shortcut widgets
                if (Admin::user()->inRoles(['jkr-checker', 'jkr-approver'])) {
                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::total_unapproved_application_infobox());
                    });
                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::total_application_to_be_checked_infobox());
                    });
                    $row2->column(4, function (Column $column) {
                        $column->append(Dashboard::total_application_pending_approval_infobox());
                    });
                }

                //phc shortcut widegts
                if (Admin::user()->inRoles(['phc-second-approver'])) {
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::phc_total_unapproved_application_infobox());
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::phc_total_application_to_be_checked_infobox());
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::phc_total_application_pending_approval_infobox());
                    });
                    $row2->column(3, function (Column $column) {
                        $column->append(Dashboard::phc_total_application_pending_second_approval_infobox());
                    });
                }

               
    
            })
            ->row(function (Row $row) {

                //jkr shortcut permit table
                if (Admin::user()->inRoles(['jkr-checker', 'jkr-approver'])) {
                    $grid = new Grid(new PermitApplication());
                    $grid->column('id', __('ID'))->sortable()->style('text-align: center;');
                    $grid->column('holder.name', __('Name'));
                    $grid->column('purpose', __('Purpose'))->width(650)->textarea()->sortable();
                    $grid->column('phc_second_approve', __('PHC Second Approve'))->switch()->sortable()->style('display: flex; justify-content: center; align-items: center;');
                    $row->column(12, function (Column $column) use ($grid) {
                        $column->append($grid);
                    });
                    return $grid;
                }


            });
    }

    public function settings(Content $content)
    {
        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            ->title('Settings')
            ->description('Description...')
            // ->row(Dashboard::title())
            ->row(function (Row $row2) {

                $row2->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });
                $row2->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });
                $row2->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });

            });
    }
    public function users(Request $request)
    {
        $query = $request::input("query");
        return User::where('name', 'like', "%$query%")->limit(10)->get(['id', 'name as text']);
    }


}
