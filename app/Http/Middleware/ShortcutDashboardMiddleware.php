<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AdminUser;
use OpenAdmin\Admin\Facades\Admin;

class ShortcutDashboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //set roles to view shortcut dashboard
        if (Admin::user()->inRoles(['jkr-checker', 'jkr-approver', 'phc-second-approver'])) {
            return redirect('admin/shortcut');
        }
        return $next($request);
    }
}
