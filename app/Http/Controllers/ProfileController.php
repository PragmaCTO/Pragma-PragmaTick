<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the settings form for the authenticated user.
     */
    public function edit()
    {
        /** @var User $user */
        $user = auth()->user();

        $managedUsers = collect();
        $isOrgAdmin = false;

        if ($user->isSuperAdmin()) {
            $managedUsers = User::with(['organizations', 'projects'])->orderBy('name')->get();
        } else {
            $adminOrgIds = $user->organizations()
                ->wherePivot('role', 'org_admin')
                ->pluck('organizations.id');

            if ($adminOrgIds->isNotEmpty()) {
                $isOrgAdmin = true;
                $managedUsers = User::whereHas('organizations', fn($q) => $q->whereIn('organizations.id', $adminOrgIds))
                    ->with(['organizations', 'projects'])
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('settings.edit', compact('user', 'managedUsers', 'isOrgAdmin'));
    }

    /**
     * Update member contact info (name, phone_number, emails JSON array).
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
        ]);

        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'];
        $user->emails = array_values(array_unique($validated['emails']));
        $user->email = $user->emails[0]; // Set primary email to first email
        $user->save();

        $user->logActivity('updated_profile', 'Updated personal contact information and email addresses.');

        return redirect()->back()->with('success', 'Profile and contact information updated successfully.');
    }
}
