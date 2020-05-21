<?php

namespace Modules\User\Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        // User permissions
        $permissions[] = ['name' => 'browse-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'add-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'read-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'edit-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'delete-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'force-delete-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'restore-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'import-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'export-user', 'guard_name' => 'web' ];
        
        foreach($permissions AS $permission){
            Permission::firstOrCreate($permission);
        }
    }
}
