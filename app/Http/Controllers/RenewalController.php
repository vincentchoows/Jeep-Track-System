<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PermitHolderController;
use App\Models\PermitApplication;
use App\Models\PermitHolder;
use App\Models\ApplicantCategory;
use App\Models\VehicleType;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class RenewalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Query database based on sorting criteria
        $tables = PermitApplication::where('customer_id', $user->id)
            ->where('transaction_status', 2)
            ->where(function ($query) {
                $query->where('status', '2');
            })
            ->where(function ($query) {
                $query->whereDate('end_date', '<', Carbon::now()->addDays(30))
                    ->orWhereDate('end_date', '<', Carbon::now());
            })
            ->orderBy($sortBy, $sortDirection)
            ->with('holder', 'user', 'applicantcat')
            ->paginate(10);

        foreach ($tables as $table) {
            if (isset($table->end_date)) {
                $endDate = Carbon::parse($table->end_date);
                if ($endDate->isPast()) {
                    // End date is in the past, calculate negative days left
                    $daysLeft = -now()->diffInDays($endDate);
                } else {
                    // End date is in the future, calculate positive days left
                    $daysLeft = now()->diffInDays($endDate);
                }
                $table->days_left = $daysLeft;
            } else {
                // No end date set, days left is null
                $table->days_left = null;
            }
        }

        return view('components.pages.renewal', [
            'tables' => $tables,
            'sort_direction' => $sortDirection,
        ]);
    }

    public function show(Request $request, $id)
    {
        $userPermit = PermitApplication::where('customer_id', Auth::id())
            ->findOrFail($id);

        $fileArray = []; // Initialize the $fileArray variable
        $applicantcatName = "";
        $applicantcatOptions = ApplicantCategory::all();

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

        $fileArray = []; // Initialize the $fileArray

        foreach ($fileTypes as $field) {
            // Check if the field exists in $userPermit and if its value is a JSON string
            if (isset($userPermit->$field) && $this->isJson($userPermit->$field)) {
                // Decode the JSON string into an array
                $decodedValue = json_decode($userPermit->$field, true);

                // Merge the decoded array into the $fileArray while preserving keys
                foreach ($decodedValue as $key => $value) {
                    $fileArray[$field][$key] = $value;
                }
            } else {
                $fileArray[$field] = $userPermit->$field;
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
                $statusString = __('Approving');
                break;
            case 1:
                $statusString = __('Amend');
                break;
            case 2:
                $statusString = __('Approved');
                break;
            case 3:
                $statusString = __('Rejected');
                break;
            case 4:
                $statusString = __('Restricted');
                break;
            default:
                $statusString = __('Unknown');
                break;
        }

        $renewalPage = true;
        $applicantcatOptions = ApplicantCategory::all();
        $permitHolderOptions = PermitHolder::all();
        return view('components.pages.application_show', compact('userPermit', 'applicantcatOptions', 'applicantcatName', 'fileArray', 'statusString', 'renewalPage'));
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


}
