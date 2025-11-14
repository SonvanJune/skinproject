<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\PermissionService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    // Dependency injection of role and permission services
    protected $roleService;
    protected $permissionService;
    protected $userService;

    public function __construct(RoleService $roleService, PermissionService $permissionService, UserService $userService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $page = $request->query('page');
        $per_page = $request->query('per_page');

        if (!$page || !is_numeric($page) || $page < 1) {
            $page = 1;
        }

        if (!$per_page || !is_numeric($per_page) || $per_page < 1) {
            $per_page = RoleService::PER_PAGE;
        }

        $request->merge(["page" => $page, "per_page" => $per_page]);

        $paginatedDTO = $this->roleService->readRoleList($request);

        if (is_string($paginatedDTO)) {
            return redirect()->route('admin.index')->with('error', $paginatedDTO);
        }

        $permissions = $this->permissionService->readAllPermissions($request);

        return view('admin.roles.index', compact('paginatedDTO', 'permissions'));
    }

    /**
     * Display a form to create new role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $permissions = $this->permissionService->readAllPermissions($request);

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $createRoleDTO = $this->roleService->createRole($request);

        if (is_string($createRoleDTO)) {
            return back()->with('error', $createRoleDTO);
        }

        return redirect()->route('admin.roles')->with('success', 'Add new role successfully!');
    }

    /**
     * Display a form to create new edit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $id, bool $duplicated = false)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $role  = Role::where('role_id', $id)->first();

        if (!$role) {
            return back()->with('error', 'Role not found');
        }

        $permissions = $this->permissionService->readAllPermissions($request);

        return view('admin.roles.edit', compact('role', 'permissions', 'duplicated'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, bool $duplicated = false)
    {
        if ($duplicated) {
            return $this->store($request);
        }

        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $updateRoleDTO = $this->roleService->updateRole($request);

        if (is_string($updateRoleDTO)) {
            return back()->with('error', $updateRoleDTO);
        }

        return redirect()->route('admin.roles')->with('success', 'Update role successfully!');
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $id)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(["role_id" => $id]);

        $deleteRoleDTO = $this->roleService->deleteRole($request);

        if (is_string($deleteRoleDTO)) {
            return redirect()->route('admin.roles')->with('error', $deleteRoleDTO);
        }

        return redirect()->route('admin.roles')->with('success', 'Delete successfully!');
    }
}
