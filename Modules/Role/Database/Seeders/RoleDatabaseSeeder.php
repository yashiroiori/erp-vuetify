<?php

namespace Modules\Role\Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules['catalog'] = [
            'module' => ['name' => 'Sistema', 'slug' => 'system', 'icon' => 'settings', 'order' => 10, 'header' => 1, 'core' => 0, 'installed' => 0],
            'children' => [
                ['name' => 'Roles', 'slug' => 'role', 'icon' => 'vpn_key', 'order' => 1, 'header' => 0, 'core' => 1, 'installed' => 0 ],
            ],
        ];
        foreach($modules AS $module){
            $module['module']['parent_id'] = null;
            $tmp = Module::firstOrCreate($module['module']);
            if(count($module['children']) > 0){
                foreach($module['children'] AS $children){
                    $tmp->children()->firstOrCreate($children);
                }
            }
        }
        $this->call(PermissionTableSeeder::class);
    }
}
