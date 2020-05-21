<?php

namespace Modules\Role\Database\Seeders;

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
        // Role permissions
        $permissions[] = ['name' => 'browse-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'add-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'read-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'edit-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'delete-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'force-delete-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'restore-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'import-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'export-role', 'guard_name' => 'web' ];
        
        foreach($permissions AS $permission){
            Permission::firstOrCreate($permission);
        }
    }
}
