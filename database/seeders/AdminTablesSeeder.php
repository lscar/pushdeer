<?php

namespace Database\Seeders;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'name'     => 'Administrator',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
            [
                'name'        => 'Logs',
                'slug'        => 'ext.log-viewer',
                'http_method' => '',
                'http_path'   => "/logs*",
            ],
            [
                'name'        => 'PushDeer management',
                'slug'        => 'pushdeer.management',
                'http_method' => '',
                'http_path'   => "/push-deer-users*\r\n/push-deer-devices*\r\n/push-deer-keys*\r\n/push-deer-messages*",
            ],
            [
                'name'        => 'Scheduling',
                'slug'        => 'ext.scheduling',
                'http_method' => '',
                'http_path'   => "/scheduling*",
            ],
        ]);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::upsert([
            [
                'id'        => 1,
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'Dashboard',
                'icon'      => 'fa-bar-chart',
                'uri'       => '/',
            ],
            [
                'id'        => 2,
                'parent_id' => 0,
                'order'     => 2,
                'title'     => 'Admin',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'id'        => 3,
                'parent_id' => 2,
                'order'     => 1,
                'title'     => 'Users',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'id'        => 4,
                'parent_id' => 2,
                'order'     => 2,
                'title'     => 'Roles',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'id'        => 5,
                'parent_id' => 2,
                'order'     => 3,
                'title'     => 'Permission',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
            ],
            [
                'id'        => 6,
                'parent_id' => 2,
                'order'     => 4,
                'title'     => 'Menu',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
            ],
            [
                'id'        => 7,
                'parent_id' => 2,
                'order'     => 5,
                'title'     => 'Operation log',
                'icon'      => 'fa-history',
                'uri'       => 'auth/logs',
            ],
            [
                'id'        => 8,
                'parent_id' => 0,
                'order'     => 3,
                'title'     => 'PushDeer',
                'icon'      => 'fa-cloud',
                'uri'       => '',
            ],
            [
                'id'        => 9,
                'parent_id' => 3,
                'order'     => 1,
                'title'     => 'Users',
                'icon'      => 'fa-users',
                'uri'       => 'push-deer-users',
            ],
            [
                'id'        => 10,
                'parent_id' => 3,
                'order'     => 2,
                'title'     => 'Devices',
                'icon'      => 'fa-mobile',
                'uri'       => 'push-deer-devices',
            ],
            [
                'id'        => 11,
                'parent_id' => 3,
                'order'     => 3,
                'title'     => 'Keys',
                'icon'      => 'fa-key',
                'uri'       => 'push-deer-keys',
            ],
            [
                'id'        => 12,
                'parent_id' => 3,
                'order'     => 4,
                'title'     => 'Messages',
                'icon'      => 'fa-comments',
                'uri'       => 'push-deer-messages',
            ],
            [
                'id'        => 13,
                'parent_id' => 0,
                'order'     => 4,
                'title'     => 'LogViewer',
                'icon'      => 'fa-database',
                'uri'       => 'logs',
            ],
            [
                'id'        => 14,
                'parent_id' => 0,
                'order'     => 5,
                'title'     => 'Scheduling',
                'icon'      => 'fa-clock-o',
                'uri'       => 'scheduling',
            ],
        ], 'id');

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
    }
}
