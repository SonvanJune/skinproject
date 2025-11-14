<?php

namespace App\Services;

use App\DTOs\GetPermissionDTO;
use App\DTOs\PaginatedDTO;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Service class responsible for handling permission-related operations.
 * This class provides methods for reading permissions in paginated format
 * and fetching all available permissions from the database.
 */
class PermissionService
{
    // Constant defining the default number of items per page for paginated results
    private const PER_PAGE = 15;

    // Constant defining the default page number when requesting paginated results
    private const DEFAULT_PAGE = 1;

    /**
     * Retrieves a paginated list of permissions from the database.
     * This method validates pagination parameters, fetches the permissions,
     * and returns them in a paginated data transfer object (DTO).
     *
     * @param Request $request - The HTTP request containing optional pagination parameters.
     * @return string|PaginatedDTO - Returns a PaginatedDTO with permission data or an error message on failure.
     */
    public function readPermissionList(Request $request): PaginatedDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'page' => 'nullable|numeric|integer',
                'per_page' => 'nullable|numeric|integer'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', $this::PER_PAGE);
        $page = $request->input('page', $this::DEFAULT_PAGE);
        $skip = ($page - 1) * $perPage;

        $permissions = Permission::skip($skip)->take($perPage)->get();

        if ($permissions->isEmpty()) {
            return 'There is no permission';
        }

        $total = Permission::all()->count();

        return PaginatedDTO::fromData(GetPermissionDTO::fromModels($permissions), $page, $perPage, $total);
    }

    /**
     * Retrieves all permissions from the database without pagination.
     * This method returns a list of all permissions in the form of DTOs.
     *
     * @param Request $request - The HTTP request (though not required for this method).
     * @return array|string - Returns an array of GetPermissionDTO or an error message if no permissions exist.
     */
    public function readAllPermissions(Request $request): array|string
    {
        $permissions = Permission::orderBy('permission_name', 'asc')->get();

        if ($permissions->isEmpty()) {
            return 'There is no permission';
        }

        return GetPermissionDTO::fromModels($permissions);
    }
}
