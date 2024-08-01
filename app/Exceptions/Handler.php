<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Facades\Admin;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        //Error for customer user 
        if (Auth::user()) {

            // Handle ModelNotFoundException
            if ($exception instanceof ModelNotFoundException) {
                return response()->view('errors.404', []);
            }

            // Handle NotFoundHttpException
            if ($exception instanceof NotFoundHttpException) {
                return response()->view('errors.404', []);
            }

            // Handle ModelNotFoundException and NotFoundHttpException
            if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                session()->flash('error_message', $exception->getMessage()); // Flash the exception message to the session
                return response()->view('errors.404', []);
            }

            if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                return response()->view('errors.custom_404', ['exception' => $exception], 404);
            }

            

        }



        // Error for Admin user
        if (Admin::user()) {
            // Redirect back to the current page with an error message
            // return back()->with('error_message', 'An error occurred. Please try again.');
            return back();
        }

        // Let Laravel handle other types of exceptions
        return parent::render($request, $exception);

    }
}
