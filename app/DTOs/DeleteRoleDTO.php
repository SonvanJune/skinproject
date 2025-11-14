<?php

namespace App\DTOs;

use App\Models\Role;

class DeleteRoleDTO
{
    // Properties representing the role's details
    public string $role_id;
    public string $role_name;
    public string $created_at;
    public string $updated_at;

    /**
     * Constructor to initialize the DeleteRoleDTO object with role details.
     * @param string $role_id - Unique identifier for the role.
     * @param string $role_name - Name of the role.
     * @param string $created_at - Timestamp of when the role was created.
     * @param string $updated_at - Timestamp of when the role was last updated.
     */
    public function __construct(
        string $role_id,
        string $role_name,
        string $created_at,
        string $updated_at
    ) {
        $this->role_id = $role_id;
        $this->role_name = $role_name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * Static method to create a DeleteRoleDTO object from a Role model instance.
     * @param Role $role - The Role model object.
     * @return self|string - Returns a new DeleteRoleDTO instance populated with role details.
     */
    public static function fromModel(Role $role): self|string
    {
        return new self(
            $role->role_id,
            $role->role_name,
            $role->created_at,
            $role->updated_at
        );
    }
}
