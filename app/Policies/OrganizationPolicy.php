<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view any organizations.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the organization.
     */
    public function view(User $user, Organization $organization): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->roleInOrganization($organization) !== null) {
            return true;
        }

        // Check if user is attached to any project under this organization
        return $user->projects()
                    ->where('organization_id', $organization->id)
                    ->exists();
    }

    /**
     * Determine whether the user can create organizations.
     * Only Super Admins can create organizations.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the organization.
     */
    public function update(User $user, Organization $organization): bool
    {
        return $user->isOrgAdmin($organization);
    }

    /**
     * Determine whether the user can manage members of the organization.
     */
    public function manageMembers(User $user, Organization $organization): bool
    {
        return $user->isOrgAdmin($organization);
    }

    /**
     * Determine whether the user can delete the organization.
     */
    public function delete(User $user, Organization $organization): bool
    {
        return $user->isOrgAdmin($organization);
    }
}
