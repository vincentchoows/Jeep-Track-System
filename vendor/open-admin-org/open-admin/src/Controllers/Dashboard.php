<?php

namespace OpenAdmin\Admin\Controllers;

use App\Models\PermitRenewalLog;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\PermitApplicationLog;
use OpenAdmin\Admin\Auth\Database\Role;
use OpenAdmin\Admin\Auth\Database\Administrator;
use OpenAdmin\Admin\Facades\Admin as AdminFacade;
use OpenAdmin\Admin\Admin;

class Dashboard
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function title()
    {
        return view('admin::dashboard.title');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function environment()
    {
        $envs = [
            ['name' => 'PHP version', 'value' => 'PHP/' . PHP_VERSION],
            ['name' => 'Laravel version', 'value' => app()->version()],
            ['name' => 'CGI', 'value' => php_sapi_name()],
            ['name' => 'Uname', 'value' => php_uname()],
            ['name' => 'Server', 'value' => Arr::get($_SERVER, 'SERVER_SOFTWARE')],

            ['name' => 'Cache driver', 'value' => config('cache.default')],
            ['name' => 'Session driver', 'value' => config('session.driver')],
            ['name' => 'Queue driver', 'value' => config('queue.default')],

            ['name' => 'Timezone', 'value' => config('app.timezone')],
            ['name' => 'Locale', 'value' => config('app.locale')],
            ['name' => 'Env', 'value' => config('app.env')],
            ['name' => 'URL', 'value' => config('app.url')],
        ];

        return view('admin::dashboard.environment', compact('envs'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function extensions()
    {
        $extensions = [
            'helpers' => [
                'name' => 'open-admin-ext/helpers',
                'link' => 'https://github.com/open-admin-org/helpers',
                'icon' => 'cogs',
            ],
            'log-viewer' => [
                'name' => 'open-admin-ext/log-viewer',
                'link' => 'https://github.com/open-admin-org/log-viewer',
                'icon' => 'database',
            ],
            'backup' => [
                'name' => 'open-admin-ext/backup',
                'link' => 'https://github.com/open-admin-org/backup',
                'icon' => 'copy',
            ],
            'config' => [
                'name' => 'open-admin-ext/config',
                'link' => 'https://github.com/open-admin-org/config',
                'icon' => 'toggle-on',
            ],
            'api-tester' => [
                'name' => 'open-admin-ext/api-tester',
                'link' => 'https://github.com/open-admin-org/api-tester',
                'icon' => 'sliders-h',
            ],
            'media-manager' => [
                'name' => 'open-admin-ext/media-manager',
                'link' => 'https://github.com/open-admin-org/media-manager',
                'icon' => 'file',
            ],
            'scheduling' => [
                'name' => 'open-admin-ext/scheduling',
                'link' => 'https://github.com/open-admin-org/scheduling',
                'icon' => 'clock',
            ],
            'reporter' => [
                'name' => 'open-admin-ext/reporter',
                'link' => 'https://github.com/open-admin-org/reporter',
                'icon' => 'bug',
            ],
            'redis-manager' => [
                'name' => 'open-admin-ext/redis-manager',
                'link' => 'https://github.com/open-admin-org/redis-manager',
                'icon' => 'flask',
            ],
            'grid-sortable' => [
                'name' => 'open-admin-ext/grid-sortable',
                'link' => 'https://github.com/open-admin-org/grid-sortable',
                'icon' => 'arrows-alt-v',
            ],
        ];

        foreach ($extensions as &$extension) {
            $name = explode('/', $extension['name']);
            $extension['installed'] = array_key_exists(end($name), Admin::$extensions);
        }

        return view('admin::dashboard.extensions', compact('extensions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function dependencies()
    {
        $json = file_get_contents(base_path('composer.json'));
        $dependencies = json_decode($json, true)['require'];
        return Admin::component('admin::dashboard.dependencies', compact('dependencies'));
    }

    //custom widgets ------------------------------------------------------------------->

    public static function latest_renewal_activity()
    {
        $envs = PermitRenewalLog::with('adminUser')
            ->orderByDesc('created_at')
            ->get();
        $widgetTitle = __('Renewal Activity');
        $rowQuantity = 10;
        return view('admin::dashboard.latest-renewal-activity', compact('envs', 'widgetTitle', 'rowQuantity'));
    }

    public static function latest_application_activity()
    {
        if (AdminFacade::user()->inRoles(['sysadmin'])) {
            $roles = Role::all();
            if ($roles->isNotEmpty()) {
                $adminIds = $roles->flatMap->administrators()->pluck('id');
                $adminIds = $roles->pluck('administrators.*.id')->flatten();
                $envs = PermitApplicationLog::with('adminUser')
                    ->whereIn('admin_user_id', $adminIds)
                    ->orderByDesc('created_at')
                    ->get();
                $rowQuantity = 10;
                $widgetTitle = __('Application Activity');
                return view('admin::dashboard.latest-application-activity', compact('envs', 'widgetTitle', 'rowQuantity'));
            }
        } elseif (AdminFacade::user()->inRoles(['phc'])) {
            $roles = Role::where('slug', 'phc')->get();
            if ($roles->isNotEmpty()) {
                $adminIds = $roles->flatMap->administrators()->pluck('id');
                $adminIds = $roles->pluck('administrators.*.id')->flatten();
                $envs = PermitApplicationLog::with('adminUser')
                    ->whereIn('admin_user_id', $adminIds)
                    ->orderByDesc('created_at')
                    ->get();
                $rowQuantity = 10;
                $widgetTitle = __('Application Activity');
                return view('admin::dashboard.latest-application-activity', compact('envs', 'widgetTitle', 'rowQuantity'));
            }
        } elseif (AdminFacade::user()->inRoles(['jkr'])) {
            $roles = Role::where('slug', 'jkr')->get();
            if ($roles->isNotEmpty()) {
                $adminIds = $roles->flatMap->administrators()->pluck('id');
                $adminIds = $roles->pluck('administrators.*.id')->flatten();
                $envs = PermitApplicationLog::with('adminUser')
                    ->whereIn('admin_user_id', $adminIds)
                    ->orderByDesc('created_at')
                    ->get();
                $rowQuantity = 10;
                $widgetTitle = __('Application Activity');
                return view('admin::dashboard.latest-application-activity', compact('envs', 'widgetTitle', 'rowQuantity'));
            }
        } elseif (AdminFacade::user()->inRoles(['fin'])) {
            $roles = Role::where('slug', 'fin')->get();
            if ($roles->isNotEmpty()) {
                $adminIds = $roles->flatMap->administrators()->pluck('id');
                $adminIds = $roles->pluck('administrators.*.id')->flatten();
                $envs = PermitApplicationLog::with('adminUser')
                    ->whereIn('admin_user_id', $adminIds)
                    ->orderByDesc('created_at')
                    ->get();
                $rowQuantity = 10;
                $widgetTitle = __('Application Activity');
                return view('admin::dashboard.latest-application-activity', compact('envs', 'widgetTitle', 'rowQuantity'));
            }
        } else {
            // Do nothing
        }

    }

    //Administrator Widget ------------------------------------------------>

    // public static function total_pending_approval_infobox()
    // {
    //     return view('admin::dashboard.total-pending-approval-infobox');
    // }

    public static function total_monthly_application_infobox()
    {
        return view('admin::dashboard.administrator-infobox.total-monthly-application-infobox');
    }

    public static function total_pending_renewal_infobox()
    {
        return view('admin::dashboard.administrator-infobox.total-pending-renewal-infobox');
    }

    public static function total_monthly_transaction_infobox()
    {
        return view('admin::dashboard.administrator-infobox.total-monthly-transaction-infobox');
    }

    public static function total_unapproved_application_infobox()
    {
        return view('admin::dashboard.administrator-infobox.total-unapproved-application-infobox');
    }

    public static function new_tab_infobox()
    {
        return view('admin::dashboard.new-tab-infobox');
    }

    public static function approved_tab_infobox()
    {
        return view('admin::dashboard.approved-tab-infobox');
    }

    public static function rejected_tab_infobox()
    {
        return view('admin::dashboard.rejected-tab-infobox');
    }

    // OpenAdmin Components ------------------------------------------------------------------->
    public static function test_info_box()
    {
        $envs = [['name' => 'URL', 'value' => 'this is here'],];
        return view('admin::dashboard.test-widgets.test', compact('envs'));
    }
    public static function test_box()
    {
        $envs = [['name' => 'URL', 'value' => 'this is here'],];
        return view('admin::dashboard.test-widgets.test-box', compact('envs'));
    }
    public static function test_collapse()
    {
        $envs = [['name' => 'URL', 'value' => 'this is here'],];
        return view('admin::dashboard.test-widgets.test-collapse', compact('envs'));
    }
    public static function test_form()
    {
        $envs = [['name' => 'URL', 'value' => 'this is here'],];
        return view('admin::dashboard.test-widgets.test-form', compact('envs'));
    }
    public static function test_tab()
    {
        $envs = [['name' => 'URL', 'value' => 'this is here'],];
        return view('admin::dashboard.test-widgets.test-tab', compact('envs'));
    }
    public static function test_infobox()
    {
        $envs = [['name' => 'URL', 'value' => 'this is here'],];
        return view('admin::dashboard.test-widgets.test-infobox', compact('envs'));
    }


    // PHC ------------------------------------------------------------------->
    public static function pending_checking_infobox()
    {
        return view('admin::dashboard.admin-user-infobox.pending-checking-infobox');
    }

    public static function pending_approving_infobox()
    {
        return view('admin::dashboard.admin-user-infobox.pending-approving-infobox');
    }
    public static function pending_second_approving_infobox()
    {
        return view('admin::dashboard.admin-user-infobox.pending-second-approving-infobox');
    }

    public static function pending_card_assign()
    {
        return view('admin::dashboard.admin-user-infobox.pending-card-assign-infobox');
    }


}
