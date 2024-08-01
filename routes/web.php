<?php

use App\Http\Controllers\PermitHolderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RenewalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\PermitApplication;
use App\Models\PermitHolder;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Auth;
use App\Admin\Controllers\UserController;
use App\Models\User;
use App\Notifications\PermitApproved;


//-------------------------------------------------------------------------------------
//API for selections in Admin blade view
//NOTE: 
//    * API put above
//    * Remove GET request! 
//-------------------------------------------------------------------------------------
Route::match(['get', 'post'], '/admin/api/users', [UserController::class, 'usersAjax']);
Route::match(['get', 'post'], '/admin/api/holders', [UserController::class, 'holdersAjax']);

//-------------------------------------------------------------------------------------
//API for email notificaiton testing
//NOTE: API put above
//-------------------------------------------------------------------------------------
Route::get('/test-notification', function () {
    $user = User::find(1); // Replace with the actual user or recipient
    $user->notify(new PermitApproved);
    return "Notification sent!";
});

/*
|--------------------------------------------------------------------------
| Email Verification
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| TESTING
|--------------------------------------------------------------------------
*/

Route::get('/test-alert', function () {
    return view('errors.404')->with('success_message', 'Test message.');
});

//proceeding paymet testing 
Route::get('/proceed-to-payment', function () {
    return redirect()->route('application')->with('warning', __('Please do not cancel or exit the browser while proceeding with payment.'));
})->name('proceed.to.payment');


//proceeding to complete payment
Route::get('/proceed-to-payment', function () {
    return redirect()->route('application')->with('warning', __('Please do not cancel or exit the browser while proceeding with payment.'));
})->name('proceed.to.payment');


Route::get('/complete-payment/{id}', function ($id) {
    // Find the permit application by ID
    $permitApplication = PermitApplication::findOrFail($id);

    // Update the status to 2 (Paid)
    $permitApplication->transaction_status = 1;
    $permitApplication->status = 3;
    $permitApplication->save();

    // Redirect to the desired route with a success message
    return redirect()->route('application')->with('success', __('Payment successfully completed. Your application status has been updated.'));
})->name('complete.payment');


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    $carouselItems = [
        ['title' => 'First Slide'],
        ['title' => 'Second Slide'],
        ['title' => 'Third Slide'],
    ];
    return view('components.pages.home', compact('carouselItems'));
})->name('/');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('/application', function () {
    return view('components.pages.application');
})->name('dashboard');

Route::group([ 'middleware' => [ 'auth', 'verified' ] ], function () {


    //-------------------------------------------------------------------------------------
    // Application 
    //-------------------------------------------------------------------------------------

    Route::get('/application', [ApplicationController::class, 'index'])->name('application');

    Route::get('/application/add', [ApplicationController::class, 'add'])->name('application.add');

    Route::post('/application/add', [ApplicationController::class, 'add'])->name('application.store');

    Route::get('/application/{id}', [ApplicationController::class, 'show'])->name('application.view');

    Route::get('/application/{id}/edit', [ApplicationController::class, 'edit'])->name('application.edit');

    Route::post('/application/{id}/edit', [ApplicationController::class, 'edit'])->name('application.edit');

    

    //-------------------------------------------------------------------------------------
    //My Permits
    //-------------------------------------------------------------------------------------

    Route::get('/mypermit', [ApplicationController::class, 'indexMyPermit'])->name('mypermit');

    Route::get('/mypermit/{id}', [ApplicationController::class, 'showMyPermit'])->name('mypermit.view');

    //-------------------------------------------------------------------------------------
    //Renewal 
    //-------------------------------------------------------------------------------------

    Route::get('/renewal', [RenewalController::class, 'index'])->name('renewal');

    Route::get('/renewal/{id}', [RenewalController::class, 'show'])->name('renewal.view');


    //-------------------------------------------------------------------------------------
    //Permit Holder 
    //-------------------------------------------------------------------------------------

    Route::get('/permitholder', [PermitHolderController::class, 'index'])->name('permitholder');

    Route::get('/permitholder/add', [PermitHolderController::class, 'add'])->name('permitholder.add');

    Route::post('/permitholder/add', [PermitHolderController::class, 'add'])->name('permitholder.add');

    Route::get('/permitholder/{id}', [PermitHolderController::class, 'show'])->name('permitholder.view');

    Route::get('/permitholder/{id}/edit', [PermitHolderController::class, 'edit'])->name('permitholder.edit');

    Route::post('/permitholder/{id}/edit', [PermitHolderController::class, 'edit'])->name('permitholder.submit');

    //-------------------------------------------------------------------------------------
    //Help
    //-------------------------------------------------------------------------------------
    Route::get('/help', function () {
        return view('components.pages.help');
    })->name('help');

    //-------------------------------------------------------------------------------------
    //Error pages 
    //-------------------------------------------------------------------------------------

    Route::get('/error', function () {
        return view('errors.404');
    })->name('error');


    //-------------------------------------------------------------------------------------
    //Customer File Display/Redirect 
    //-------------------------------------------------------------------------------------

    Route::get('files/{holderid}/{filecat}/{filename}', function ($holderid, $filecat, $filename) {

        $filePath = public_path('uploads/files/' . $holderid . '/' . $filecat . '/' . $filename);
        if (!file_exists($filePath)) {
            abort(404);
        }
        // Serve the file
        return response()->file($filePath);
    })->middleware('auth');




});


require __DIR__ . '/auth.php';
