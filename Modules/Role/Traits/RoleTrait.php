<?php

namespace Modules\Role\Http\Traits;

use App\Models\Module;
use App\Models\Permission;
use DB;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Role\Http\Requests\RoleStoreRequest;
use Modules\Role\Http\Requests\RoleUpdateRequest;

trait RoleTrait
{
    use AuthorizesRequests;

    public function __construct()
    {
        Inertia::share([
            'roleModelData' => Role::modelData(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', Role::class);
        $modulesPermission = [];
        $permissions_list = ['browse','add','read','edit','delete','force-delete','restore','import','export'];
        foreach(Module::whereHas('children')->get() AS $module){
            $moduleTmp = [
                'id' => $module->id,
                'name' => $module->name,
                'children' => [],
            ];
            $permission_count = 0;
            foreach($module->children AS $child){
                $permission = [];
                foreach($permissions_list AS $permission_list){
                    if(Permission::where('name',$permission_list.'-'.$child->slug)->first()){
                        $permission_count++;
                        $permission[] = [
                            'id' => $permission_list.'-'.$child->slug,
                            'name' => __('admin.actions.'.$permission_list),
                            'icon' => __('admin.icons.'.$permission_list),
                        ];
                    }
                }
                if(count($permission) > 0){
                    $moduleTmp['children'][] = [
                        'id' => $child->id,
                        'name' => $child->name,
                        'icon' => $child->icon,
                        'children' => $permission,
                    ];
                }
            }
            if($permission_count > 0){
                $modulesPermission[] = $moduleTmp;
            }
        }
        return Inertia::render('Module/Role/RoleIndex',[
            'breadcrumbs' => Breadcrumbs::generate(),
            'roles' => Role::withTrashed()->paginate($request->get('per_page') ?? 15)->appends(request()->query()),
            'users' => User::active()->get()->map(function($user){
                return (array)[
                    'value' => $user->id,
                    'text' => $user->full_name,
                ];
            })->toArray(),
            'modulesPermission' => $modulesPermission,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $role = new Role($request->only('name'));
            $role->save();
            $role->syncPermissions($request->get('permission'));
            if($request->has('users') && count($request->get('users')) > 0){
                foreach(User::whereIn('id',$request->get('users'))->get() AS $user){
                    $user->assignRole($role);
                    $user->touch();
                }
            }
            return Redirect::back()->with('success', 'Rol: '.$role->name.' registrado');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);
        if(request()->ajax()){
            $role->users = $role->users()->pluck('id')->toArray();
            $role->permission = $role->permissions()->pluck('name');
            return $role;
        }
        // $role->load('users');
        // $modulesList = Module::whereHas('children')->get();
        // $modules = [];
        // foreach($modulesList AS $module){
        //     $tmp = [
        //         'id' => $module->id,
        //         'label' => $module->name,
        //     ];
        //     $addModule = false;
        //     foreach($module->children AS $children){
        //         $permissions = Permission::where('name','LIKE','%-'.str_replace('.index','',$children->slug))->pluck('name')->toArray();
        //         if(count($permissions) > 0){
        //             $tmp['children'][] = [
        //                 'id' => $children->id,
        //                 'label' => $children->name,
        //                 'slug' => $children->slug,
        //                 'permission' => $permissions,
        //             ];
        //             $addModule  =true;
        //         }
        //     }
        //     if($addModule){
        //         $modules[] = $tmp;
        //     }
        // }
        // echo '<pre>';
        // print_r(Role::orderBy('created_at', 'desc')->cursorPaginate(1));
        // exit;
        $role->permission = $role->permissions()->pluck('name')->toArray();
        return Inertia::render('Package/yashiroiori/aclmanagertabler/RoleShow',[
            'breadcrumbs' => Breadcrumbs::generate(),
            'modules' => $modules,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $this->authorize('update', Role::class);
        $modulesList = Module::whereHas('children')->get();
        $modules = [];
        foreach($modulesList AS $module){
            $tmp = [
                'id' => $module->id,
                'label' => $module->name,
            ];
            $addModule = false;
            foreach($module->children AS $children){
                $tmpChildren = [
                    'id' => $children->id,
                    'label' => $children->name,
                ];
                $add = false;
                foreach(Permission::where('name','LIKE','%-'.str_replace('.index','',$children->slug))->get() AS $permission){
                    $add = true;
                    $tmpChildren['children'][] = [
                        'id' => $permission->name,
                        'label' => __('admin.actions.'.str_replace('-'.$children->slug,'',$permission->name)).': '.$children->name,
                    ];
                }
                if($add){
                    $tmp['children'][] = $tmpChildren;
                    $addModule  =true;
                }
            }
            if($addModule){
                $modules[] = $tmp;
            }
        }
        $users_list = User::active()->get();
        $users = [];
        foreach($users_list AS $user){
            $users[] = [
                'id' => $user->id,
                'text' => $user->full_name.' - '.$user->email,
            ];
        }
        $role->permission = $role->permissions()->pluck('name')->toArray();
        $role->users = $role->users()->pluck('id')->toArray();
        return Inertia::render('Package/yashiroiori/aclmanagertabler/RoleEdit',[
            'breadcrumbs' => Breadcrumbs::generate(),
            'modules' => $modules,
            'users' => $users,
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        return DB::transaction(function () use ($request, $role) {
            $role->fill($request->only('name'));
            $role->save();
            $role->syncPermissions($request->get('permission'));
            if($request->has('users') && count($request->get('users')) > 0){
                foreach(User::whereIn('id',$request->get('users'))->get() AS $user){
                    $user->assignRole($role);
                    $user->touch();
                }
            }
            return Redirect::back()->with('success', 'Rol: '.$role->name.' actualizado');
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param string $role
     * @return Response
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        return DB::transaction(function () use ($role) {
            if(!$role->trashed()){
                $role->delete();
                return Redirect::back()->with('success', 'Rol: '.$role->name.' eliminado');
            } else {
                $role->forceDelete();
                return Redirect::route('role.index')->with('success', 'Rol: '.$role->name.' eliminado permanentemente');
            }
        });
    }

    /**
     * Restore the specified resource from storage.
     * @param string $role
     * @return Response
     */
    public function restore(Role $role)
    {
        // $this->authorize('restore', $role);
        return DB::transaction(function () use ($role) {
            if($role->restore()){
                return Redirect::back()->with('success', 'Rol: '.$role->name.' restaurado');
            }
            return Redirect::back()->with('error', 'Ocurrio un erro al tratar de restaurar el rol: '.$role->name);
        });
    }

    /**
     * Archive the specified resource from storage.
     * @param string $role
     * @return Response
     */
    public function archive(Role $role)
    {
        $this->authorize('update', $role);
        return DB::transaction(function () use ($role) {
            $role->update(['archived' => !$role->archived]);
            return Redirect::back()->with('success', 'Rol: '.$role->name.' '.($role->archived == 1 ? 'archivado' : 'activo'));
        });
    }

    public function batchAction(Request $request)
    {
        $actionText = __('admin.status.'.$request->get('action'));
        Role::unsetEventDispatcher();
        foreach($request->get('items') AS $item){
            $role = Role::withTrashed()->find($item);
            if($role){
                switch($request->get('action')){
                    case 'active':
                        $this->authorize('update', $role);
                        $role->update(['archived' => false]);
                    break;
                    case 'archive':
                        $this->authorize('update', $role);
                        $role->update(['archived' => true]);
                    break;
                    case 'restore':
                        $this->authorize('restore', $role);
                        $role->restore();
                    break;
                    case 'delete':
                        $this->authorize('delete', $role);
                        $role->delete();
                    break;
                    case 'force-delete':
                        $this->authorize('force-delete', $role);
                        $role->forceDelete();
                    break;
                }
            }
        }
        return Redirect::back()->with('success', 'Registros: '.$actionText);
    }
}