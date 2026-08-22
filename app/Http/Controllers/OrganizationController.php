<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the organizations scoped by visibility.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $organizations = Organization::withCount(['projects', 'users'])->with(['projects', 'users'])->get();
        } else {
            // Org Admins see their assigned orgs
            // Members see orgs where they belong OR have an assigned project
            $orgIdsFromPivot = $user->organizations()->pluck('organizations.id');
            $orgIdsFromProjects = Project::whereHas('users', fn($q) => $q->where('users.id', $user->id))->pluck('organization_id');
            $allOrgIds = $orgIdsFromPivot->merge($orgIdsFromProjects)->unique();

            $organizations = Organization::whereIn('id', $allOrgIds)
                ->withCount(['projects', 'users'])
                ->with(['projects', 'users'])
                ->get();
        }

        $allUsers = User::orderBy('name')->get();

        return view('organizations.index', compact('organizations', 'user', 'allUsers'));
    }

    /**
     * Store a newly created organization (Super Admin only).
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Only Super Admins can create new organizations.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color_code' => 'required|string|max:7',
        ]);

        $org = Organization::create($validated);
        $user->logActivity('created', "Created organization {$org->name}", $org);

        return redirect()->route('organizations.index')->with('success', 'Organization created successfully.');
    }

    /**
     * Update specified organization details.
     */
    public function update(Request $request, Organization $organization)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('update', $organization)) {
            abort(403, 'Unauthorized to update this organization.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color_code' => 'required|string|max:7',
        ]);

        $organization->update($validated);
        $user->logActivity('updated', "Updated organization details for {$organization->name}", $organization);

        return redirect()->back()->with('success', "Organization '{$organization->name}' updated successfully.");
    }

    /**
     * Display the specified organization.
     */
    public function show(Organization $organization)
    {
        $user = auth()->user();
        if ($user && !Gate::allows('view', $organization)) {
            abort(403, 'Unauthorized to view this organization.');
        }

        $organization->load(['users', 'projects.users']);
        $allUsers = User::orderBy('name')->get();

        return view('organizations.show', compact('organization', 'allUsers', 'user'));
    }

    /**
     * Add existing user to organization with role & position.
     */
    public function addMember(Request $request, Organization $organization)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('manageMembers', $organization)) {
            abort(403, 'Unauthorized to manage members in this organization.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:org_admin,member',
            'position' => 'nullable|string|max:255',
        ]);

        $targetUser = User::findOrFail($validated['user_id']);

        // Sync/Attach with pivot role and position
        $organization->users()->syncWithoutDetaching([
            $targetUser->id => [
                'role' => $validated['role'],
                'position' => $validated['position'] ?? 'Member',
            ]
        ]);

        $user->logActivity('assigned_role', "Assigned {$targetUser->name} as {$validated['role']} in {$organization->name}", $organization, [
            'target_user_id' => $targetUser->id,
            'role' => $validated['role'],
            'position' => $validated['position'] ?? 'Member',
        ]);

        return redirect()->back()->with('success', "{$targetUser->name} added to organization successfully.");
    }

    /**
     * Remove user from organization.
     */
    public function removeMember(Organization $organization, User $targetUser)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('manageMembers', $organization)) {
            abort(403, 'Unauthorized to remove members from this organization.');
        }

        $organization->users()->detach($targetUser->id);
        $user->logActivity('removed_member', "Removed {$targetUser->name} from {$organization->name}", $organization);

        return redirect()->back()->with('success', "Member removed from organization.");
    }

    /**
     * Soft delete organization.
     */
    public function destroy(Organization $organization)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('delete', $organization)) {
            abort(403, 'Unauthorized to delete this organization.');
        }

        $orgName = $organization->name;
        $organization->delete();
        $user->logActivity('deleted', "Soft-deleted organization {$orgName}", $organization);

        return redirect()->route('organizations.index')->with('success', "Organization {$orgName} soft-deleted.");
    }
}
