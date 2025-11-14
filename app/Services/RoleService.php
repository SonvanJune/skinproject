<?php

namespace App\Services;

use App\DTOs\CreateRoleDTO;
use App\DTOs\DeleteRoleDTO;
use App\DTOs\GetRoleDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\UpdateListPermissionOfRole;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoleService
{
    public const PER_PAGE = 10;
    public const DEFAULT_PAGE = 1;

    public const ADMIN_ROLE = "Admin";
    public const USER_ROLE = "User";
    public const SUB_ADMIN_ROLE = "Sub-admin";

    /**
     * Retrieve a specific system role based on the provided role name.
     *
     * @param Request $request The incoming HTTP request.
     * @param string $role The role name to search for (default: "Admin", "User", or "Sub-admin").
     * @return Role|string Returns the Role model instance if found, otherwise returns an error message.
     */
    public function getSystemRole(Request $request, $role = "Admin" | "User" | "Sub-admin"): Role | string
    {
        $role = Role::where("role_name", $role)->first();

        if (!$role) {
            return "Role not found";
        }

        return $role;
    }

    /**
     * Creates a new role based on the incoming request data.
     * @param Request $request - Incoming request containing role data.
     * @return CreateRoleDTO|string - Returns a DTO if successful or an error message string.
     */
    public function createRole(Request $request): CreateRoleDTO|string
    {
        Validator::extend('permission_id_array', function ($attribute, $value) {
            foreach ($value as $item) {
                if (
                    !is_string($item) ||
                    !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $item) ||
                    !Permission::where('permission_id', $item)->exists()
                ) {
                    return false;
                }
            }
            return true;
        });

        $validator = Validator::make($request->all(), [
            'role_name' => 'required|max:255',
            'permissions' => 'permission_id_array'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (
            $request->input("role_name") === RoleService::ADMIN_ROLE ||
            $request->input("role_name") === RoleService::USER_ROLE ||
            $request->input("role_name") === RoleService::SUB_ADMIN_ROLE
        ) {
            return "The role name cannot be the same as the system role";
        }

        if (Role::where("role_name", $request->input("role_name"))->exists()) {
            return "Role already exists";
        }

        DB::beginTransaction();
        try {
            $role = new Role();
            $role->role_id = Str::uuid()->toString();
            $role->role_name = $request->role_name;
            $role->created_at = now();

            $flag = $role->save();

            if (!$flag) {
                return "Cannot create a new role";
            }

            $role->permissions()->attach($request->input("permissions"));

            $flag = $role->save();

            if (!$flag) {
                DB::rollBack();
                return "Cannot attach permissions for this role";
            }

            DB::commit();

            return CreateRoleDTO::fromModel($role);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to create role: ' . $e->getMessage();
        }
    }

    /**
     * Creates a new role based on the incoming request data.
     * @param Request $request - Incoming request containing role data.
     * @return CreateRoleDTO|string - Returns a DTO if successful or an error message string.
     */
    public function updateRole(Request $request): CreateRoleDTO|string
    {
        Validator::extend('permission_id_array', function ($attribute, $value) {
            foreach ($value as $item) {
                if (
                    !is_string($item) ||
                    !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $item) ||
                    !Permission::where('permission_id', $item)->exists()
                ) {
                    return false;
                }
            }
            return true;
        });

        $validator = Validator::make($request->all(), [
            'role_id' => 'required|uuid',
            'role_name' => 'max:255',
            'permissions' => 'nullable|permission_id_array'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!$request->has('role_name')) {
            return "Nothing to update";
        }

        if (
            $request->input("role_name") === RoleService::ADMIN_ROLE ||
            $request->input("role_name") === RoleService::USER_ROLE ||
            $request->input("role_name") === RoleService::SUB_ADMIN_ROLE
        ) {
            return "Cannot update role name as a system role";
        }

        $role = Role::where("role_id", $request->input("role_id"))->first();

        if (!$role) {
            return "Role not found";
        }

        if (
            $request->has('role_name') &&
            ($role->role_name === RoleService::ADMIN_ROLE ||
                $role->role_name === RoleService::USER_ROLE ||
                $role->role_name === RoleService::SUB_ADMIN_ROLE)
        ) {
            return "Cannot update role name of a system role";
        }

        DB::beginTransaction();
        try {
            if ($request->has('role_name')) {
                $role->role_name = $request->role_name;
            }

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->input('permissions'));
            }
            else{
                $role->permissions()->sync([]);
            }

            $role->updated_at = now();
            $flag = $role->save();

            DB::commit();

            if (!$flag) {
                return "The system cannot update the role now, please try later";
            }

            return CreateRoleDTO::fromModel($role);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to update role: ' . $e->getMessage();
        }
    }

    /**
     * Reads and returns a paginated list of roles.
     * @param Request $request - Incoming request containing pagination data.
     * @return PaginatedDTO|string - Returns a paginated DTO or an error message string.
     */
    public function readRoleList(Request $request): PaginatedDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'page' => 'nullable|numeric|integer',
                'per_page' => 'nullable|numeric|integer',
                'key' => 'nullable|string'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', $this::PER_PAGE);
        $page = $request->input('page', $this::DEFAULT_PAGE);
        $skip = ($page - 1) * $perPage;
        $key = $request->input('key', '');

        $roles = Role::where('role_name', 'LIKE', '%' . $key . '%')
            ->skip($skip)
            ->take($perPage)
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $roles->load("permissions");
        $roles->load("users");
        $total = Role::where('role_name', 'LIKE', '%' . $key . '%')->count();

        return PaginatedDTO::fromData(GetRoleDTO::fromModels($roles), $page, $perPage, $total, $key ?? "");
    }

    /**
     * Reads and returns a paginated list of roles.
     * @param Request $request - Incoming request containing pagination data.
     * @return array - Returns a array
     */
    public function readRawRoleList(Request $request): array
    {
        $roles = Role::orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')->get();

        return GetRoleDTO::fromModels($roles);
    }

    /**
     * Deletes a role by its ID.
     * @param Request $request - Incoming request containing role ID.
     * @return DeleteRoleDTO|string - Returns a DTO if successful or an error message string.
     */
    public function deleteRole(Request $request): DeleteRoleDTO|string
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $role = Role::where("role_id", $request->input('role_id'))->first();

        if (!$role) {
            return "Role not found";
        }

        if (
            $role->role_name === RoleService::ADMIN_ROLE ||
            $role->role_name === RoleService::USER_ROLE ||
            $role->role_name === RoleService::SUB_ADMIN_ROLE
        ) {
            return "Cannot delete system role";
        }

        if (count($role->users) > 0) {
            return "Cannot delete role with users attached";
        }

        DB::beginTransaction();
        try {
            $flag = $role->delete();

            DB::commit();

            if (!$flag) {
                return "Cannot delete role";
            }

            return DeleteRoleDTO::fromModel($role);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to delete role: ' . $e->getMessage();
        }
    }

    /**
     * Update the list of permissions for a role.
     *
     * @param Request $request - The request containing role and permission data.
     * @return string|UpdateListPermissionOfRole - Returns an updated DTO or an error message in case of failure.
     */
    public function updateListPermissionOfRole(Request $request): string|UpdateListPermissionOfRole
    {
        Validator::extend('uuid_array', function ($attribute, $value, $parameters, $validator) {
            foreach ($value as $uuid) {
                if (!is_string($uuid) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid)) {
                    return false;
                }
            }
            return true;
        });

        $validator = Validator::make(
            $request->all(),
            [
                'role_id' => 'required|uuid',
                'list_permission' => 'required|uuid_array',
            ],
            [
                'list_permission.uuid_array' => 'Required array of uuids'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!Role::where("role_id", $request->input("role_id"))->exists()) {
            return "Role not found";
        }

        if (count($request->input("list_permission")) < 1) {
            return "No permission of role to update";
        }

        $role = Role::where("role_id", $request->input("role_id"))->first();

        if (!$role) {
            return "Role not found";
        }

        try {
            DB::beginTransaction();

            $role->permissions()->sync($request->input("list_permission"));

            $flag = $role->save();

            DB::commit();

            if (!$flag) {
                return "Cannot update list permission of role";
            }

            return UpdateListPermissionOfRole::fromModel($role);
        } catch (Exception $e) {
            DB::rollBack();
            return "Update list permission of role failed: " . $e->getMessage();
        }
    }
}
