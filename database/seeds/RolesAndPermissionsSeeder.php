<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'add-asset-comment',

            'asset-list',
            'asset-create',
            'asset-edit',
            'asset-delete',

            'asset-category-list',
            'asset-category-create',
            'asset-category-edit',
            'asset-category-delete',

            'breach-list',
            'breach-create',
            'breach-edit',
            'breach-delete',

            'cascade-list',
            'cascade-create',
            'cascade-edit',
            'cascade-delete',

            'consequence-list',
            'consequence-create',
            'consequence-edit',
            'consequence-delete',

            'invite-list',
            'invite-create',
            'invite-delete',

            'loadlevel-list',
            'loadlevel-create',
            'loadlevel-edit',
            'loadlevel-delete',

            'news-list',
            'news-create',
            'news-edit',
            'news-delete',

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'scenario-list',
            'scenario-create',
            'scenario-edit',
            'scenario-delete',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // create roles and assign created permissions
        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo([
            'add-asset-comment',

            'asset-list',
            'asset-create',
            'asset-edit',

            'asset-category-list',

            'breach-list',

            'cascade-list',
            'cascade-create',
            'cascade-edit',

            'consequence-list',
            'consequence-create',

            'loadlevel-list',

            'news-list',
            'news-create',
            'news-edit',

            'scenario-list',
            'scenario-create',
            'scenario-edit',
        ]);

        $role = Role::create(['name' => 'member']);
        $role->givePermissionTo([
            'asset-list',

            'asset-category-list',

            'breach-list',

            'cascade-list',

            'loadlevel-list',

            'news-list',

            'add-asset-comment',

            'scenario-list',
        ]);
    }
}
