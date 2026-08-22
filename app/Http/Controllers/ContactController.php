<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Enforce strict Super Admin restriction across all Contact CRM actions.
     */
    protected function checkSuperAdmin(): User
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Forbidden: The External Contacts CRM module is strictly restricted to Super Admins only.');
        }

        return $user;
    }

    /**
     * Display listing of external CRM contacts (Super Admin only).
     */
    public function index()
    {
        $user = $this->checkSuperAdmin();
        $contacts = Contact::latest()->get();

        return view('contacts.index', compact('contacts', 'user'));
    }

    /**
     * Store a newly created external CRM contact (Super Admin only).
     */
    public function store(Request $request)
    {
        $user = $this->checkSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $contact = Contact::create($validated);
        $user->logActivity('created', "Created external contact '{$contact->name}' ({$contact->company})", $contact);

        return redirect()->route('contacts.index')->with('success', "External contact '{$contact->name}' created.");
    }

    /**
     * Update an existing CRM contact (Super Admin only).
     */
    public function update(Request $request, Contact $contact)
    {
        $user = $this->checkSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $contact->update($validated);
        $user->logActivity('updated', "Updated external contact '{$contact->name}'", $contact);

        return redirect()->route('contacts.index')->with('success', "External contact '{$contact->name}' updated.");
    }

    /**
     * Soft delete contact (Super Admin only).
     */
    public function destroy(Contact $contact)
    {
        $user = $this->checkSuperAdmin();

        $name = $contact->name;
        $contact->delete();
        $user->logActivity('deleted', "Soft-deleted external contact '{$name}'", $contact);

        return redirect()->route('contacts.index')->with('success', "External contact '{$name}' soft-deleted.");
    }
}
