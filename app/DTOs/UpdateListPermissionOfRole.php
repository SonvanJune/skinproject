<?php

namespace App\DTOs;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class UpdateListPermissionOfRole
{
    // Properties representing the details of a role and its associated permissions
    public string $role_id; // The unique identifier of the role
    public string $role_name; // The name of the role
    public string $created_at; // Timestamp of when the role was created
    public string $updated_at; // Timestamp of when the role was last updated
    public array $list_permission; // An array of permissions associated with the role

    /**
     * Constructor to initialize the UpdateListPermissionOfRole DTO object.
     *
     * @param string $role_id - The unique identifier for the role.
     * @param string $role_name - The name of the role.
     * @param string $created_at - Timestamp of when the role was created.
     * @param string $updated_at - Timestamp of when the role was last updated.
     * @param Collection $list_permission - A collection of permissions associated with the role.
     */
    public function __construct(
        string $role_id,
        string $role_name,
        string $created_at,
        string $updated_at,
        Collection $list_permission
    ) {
        $this->role_id = $role_id;
        $this->role_name = $role_name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;

        $this->list_permission = GetPermissionDTO::fromModels($list_permission);
    }

    /**
     * Static method to create a DTO object from a Role model instance.
     *
     * @param Role $role - The Role model instance.
     * @return self - Returns an instance of UpdateListPermissionOfRole DTO.
     */
    public static function fromModel(Role $role): self
    {
        return new self(
            $role->role_id,
            $role->role_name,
            $role->created_at,
            $role->updated_at,
            $role->permissions()->get()
        );
    }
}
