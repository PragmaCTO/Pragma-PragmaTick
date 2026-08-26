<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        if ($this->routeIs('projects.store') || $this->routeIs('projects.update')) {
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'abbreviation' => 'required|string|max:10|alpha_dash',
                'start_date' => 'nullable|date',
                'due_date' => 'nullable|date|after_or_equal:start_date',
            ];

            if ($this->routeIs('projects.store')) {
                $rules['organization_id'] = 'required|exists:organizations,id';
            } else {
                $rules['abbreviation'] = 'required|string|max:10';
            }

            return $rules;
        }

        if ($this->routeIs('projects.addMember')) {
            return [
                'user_id' => 'required|exists:users,id',
                'role' => 'required|in:project_admin,member',
                'position' => 'nullable|string|max:255',
            ];
        }

        return [];
    }
}
