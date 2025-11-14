<?php

namespace App\DTOs;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Database\Eloquent\Collection;

class GetRoleDTO
{
    public string $role_id;
    public string $role_name;
    public string $created_at;
    public string $updated_at;
    public array|Collection $permissions;
    public array|Collection $users;
    public bool $editable;

    public function __construct(
        string $role_id,
        string $role_name,
        string $created_at,
        string $updated_at,
        array|Collection $permissions,
        array|Collection $users,
        bool $editable
    ) {
        $this->role_id = $role_id;
        $this->role_name = $role_name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->permissions = $permissions;
        $this->users = $users;
        $this->editable = $editable;
    }

    /**
     * Create a GetRoleDTO instance from a Role model.
     *
     * @param Role $role - The Role model instance to convert to a DTO.
     * @return self - The created GetRoleDTO instance.
     */
    public static function fromModel(Role $role): self
    {
        $editable = (bool) !($role->role_name === RoleService::ADMIN_ROLE ||
            $role->role_name === RoleService::USER_ROLE ||
            $role->role_name === RoleService::SUB_ADMIN_ROLE);
        return new self(
            $role->role_id,
            $role->role_name,
            $role->created_at,
            $role->updated_at,
            $role->permissions()->get(),
            $role->users()->get(),
            $editable
        );
    }

    /**
     * Convert a collection of Role models into an array of GetRoleDTO instances.
     *
     * @param Collection $roles - The collection of Role models.
     * @return array - An array of GetRoleDTO instances.
     */
    public static function fromModels(Collection | array $roles): array
    {
        $result = [];

        foreach ($roles as $role) {
            $result[] = self::fromModel($role);
        }

        return $result;
    }
}
