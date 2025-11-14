<?php

namespace App\DTOs;

use App\Models\OneTimePassword;
use App\Models\Permission;
use Illuminate\Support\Collection;

class GetPermissionDTO
{
    public string $permission_id;
    public string $permission_name;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        string $permission_id,
        string $permission_name,
        string $created_at,
        string $updated_at
    ) {
        $this->permission_id = $permission_id;
        $this->permission_name = $permission_name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * Create a GetPermissionDTO instance from a Permission model.
     *
     * @param Permission $permission - The Permission model instance to convert to a DTO.
     * @return self - The created GetPermissionDTO instance.
     */
    public static function fromModel(Permission $permission): self
    {
        return new self(
            $permission->permission_id,
            $permission->permission_name,
            $permission->created_at,
            $permission->updated_at,
        );
    }

    /**
     * Convert a collection of Permission models into an array of GetPermissionDTO instances.
     *
     * @param Collection $permissions - The collection of Permission models.
     * @return array - An array of GetPermissionDTO instances.
     */
    public static function fromModels(Collection $permissions): array
    {
        $result = [];

        foreach ($permissions as $permission) {
            $result[] = self::fromModel($permission);
        }

        return $result;
    }
}
