<?php

use App\Admin\Controllers\HomeController;
use App\Admin\Controllers\PermitApplicationController;
use App\Admin\Controllers\DashboardController;
use Illuminate\Routing\Router;
use OpenAdmin\Admin\Facades\Admin;

// use App\Admin\Controllers\PageController as RoutePageController;
// use App\Http\Middleware\ShortcutDashboardMiddleware;


Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix') . '.',
], function (Router $router) {


    // dd(Auth::user());
    // if (Admin::user()->inRoles(['phc-second-approver'])) {
    //     //Admin login page
    //     $router->get('/', function () {
    //         return redirect('admin/permit-applications');
    //     })->name('home');
    // }else{
    //     //Admin login page
    //     $router->get('/', function () {
    //         return redirect('admin/dashboard');
    //     })->name('home');
    // }


    $router->get('/', function () {
        return redirect('admin/dashboard');
    })->name('home');

    $router->resource('/dashboard', DashboardController::class);

    $router->get('/settings', 'SettingsController@index')->name('settings');
    $router->resource('users', UserController::class);
    //$router->resource('users', AdminOperation::class);
    $router->resource('admin-users', AdminUserController::class);

    $router->resource('permit-applications', PermitApplicationController::class);
    $router->resource('permit-applications-log', PermitApplicationController::class);

    $router->resource('permit-application-logs', PermitApplicationLogController::class);
    $router->resource('permit-renewal-logs', PermitRenewalLogController::class);


    $router->resource('permit-holders', PermitHolderController::class);
    $router->resource('applicant-categories', ApplicantCategoryController::class);
    $router->resource('vehicle-types', VehicleTypeController::class);
    $router->resource('transactions', TransactionController::class);


    $router->resource('vehicles', VehicleController::class);
    $router->resource('permit-extend-logs', PermitExtendLogController::class);
    $router->resource('permit-extend-logs', PermitExtendLogController::class);
    $router->resource('permit-extend', PermitExtendController::class);
    $router->resource('permit-renewals', PermitRenewalController::class);

    //test routes 
    // $router->post('pages/release', [RoutePageController::class,'release']);

});
