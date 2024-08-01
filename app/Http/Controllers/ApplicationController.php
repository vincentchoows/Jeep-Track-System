<?php

namespace App\Http\Controllers;

use App\Models\ApplicantCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PermitApplication;
use App\Models\PermitHolder;
use App\Models\VehicleType;
use App\Models\Vehicle;
use App\Models\PermitApplicationLog;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use App\Notifications\PermitApplicationSubmitted;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Query database based on sorting criteria
        $tables = PermitApplication::where('customer_id', Auth::user()->id)
            ->whereNotIn('status', [4, 5, 6])
            ->orderBy($sortBy, $sortDirection)
            ->with('holder', 'user', 'applicantcat')
            ->paginate(10);
        // $tables['0']['end_date'] = Carbon::parse($tables['0']['end_date'])->format('d-m-Y');

        // Return the view with data
        return view('components.pages.application', [
            'tables' => $tables,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function indexMyPermit(Request $request)
    {
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        $tables = PermitApplication::where('customer_id', Auth::user()->id)
            ->where('transaction_status', '1') //Paid
            ->where(function ($query) {
                $query->where('status', '4') // Activated
                    ->orWhere('status', '5') // Disabled
                    ->orWhere('status', '6'); // Terminate
            })
            ->orderBy($sortBy, $sortDirection)
            ->with('holder', 'user', 'applicantcat')
            ->paginate(10);

        // Return the view with data
        return view('components.pages.mypermit', [
            'tables' => $tables,
            'sortDirection' => $sortDirection,
        ]);
    }


    public function show(Request $request, $id)
    {
        $userPermit = PermitApplication::findOrFail($id);
        $applicantcatOptions = ApplicantCategory::all();
        $permitHolderOptions = PermitHolder::all();
        $applicantcatName = "";

        $userPermit = PermitApplication::where('customer_id', Auth::user()->id)
            ->findOrFail($id);
        $fileTypes = [
            'surat_permohonan',
            'surat_indemnity',
            'surat_sokongan',
            'salinan_kad_pengenalan',
            'salinan_lesen_memandu',
            'salinan_geran_kenderaan',
            'salinan_insurans_kenderaan',
            'salinan_road_tax',
            'gambar_kenderaan',
        ];

        $fileArray = [];
        foreach ($fileTypes as $field) {
            // Check if the field exists in $userPermit 
            if (isset($userPermit->$field)) {
                $fileArray[$field] = json_decode($userPermit->$field);
            } else {
                $fileArray[$field] = [];
            }
        }

        //Get details of applicant category
        foreach ($applicantcatOptions as $option) {
            if ($userPermit->applicant_category_id == $option->id) {
                $applicantcatName = $option->name;
                break;
            }
        }

        //Get all vehicle type options
        if (!isset($vehicleTypeOptions)) {
            $vehicleTypeOptions = VehicleType::all();
        }

        //Retrieve Permit Log
        $tables = PermitApplicationLog::where('view_access', 0)
            ->where('permit_application_id', $userPermit->id)
            ->orderBy('created_at', 'asc')
            ->get();


        // dd(config('filesystems.disks.admin.url'));

        return view('components.pages.application_show', compact('userPermit', 'applicantcatOptions', 'applicantcatName', 'fileArray', 'vehicleTypeOptions', 'tables'));
    }


    public function showMyPermit(Request $request, $id)
    {
        $userPermit = PermitApplication::findOrFail($id);
        $applicantcatOptions = ApplicantCategory::all();
        $permitHolderOptions = PermitHolder::all();
        $applicantcatName = "";

        $userPermit = PermitApplication::where('customer_id', Auth::user()->id)
            ->whereNot('status', 0)
            ->whereNot('status', 1)
            ->findOrFail($id);

        $fileTypes = [
            'surat_permohonan',
            'surat_indemnity',
            'surat_sokongan',
            'salinan_kad_pengenalan',
            'salinan_lesen_memandu',
            'salinan_geran_kenderaan',
            'salinan_insurans_kenderaan',
            'salinan_road_tax',
            'gambar_kenderaan'
        ];

        $fileArray = [];
        foreach ($fileTypes as $field) {
            // Check if the field exists in $userPermit 
            if (isset($userPermit->$field)) {
                $fileArray[$field] = json_decode($userPermit->$field);
            } else {
                $fileArray[$field] = [];
            }
        }

        //Get details of applicant category
        foreach ($applicantcatOptions as $option) {
            if ($userPermit->applicant_category_id == $option->id) {
                $applicantcatName = $option->name;
                break;
            }
        }

        // Check the status of the permit
        $status = $userPermit->status;
        switch ($status) {
            case 0:
                $statusString = __('Reviewing');
                break;
            case 1:
                $statusString = __('Rejected');
                break;
            case 2:
                $statusString = __('Approved');
                break;
            case 3:
                $statusString = __('Restricted');
                break;
            default:
                $statusString = __('Unknown');
                break;
        }

        //Get all vehicle type options
        if (!isset($vehicleTypeOptions)) {
            $vehicleTypeOptions = VehicleType::all();
        }

        //Retrieve Permit Log
        $tables = PermitApplicationLog::where('view_access', 0)
            ->where('permit_application_id', $userPermit->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('components.pages.application_show', compact('userPermit', 'applicantcatOptions', 'applicantcatName', 'fileArray', 'statusString', 'vehicleTypeOptions', 'tables'));
    }

    //Edit: Only When Rejected
    public function edit(Request $request, $id)
    {
        $editStatus = true;
        $userPermit = PermitApplication::findOrFail($id);
        $applicantcatOptions = ApplicantCategory::all();

        // If the request method is GET, render the form
        $checkPermit = PermitApplication::findOrFail($id);
        if ($request->isMethod('GET')) {
            if ($checkPermit->status !== 1) {
                return redirect()->route('application')->with('error', __('Application Is Under Review'));
            } else {
                $fileTypes = [
                    'surat_permohonan',
                    'surat_indemnity',
                    'surat_sokongan',
                    'salinan_kad_pengenalan',
                    'salinan_lesen_memandu',
                    'salinan_geran_kenderaan',
                    'salinan_insurans_kenderaan',
                    'salinan_road_tax',
                    'gambar_kenderaan'
                ];

                $fileArray = [];
                foreach ($fileTypes as $field) {
                    // Check if the field exists in $userPermit 
                    if (isset($userPermit->$field)) {
                        $fileArray[$field] = json_decode($userPermit->$field);
                    } else {
                        $fileArray[$field] = [];
                    }
                }

                //Retrieve vehicle type options
                if (!isset($vehicleTypeOptions)) {
                    $vehicleTypeOptions = VehicleType::all();
                }

                //Retrieve Permit Log
                $tables = PermitApplicationLog::where('view_access', 0)
                    ->where('permit_application_id', $userPermit->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                return view('components.pages.application_show', compact('userPermit', 'editStatus', 'applicantcatOptions', 'fileArray', 'vehicleTypeOptions', 'tables'));
            }
        }


        // Validate input fields
        $validatedData = $request->validate([
            'holder_id' => 'numeric',
            // 'holder_name' => 'sometimes|string',
            'holder.identification_no' => 'string|max:255',
            'holder.contact_no' => 'string|max:20',
            'holder.address' => 'string',
            // // 'applicant_category_id' => 'required|numeric',
            // // 'company_name' => 'sometimes|string',
            // // 'company_address' => 'sometimes|string',
            'purpose' => 'nullable|string',

            'vehicle.type' => 'nullable|string',
            'vehicle.reg_no' => 'nullable|string',
            'vehicle.model' => 'nullable|string',

            'surat_permohonan' => ['', 'array'],
            'surat_permohonan.*' => ['mimes:pdf,jpg,png', 'max:5120'],

            'surat_indemnity' => ['', 'array'],
            'surat_indemnity.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'surat_sokongan' => ['', 'array'],
            'surat_sokongan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_kad_pengenalan' => ['', 'array'],
            'salinan_kad_pengenalan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_lesen_memandu' => ['', 'array'],
            'salinan_lesen_memandu.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_geran_kenderaan' => ['', 'array'],
            'salinan_geran_kenderaan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_insurans_kenderaan' => ['', 'array'],
            'salinan_insurans_kenderaan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_road_tax' => ['', 'array'],
            'salinan_road_tax.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'gambar_kenderaan' => ['', 'array'],
            'gambar_kenderaan.*' => ['mimes:pdf,jpg,png', 'max:5120'],

        ], [
            'holder_id.required' => 'The holder ID field is required.',
            'holder_id.numeric' => 'The holder ID field needs to be numeric.',
            'applicant_category_id.required' => 'The applicant category field is required.',
            'applicant_category_id.numeric' => 'The applicant category field needs to be numeric.',
            // 'company_name.string' => 'The company name field needs to be a string.',
            // 'company_address.string' => 'The company address field needs to be a string.',
            'purpose.required' => 'The purpose field is required.',
            'purpose.string' => 'The purpose field needs to be a string.',
            'vehicletype.required' => 'The vehicle type field is required.',
            'vehicletype.numeric' => 'The vehicle type field needs to be numeric.',
            'vehicle_reg_no.required' => 'The vehicle registration number field is required.',
            'vehicle_reg_no.string' => 'The vehicle registration number field needs to be a string.',
            'vehicle_model.required' => 'The vehicle model field is required.',
            'vehicle_model.string' => 'The vehicle model field needs to be a string.',

            'surat_permohonan.required' => 'The surat permohonan field is required.',
            'surat_permohonan.mimes' => 'The surat permohonan field must be a file of type: pdf, jpg, png.',
            'surat_permohonan.max' => 'The surat permohonan field may not be greater than 5 MB.',

            'surat_indemnity.required' => 'The surat indemnity field is required.',
            'surat_indemnity.mimes' => 'The surat indemnity field must be a file of type: pdf, jpg, png.',
            'surat_indemnity.max' => 'The surat indemnity field may not be greater than 5 MB.',
            'surat_sokongan.required' => 'The surat sokongan field is required.',
            'surat_sokongan.mimes' => 'The surat sokongan field must be a file of type: pdf, jpg, png.',
            'surat_sokongan.max' => 'The surat sokongan field may not be greater than 5 MB.',
            'salinan_kad_pengenalan.required' => 'The salinan kad pengenalan field is required.',
            'salinan_kad_pengenalan.mimes' => 'The salinan kad pengenalan field must be a file of type: pdf, jpg, png.',
            'salinan_kad_pengenalan.max' => 'The salinan kad pengenalan field may not be greater than 5 MB.',
            'salinan_lesen_memandu.required' => 'The salinan lesen memandu field is required.',
            'salinan_lesen_memandu.mimes' => 'The salinan lesen memandu field must be a file of type: pdf, jpg, png.',
            'salinan_lesen_memandu.max' => 'The salinan lesen memandu field may not be greater than 5 MB.',
            'salinan_geran_kenderaan.required' => 'The salinan geran kenderaan field is required.',
            'salinan_geran_kenderaan.mimes' => 'The salinan geran kenderaan field must be a file of type: pdf, jpg, png.',
            'salinan_geran_kenderaan.max' => 'The salinan geran kenderaan field may not be greater than 5 MB.',
            'salinan_insurans_kenderaan.required' => 'The salinan insurans kenderaan field is required.',
            'salinan_insurans_kenderaan.mimes' => 'The salinan insurans kenderaan field must be a file of type: pdf, jpg, png.',
            'salinan_insurans_kenderaan.max' => 'The salinan insurans kenderaan field may not be greater than 5 MB.',
            'salinan_road_tax.required' => 'The salinan road tax field is required.',
            'salinan_road_tax.mimes' => 'The salinan road tax field must be a file of type: pdf, jpg, png.',
            'salinan_road_tax.max' => 'The salinan road tax field may not be greater than 5 MB.',
            'gambar_kenderaan.required' => 'The gambar kenderaan field is required.',
            'gambar_kenderaan.mimes' => 'The gambar kenderaan field must be a file of type: pdf, jpg, png.',
            'gambar_kenderaan.max' => 'The gambar kenderaan field may not be greater than 5 MB.',
        ]);

        //Update Document
        //Documents
        $fileFields = [
            'surat_permohonan' => 'surat_permohonan',
            'surat_indemnity' => 'surat_indemnity',
            'surat_sokongan' => 'surat_sokongan',
            'salinan_kad_pengenalan' => 'salinan_kad_pengenalan',
            'salinan_lesen_memandu' => 'salinan_lesen_memandu',
            'salinan_geran_kenderaan' => 'salinan_geran_kenderaan',
            'salinan_insurans_kenderaan' => 'salinan_insurans_kenderaan',
            'salinan_road_tax' => 'salinan_road_tax',
            'gambar_kenderaan' => 'gambar_kenderaan'
        ];


        foreach ($fileFields as $field => $directory) {

            if ($request->hasFile($field)) {
                $files = null;
                $files = $request->{$field};
                //Renaming process

                $filePaths = [];
                foreach ($files as $file) {
                    $uniqueFileName = uniqid() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($userPermit->holder_id . '/' . $directory, $uniqueFileName, 'public');
                    $filePaths[] = $filePath;
                }

                $resultFilePath = json_encode($filePaths);
                $userPermit->$field = $resultFilePath;

            }
        }

        $attributes = $request->except(array_keys($fileFields));
        $userPermit->update($attributes);
        $userPermit->clearAllComments();
        $userPermit->save();

        return redirect('/application')->with('success', 'User permit updated successfully.');
    }

    public function add(Request $request)
    {
        // If the request method is GET, render the form
        if ($request->isMethod('GET')) {
            $options = PermitHolder::where('customer_id', Auth::user()->id)->get();
            $applicantcatOptions = ApplicantCategory::all();
            $vehicleTypeOptions = VehicleType::all();
            return view('components.pages.application_add', compact('options', 'applicantcatOptions', 'vehicleTypeOptions'));
        }

        // Validate input fields
        $validatedData = $request->validate([
            'holder_id' => 'required|numeric',
            'applicant_category_id' => 'required|numeric',
            'purpose' => 'required|string',
            'vehicletype' => 'required|numeric',
            'reg_no' => 'required|string',
            'vehicle_model' => 'required|string',
            'surat_permohonan' => ['required', 'array'],
            'surat_permohonan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'surat_indemnity' => ['required', 'array'],
            'surat_indemnity.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'surat_sokongan' => ['required', 'array'],
            'surat_sokongan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_kad_pengenalan' => ['required', 'array'],
            'salinan_kad_pengenalan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_lesen_memandu' => ['required', 'array'],
            'salinan_lesen_memandu.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_geran_kenderaan' => ['required', 'array'],
            'salinan_geran_kenderaan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_insurans_kenderaan' => ['required', 'array'],
            'salinan_insurans_kenderaan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'salinan_road_tax' => ['required', 'array'],
            'salinan_road_tax.*' => ['mimes:pdf,jpg,png', 'max:5120'],
            'gambar_kenderaan' => ['required', 'array'],
            'gambar_kenderaan.*' => ['mimes:pdf,jpg,png', 'max:5120'],
        ], [
            'holder_id.required' => 'The holder ID field is required.',
            'holder_id.numeric' => 'The holder ID field needs to be numeric.',
            'applicant_category_id.required' => 'The applicant category field is required.',
            'applicant_category_id.numeric' => 'The applicant category field needs to be numeric.',
            'purpose.required' => 'The purpose field is required.',
            'purpose.string' => 'The purpose field needs to be a string.',
            'vehicletype.required' => 'The vehicle type field is required.',
            'vehicletype.numeric' => 'The vehicle type field needs to be numeric.',
            'reg_no.required' => 'The vehicle registration number field is required.',
            'reg_no.string' => 'The vehicle registration number field needs to be a string.',
            'vehicle_model.required' => 'The vehicle model field is required.',
            'vehicle_model.string' => 'The vehicle model field needs to be a string.',
            'surat_permohonan.required' => 'The surat permohonan field is required.',
            'surat_permohonan.array' => 'The surat permohonan field must be an array.',
            'surat_permohonan.*.mimes' => 'Each surat permohonan must be a file of type: pdf, jpg, png.',
            'surat_permohonan.*.max' => 'Each surat permohonan may not be greater than 5 MB.',
            'surat_indemnity.required' => 'The surat indemnity field is required.',
            'surat_indemnity.array' => 'The surat indemnity field must be an array.',
            'surat_indemnity.*.mimes' => 'Each surat indemnity must be a file of type: pdf, jpg, png.',
            'surat_indemnity.*.max' => 'Each surat indemnity may not be greater than 5 MB.',
            'surat_sokongan.required' => 'The surat sokongan field is required.',
            'surat_sokongan.array' => 'The surat sokongan field must be an array.',
            'surat_sokongan.*.mimes' => 'Each surat sokongan must be a file of type: pdf, jpg, png.',
            'surat_sokongan.*.max' => 'Each surat sokongan may not be greater than 5 MB.',
            'salinan_kad_pengenalan.required' => 'The salinan kad pengenalan field is required.',
            'salinan_kad_pengenalan.array' => 'The salinan kad pengenalan field must be an array.',
            'salinan_kad_pengenalan.*.mimes' => 'Each salinan kad pengenalan must be a file of type: pdf, jpg, png.',
            'salinan_kad_pengenalan.*.max' => 'Each salinan kad pengenalan may not be greater than 5 MB.',
            'salinan_lesen_memandu.required' => 'The salinan lesen memandu field is required.',
            'salinan_lesen_memandu.array' => 'The salinan lesen memandu field must be an array.',
            'salinan_lesen_memandu.*.mimes' => 'Each salinan lesen memandu must be a file of type: pdf, jpg, png.',
            'salinan_lesen_memandu.*.max' => 'Each salinan lesen memandu may not be greater than 5 MB.',
            'salinan_geran_kenderaan.required' => 'The salinan geran kenderaan field is required.',
            'salinan_geran_kenderaan.array' => 'The salinan geran kenderaan field must be an array.',
            'salinan_geran_kenderaan.*.mimes' => 'Each salinan geran kenderaan must be a file of type: pdf, jpg, png.',
            'salinan_geran_kenderaan.*.max' => 'Each salinan geran kenderaan may not be greater than 5 MB.',
            'salinan_insurans_kenderaan.required' => 'The salinan insurans kenderaan field is required.',
            'salinan_insurans_kenderaan.array' => 'The salinan insurans kenderaan field must be an array.',
            'salinan_insurans_kenderaan.*.mimes' => 'Each salinan insurans kenderaan must be a file of type: pdf, jpg, png.',
            'salinan_insurans_kenderaan.*.max' => 'Each salinan insurans kenderaan may not be greater than 5 MB.',
            'salinan_road_tax.required' => 'The salinan road tax field is required.',
            'salinan_road_tax.array' => 'The salinan road tax field must be an array.',
            'salinan_road_tax.*.mimes' => 'Each salinan road tax must be a file of type: pdf, jpg, png.',
            'salinan_road_tax.*.max' => 'Each salinan road tax may not be greater than 5 MB.',
            'gambar_kenderaan.required' => 'The gambar kenderaan field is required.',
            'gambar_kenderaan.array' => 'The gambar kenderaan field must be an array.',
            'gambar_kenderaan.*.mimes' => 'Each gambar kenderaan must be a file of type: pdf, jpg, png.',
            'gambar_kenderaan.*.max' => 'Each gambar kenderaan may not be greater than 5 MB.',
        ]);

        //Exception: If Applicant Category is Non-Resident
        if ($request->applicant_category_id !== '1' && $request->applicant_category_id !== '0') {
            $validatedData = $request->validate([
                'company_name' => 'required|string',
                'company_address' => 'required|string',
            ], [
                'company_name.string' => 'The company name field needs to be a string.',
                'company_address.string' => 'The company address field needs to be a string.',
            ]);
        }

        //--------------------------------------------------------------------------------------------------
        $permitApplication = new PermitApplication();
        $newHolderId = null;
        $newHolder = null;

        //Optional:
        $permitApplication->company_name = $request->company_name;
        $permitApplication->company_address = $request->company_address;

        if ($request->holder_id == 0) {
            $validatedData = $request->validate([
                'holder_name' => 'required|string',
                'identification_no' => 'required|string|max:20',
                'contact_no' => 'required|string|max:20',
                'address' => 'required|string',
            ], [
                'holder_name.required' => 'The holder name field is required.',
                'holder_name.string' => 'The holder name must be a string.',
                'identification_no.required' => 'The identification number field is required.',
                'identification_no.string' => 'The identification number must be a string.',
                'identification_no.max' => 'The identification number may not be greater than 20 characters.',
                'contact_no.required' => 'The contact number field is required.',
                'contact_no.string' => 'The contact number must be a string.',
                'contact_no.max' => 'The contact number may not be greater than 20 characters.',
                'address.required' => 'The address field is required.',
                'address.string' => 'The address must be a string.',
            ]);
            $newHolder = new PermitHolder();
            $newHolder->name = $request->holder_name;
            $newHolder->identification_no = $request->identification_no;
            $newHolder->contact_no = $request->contact_no;
            $newHolder->address = $request->address;
            $newHolder->customer_id = Auth::user()->id;
            $newHolder->save();
            $newHolderId = $newHolder->id;

            //if the holder_id is chosen
        } elseif ($request->holder_id) {
            $newHolderId = $request->holder_id;
        } else {
            $newHolderId = null;
        }

        //Applicants
        $permitApplication->holder_id = $newHolderId;
        $permitApplication->applicant_category_id = $request->applicant_category_id;
        $permitApplication->company_name = $request->company_name; //optional
        $permitApplication->company_address = $request->company_address; //optional
        $permitApplication->purpose = $request->purpose;

        //Vehicles
        $newVehicle = new Vehicle();
        $newVehicle->type = $request->vehicletype;
        $newVehicle->reg_no = $request->reg_no;
        $newVehicle->model = $request->vehicle_model;
        $newVehicle->save();
        $permitApplication->vehicle_id = $newVehicle->id;

        //Documents
        $fileFields = [
            'surat_permohonan' => 'surat_permohonan',
            'surat_indemnity' => 'surat_indemnity',
            'surat_sokongan' => 'surat_sokongan',
            'salinan_kad_pengenalan' => 'salinan_kad_pengenalan',
            'salinan_lesen_memandu' => 'salinan_lesen_memandu',
            'salinan_geran_kenderaan' => 'salinan_geran_kenderaan',
            'salinan_insurans_kenderaan' => 'salinan_insurans_kenderaan',
            'salinan_road_tax' => 'salinan_road_tax',
            'gambar_kenderaan' => 'gambar_kenderaan'
        ];

        foreach ($fileFields as $field => $directory) {
            if ($request->hasFile($field)) {
                $files = null;
                $files = $request->{$field};
                //Renaming process

                $filePaths = [];
                foreach ($files as $file) {
                    $uniqueFileName = uniqid() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($newHolderId . '/' . $directory, $uniqueFileName, 'public');
                    $filePaths[] = $filePath;
                }
                // Convert the array to a JSON-encoded string
                $resultFilePath = json_encode($filePaths);
                $permitApplication->{$field} = $resultFilePath;
            }
        }

        //Add serial number
        $permitApplication->serial_no = $this->generateSerialCode();
        $permitApplication->customer_id = Auth::user()->id;
        $permitApplication->save();

        // Send email notification to the user
        // $user = Auth::user();
        // $user->notify(new PermitApplicationSubmitted($permitApplication));

        return redirect()->route('application')
            ->with('success', __('Your new application has been submitted successfully.'));
    }

    private function generateSerialCode($blocks = 3, $blockLength = 3)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $serialCode = '';

        for ($i = 0; $i < $blocks; $i++) {
            if ($i > 0) {
                $serialCode .= '-';
            }
            for ($j = 0; $j < $blockLength; $j++) {
                $serialCode .= $characters[rand(0, strlen($characters) - 1)];
            }
        }

        return $serialCode;
    }

    function cleanString($str)
    {
        // Check if the string starts and ends with [" and "]
        if (substr($str, 0, 2) === '["' && substr($str, -2) === '"]') {
            // Remove the first two characters and the last two characters
            $cleaned_str = '[' . substr($str, 2, -2) . ']';
        } else {
            // If the string does not have the expected format, return it as is
            $cleaned_str = $str;
        }

        return $cleaned_str;
    }
}