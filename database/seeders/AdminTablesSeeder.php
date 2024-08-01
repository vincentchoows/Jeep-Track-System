<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // base tables
        \OpenAdmin\Admin\Auth\Database\Menu::truncate();
        \OpenAdmin\Admin\Auth\Database\Menu::insert(
            [
                [
                    "parent_id" => 0,
                    "order" => 1,
                    "title" => "Dashboard",
                    "icon" => "icon-tachometer-alt",
                    "uri" => "/",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 30,
                    "order" => 15,
                    "title" => "Admin",
                    "icon" => "icon-server",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 16,
                    "title" => "Users",
                    "icon" => "icon-users",
                    "uri" => "auth/users",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 17,
                    "title" => "Roles",
                    "icon" => "icon-user",
                    "uri" => "auth/roles",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 18,
                    "title" => "Permission",
                    "icon" => "icon-ban",
                    "uri" => "auth/permissions",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 19,
                    "title" => "Menu",
                    "icon" => "icon-bars",
                    "uri" => "auth/menu",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 20,
                    "title" => "Operation log",
                    "icon" => "icon-history",
                    "uri" => "auth/logs",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 30,
                    "order" => 24,
                    "title" => "Helpers",
                    "icon" => "icon-cogs",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 25,
                    "title" => "Scaffold",
                    "icon" => "icon-keyboard",
                    "uri" => "helpers/scaffold",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 26,
                    "title" => "Database terminal",
                    "icon" => "icon-database",
                    "uri" => "helpers/terminal/database",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 27,
                    "title" => "Laravel artisan",
                    "icon" => "icon-terminal",
                    "uri" => "helpers/terminal/artisan",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 28,
                    "title" => "Routes",
                    "icon" => "icon-list-alt",
                    "uri" => "helpers/routes",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 19,
                    "order" => 12,
                    "title" => "Users",
                    "icon" => "icon-user-check",
                    "uri" => "users",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 18,
                    "order" => 23,
                    "title" => "Permit-application-logs",
                    "icon" => "icon-file",
                    "uri" => "permit-application-logs",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 18,
                    "order" => 22,
                    "title" => "Permit-renewal-logs",
                    "icon" => "icon-file",
                    "uri" => "permit-renewal-logs",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 21,
                    "title" => "Logs",
                    "icon" => "icon-folder",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 11,
                    "title" => "Customer",
                    "icon" => "icon-address-book",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 2,
                    "title" => "Applications",
                    "icon" => "icon-car",
                    "uri" => "permit-applications",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 3,
                    "title" => "Permit",
                    "icon" => "icon-file-signature",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 21,
                    "order" => 4,
                    "title" => "Permit Holders",
                    "icon" => "icon-file-alt",
                    "uri" => "permit-holders",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 21,
                    "order" => 5,
                    "title" => "Applicant Categories",
                    "icon" => "icon-file-contract",
                    "uri" => "applicant-categories",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 21,
                    "order" => 6,
                    "title" => "Vehicle Types",
                    "icon" => "icon-file-contract",
                    "uri" => "vehicle-types",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 9,
                    "title" => "Report",
                    "icon" => "icon-compass",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 26,
                    "order" => 10,
                    "title" => "Transactions",
                    "icon" => "icon-table",
                    "uri" => "transactions",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 21,
                    "order" => 7,
                    "title" => "Extend Permit xxx",
                    "icon" => "icon-file-contract",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 21,
                    "order" => 8,
                    "title" => "Renewal xxx",
                    "icon" => "icon-file-contract",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 13,
                    "title" => "System",
                    "icon" => "icon-wrench",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 30,
                    "order" => 14,
                    "title" => "Settings",
                    "icon" => "icon-toolbox",
                    "uri" => "settings",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 30,
                    "order" => 29,
                    "title" => "Phpinfo",
                    "icon" => "icon-info-circle",
                    "uri" => "phpinfo",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 30,
                    "order" => 30,
                    "title" => "Log viewer",
                    "icon" => "icon-exclamation-triangle",
                    "uri" => "logs",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 30,
                    "title" => "Students",
                    "icon" => "icon-file",
                    "uri" => "students",
                    "permission" => NULL
                ]
            ]
        );

        \OpenAdmin\Admin\Auth\Database\Permission::truncate();
        \OpenAdmin\Admin\Auth\Database\Permission::insert(
            [
                [
                    "name" => "All permission",
                    "slug" => "*",
                    "http_method" => "",
                    "http_path" => "*"
                ],
                [
                    "name" => "Dashboard",
                    "slug" => "dashboard",
                    "http_method" => "GET",
                    "http_path" => "/"
                ],
                [
                    "name" => "Login",
                    "slug" => "auth.login",
                    "http_method" => "",
                    "http_path" => "/auth/login\r\n/auth/logout"
                ],
                [
                    "name" => "User setting",
                    "slug" => "auth.setting",
                    "http_method" => "GET,PUT",
                    "http_path" => "/auth/setting"
                ],
                [
                    "name" => "Auth management",
                    "slug" => "auth.management",
                    "http_method" => "",
                    "http_path" => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs"
                ],
                [
                    "name" => "Admin helpers",
                    "slug" => "ext.helpers",
                    "http_method" => "",
                    "http_path" => "/helpers/*"
                ],
                [
                    "name" => "Logs",
                    "slug" => "ext.log-viewer",
                    "http_method" => "",
                    "http_path" => "/logs*"
                ]
            ]
        );

        \OpenAdmin\Admin\Auth\Database\Role::truncate();
        \OpenAdmin\Admin\Auth\Database\Role::insert(
            [
                [
                    "name" => "Administrator",
                    "slug" => "administrator"
                ],
                [
                    "name" => "Intern",
                    "slug" => "intern"
                ]
            ]
        );

        // pivot tables
        DB::table('admin_role_menu')->truncate();
        DB::table('admin_role_menu')->insert(
            [
                [
                    "role_id" => 1,
                    "menu_id" => 2
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 8
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 18
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 19
                ]
            ]
        );

        DB::table('admin_role_permissions')->truncate();
        DB::table('admin_role_permissions')->insert(
            [
                [
                    "role_id" => 1,
                    "permission_id" => 1
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 2
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 3
                ]
            ]
        );

        // finish
    }
}
