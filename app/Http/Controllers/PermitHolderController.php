<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PermitHolder;
use OpenAdmin\Admin\Actions\Interactor\Form;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumberValidator;


class PermitHolderController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $sort_by = $request->input('sort_by', 'id');
        $sort_direction = $request->input('sort_direction', 'asc');

        $tables = PermitHolder::where('customer_id', $user->id)
            ->orderBy($sort_by, $sort_direction)
            ->paginate(10);

        return view('components.pages.permitholder', [
            'tables' => $tables,
            'sort_by' => $sort_by,
            'sort_direction' => $sort_direction
        ]);
    }

    public function show(Request $request, $id)
    {
        $userPermit = PermitHolder::where('customer_id', Auth::id())
            ->findOrFail($id);
        return view('components.pages.permitholder_show', compact('userPermit'));
    }


    public function edit(Request $request, $id)
    {
        $userPermit = PermitHolder::findOrFail($id);

        // If the request method is GET, render the form
        if ($request->isMethod('GET')) {
            return view('components.pages.permitholder_show', compact('userPermit'));
        }

        // Validate input fields
        $request->validate([
            'name' => 'string|max:255',
            'identification_no' =>  'numeric',
            // 'contact_no' => 'string',
            // 'contact_no' => [Rule::phone()->country(['INTERNATIONAL', 'MY'])],
            'contact_no' => 'phone:INTERNATIONAL,MY',
            'address' => 'string',
        ]);

        $userPermit->update($request->all());
        $userPermit->save();

        // Send a successful prompt message
        return redirect()->route('permitholder', $id)->with('success', __('User Permit Updated Successfully.'));

    }

    public function editShow(Request $request, $id)
    {
        $userPermit = PermitApplication::findOrFail($id);
        return view('components.pages.permitholder_edit', compact('userPermit'));
    }

    public function add(Request $request)
    {
        // If the request method is GET, render the form
        if ($request->isMethod('GET')) {
            return view('components.pages.permitholder_add');
        }

        // Validate input fields
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'identification_no' => 'required|numeric',
            'contact_no' => 'required|numeric',
            'address' => 'required|string',
        ]);

        $permitHolder = new PermitHolder();
        $permitHolder->name = $validatedData['name'];
        $permitHolder->customer_id = Auth::user()->id;
        $permitHolder->identification_no = $validatedData['identification_no'];
        $permitHolder->contact_no = $validatedData['contact_no'];
        $permitHolder->address = $validatedData['address'];
        $permitHolder->save();
        return redirect()->route('permitholder')->with('success', 'Permit Holder Added Successfully.');
    }

}



// this is inserted from the terminal 







