<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class SubAdminController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the sub-admins.
     *
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
            $per_page = UserService::PER_PAGE;
        }

        $request->merge(["page" => $page, "per_page" => $per_page]);

        $subAdminRole = $this->roleService->getSystemRole($request, RoleService::SUB_ADMIN_ROLE);
        $roles = $this->roleService->readRawRoleList($request);

        if (is_string($subAdminRole)) {
            return redirect()->route('admin.subadmins')->with('error', $subAdminRole);
        }

        $request->merge(["role_id" => $subAdminRole->role_id]);

        $paginatedDTO = $this->userService->readUsersByRole($request);

        if (is_string($paginatedDTO)) {
            return redirect()->route('admin.index')->with('error', $paginatedDTO);
        }

        $subadmins = [];
        foreach ($paginatedDTO->data as $index => $user) {
            $newUser = new User();
            $newUser = $newUser->fill((array)$user);
            $decryptedUser = $this->userService->decryptUser($newUser);
            $paginatedDTO->data[$index] = $decryptedUser;
        }

        $activeStatus = UserService::ACTIVE_STATUS;
        $inActiveStatus = UserService::INACTIVE_STATUS;
        $deletedStatus = UserService::DELETED_STATUS;

        $subAdminRoleName = RoleService::SUB_ADMIN_ROLE;

        return view(
            'admin.subadmins.index',
            compact('paginatedDTO', 'roles', 'activeStatus', 'inActiveStatus', 'deletedStatus', 'subAdminRoleName')
        );
    }

    /**
     * Form to create a new subadmin.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $roles = $this->roleService->readRawRoleList($request);

        return view(
            'admin.subadmins.create',
            compact('roles')
        );
    }

    /**
     * Store a newly created sub-admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $registeredUserDTO = $this->userService->registerSubAdmin($request);

        if (parent::checkIsString($registeredUserDTO)) {
            if ($registeredUserDTO === UserService::WARNING_RESTORE_STATUS) {
                $restored_email = $request->input('email');
                return redirect()->route('admin.subadmins')->with('restored_warning', $registeredUserDTO)->with('restored_email', $restored_email);
            }

            return back()->with('error', $registeredUserDTO);
        }

        $subAdminRole = $this->roleService->getSystemRole($request, RoleService::SUB_ADMIN_ROLE);
        $adminRole = $this->roleService->getSystemRole($request, RoleService::ADMIN_ROLE);
        $userRole = $this->roleService->getSystemRole($request, RoleService::USER_ROLE);

        if (is_string($subAdminRole) || is_string($adminRole) || is_string($userRole)) {
            return back()->with('error', 'System found error with roles');
        }

        $roleList = $request->input('list_role', []);
        $roleList[] = $subAdminRole->role_id;
        $request->merge(['list_role' => $roleList]);
        $request->merge(['sub_user_id' => $registeredUserDTO->user_id]);

        if (!in_array($subAdminRole->role_id, $request->input("list_role"))) {
            return back()->with('error', "Sub-admin must not lack the '" . RoleService::SUB_ADMIN_ROLE . "' role");
        }

        if (in_array($adminRole->role_id, $request->input("list_role"))) {
            return back()->with('error', "Sub-admin cannot possess the '" . RoleService::ADMIN_ROLE . "' role");
        }

        if (in_array($userRole->role_id, $request->input("list_role"))) {
            return back()->with('error', "Sub-admin cannot possess the '" . RoleService::USER_ROLE . "' role");
        }

        $updateSubAdminDTO = $this->userService->updateUserRoles($request);

        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);
        $subAdminFullName =
            $this->userService->decrypt_with_key($updateSubAdminDTO->user_first_name, $key) .
            ' ' .
            $this->userService->decrypt_with_key($updateSubAdminDTO->user_last_name, $key);

        if (parent::checkIsString($updateSubAdminDTO)) {
            return redirect()->route('admin.subadmins')
                ->with(
                    'warning',
                    'Add new Sub-admin \'' . $subAdminFullName . '\' successfully but system still found warning: ' . $updateSubAdminDTO
                );
        }

        return redirect()->route('admin.subadmins')
            ->with('success', 'Add new Sub-admin \'' . $subAdminFullName . '\' successfully');
    }

    /**
     * Form to edit a new subadmin.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $id)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $subadmin = User::where('user_id', $id)->first();

        if (!$subadmin) {
            back()->with('error', 'User not found');
        }

        $subadmin = $this->userService->decryptUser($subadmin);

        $roles = $this->roleService->readRawRoleList($request);

        return view(
            'admin.subadmins.edit',
            compact('roles', 'subadmin')
        );
    }

    /**
     * Update the specified sub-admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        try {
            $subAdminRole = $this->roleService->getSystemRole($request, RoleService::SUB_ADMIN_ROLE);
            $adminRole = $this->roleService->getSystemRole($request, RoleService::ADMIN_ROLE);
            $userRole = $this->roleService->getSystemRole($request, RoleService::USER_ROLE);

            if (is_string($subAdminRole) || is_string($adminRole) || is_string($userRole)) {
                return redirect()->route('admin.subadmins')->with('error', 'System found error with roles');
            }

            $roleList = $request->input('list_role', []);
            $roleList[] = $subAdminRole->role_id;
            $request->merge(['list_role' => $roleList]);

            if (!in_array($subAdminRole->role_id, $request->input("list_role"))) {
                return redirect()->route('admin.subadmins')->with('error', "Sub-admin must not lack the '" . RoleService::SUB_ADMIN_ROLE . "' role");
            }

            if (in_array($adminRole->role_id, $request->input("list_role"))) {
                return redirect()->route('admin.subadmins')->with('error', "Sub-admin cannot possess the '" . RoleService::ADMIN_ROLE . "' role");
            }

            if (in_array($userRole->role_id, $request->input("list_role"))) {
                return redirect()->route('admin.subadmins')->with('error', "Sub-admin cannot possess the '" . RoleService::USER_ROLE . "' role");
            }
        } catch (Exception $e) {
            return redirect()->route('admin.subadmins')->with('error', "Invalid data to update sub-admin");
        }

        $updateSubAdminDTO = $this->userService->updateUserRoles($request);

        if (is_string($updateSubAdminDTO)) {
            return redirect()->route('admin.subadmins')->with('error', $updateSubAdminDTO);
        }

        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);
        $subAdminFullName =
            $this->userService->decrypt_with_key($updateSubAdminDTO->user_first_name, $key) .
            ' ' .
            $this->userService->decrypt_with_key($updateSubAdminDTO->user_last_name, $key);

        return redirect()->route('admin.subadmins')->with('success', 'Update Sub-admin \'' . $subAdminFullName . '\' successfully!');
    }

    /**
     * Remove the specified sub-admin from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $id)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(["user_id" => $id]);

        $deletedUserDTO = $this->userService->deleteUser($request);

        if (is_string($deletedUserDTO)) {
            return redirect()->route('admin.subadmins')->with('error', $deletedUserDTO);
        }

        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);
        $subAdminFullName =
            $this->userService->decrypt_with_key($deletedUserDTO->user_first_name, $key) .
            ' ' .
            $this->userService->decrypt_with_key($deletedUserDTO->user_last_name, $key);

        return redirect()->route('admin.subadmins')->with('success', 'Delete Sub-admin \'' . $subAdminFullName . '\' successfully!');
    }

    /**
     * Activate the specified sub-admin.
     *
     * @param  string  $id
     * @param  int  $status: the current status of the user
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request, string $id, int $status)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        if ($status !== UserService::INACTIVE_STATUS && $status !== UserService::ACTIVE_STATUS) {
            return redirect()->route('admin.subadmins')->with('error', 'Invalid status to active/inactive sub-admin');
        }

        $updateStatus = $status === UserService::ACTIVE_STATUS ? UserService::INACTIVE_STATUS : UserService::ACTIVE_STATUS;
        $request->merge(["user_id" => $id, "user_status" => $updateStatus]);

        $updateUserDTO = $this->userService->updateUserInformation($request);

        if (is_string($updateUserDTO)) {
            return redirect()->route('admin.subadmins')->with('error', $updateUserDTO);
        }

        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);
        $subAdminFullName =
            $this->userService->decrypt_with_key($updateUserDTO->user_first_name, $key) .
            ' ' .
            $this->userService->decrypt_with_key($updateUserDTO->user_last_name, $key);

        return redirect()->route('admin.subadmins')
            ->with(
                'success',
                ($status === UserService::ACTIVE_STATUS ? 'Inactive' : 'Active') .
                    ' Sub-admin \'' . $subAdminFullName . '\' successfully!'
            );
    }

    /**
     * Activate the specified sub-admin.
     *
     * @param  string  $id
     * @param  int  $status: the current status of the user
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, string $email)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(['email' => $email]);

        $restoreUserDTO = $this->userService->restoreSubAdmin($request);

        if (is_string($restoreUserDTO)) {
            return redirect()->route('admin.subadmins')->with('error', $restoreUserDTO);
        }

        $key = config("app.key", UserService::DEFAULT_ENCRYPT_KEY);
        $subAdminFullName =
            $this->userService->decrypt_with_key($restoreUserDTO->user_first_name, $key) .
            ' ' .
            $this->userService->decrypt_with_key($restoreUserDTO->user_last_name, $key);

        return redirect()->route('admin.subadmins')
            ->with(
                'success',
                'Restore Sub-admin \'' . $subAdminFullName . '\' successfully!'
            );
    }
}
