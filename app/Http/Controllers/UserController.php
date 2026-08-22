<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Store a newly created user (Super Admin only).
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if (!Gate::allows('create-user')) {
            abort(403, 'Unauthorized: Only Super Admins can physically create new users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:50',
            'password' => 'required|string|min:8',
            'is_super_admin' => 'nullable|boolean',
            'secondary_emails' => 'nullable|array',
            'secondary_emails.*' => 'email',
        ]);

        $emails = [$validated['email']];
        if (!empty($validated['secondary_emails'])) {
            foreach ($validated['secondary_emails'] as $secEmail) {
                if (!empty($secEmail) && !in_array($secEmail, $emails)) {
                    $emails[] = $secEmail;
                }
            }
        }

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'emails' => $emails,
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_super_admin' => !empty($validated['is_super_admin']),
        ]);

        $user->logActivity('created_user', "Physically created user account '{$newUser->name}' ({$newUser->email})", $newUser);

        return redirect()->back()->with('success', "User account '{$newUser->name}' created successfully.");
    }

    /**
     * Update user details and password (Super Admin only).
     */
    public function update(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized: Only Super Admins can update user account details.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8',
            'is_super_admin' => 'nullable|boolean',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->is_super_admin = !empty($validated['is_super_admin']);

        // Maintain primary email in emails array
        $emails = is_array($user->emails) ? $user->emails : [$user->email];
        if (!in_array($validated['email'], $emails)) {
            array_unshift($emails, $validated['email']);
        } else {
            $emails = array_diff($emails, [$validated['email']]);
            array_unshift($emails, $validated['email']);
        }
        $user->emails = array_values($emails);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $currentUser->logActivity('updated_user', "Updated user account details and credentials for '{$user->name}'", $user);

        return redirect()->back()->with('success', "User account '{$user->name}' updated successfully.");
    }

    /**
     * Soft delete a user account (Super Admin & Org Admin).
     */
    public function destroy(User $user)
    {
        /** @var User $currentUser */
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own active user account.');
        }

        $canDelete = false;

        if ($currentUser->isSuperAdmin()) {
            $canDelete = true;
        } else {
            // Check if current user is org_admin in any org where target user belongs
            $adminOrgIds = $currentUser->organizations()
                ->wherePivot('role', 'org_admin')
                ->pluck('organizations.id')
                ->toArray();

            if (!empty($adminOrgIds) && !$user->isSuperAdmin()) {
                $targetInOrg = $user->organizations()
                    ->whereIn('organizations.id', $adminOrgIds)
                    ->exists();

                if ($targetInOrg) {
                    $canDelete = true;
                }
            }
        }

        if (!$canDelete) {
            abort(403, 'Unauthorized to soft-delete this user.');
        }

        $userName = $user->name;
        $user->delete();

        $currentUser->logActivity('deleted_user', "Soft-deleted user account '{$userName}'", $user);

        return redirect()->back()->with('success', "User '{$userName}' soft-deleted successfully.");
    }
}
