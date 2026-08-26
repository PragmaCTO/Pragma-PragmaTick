<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWikiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        if ($this->routeIs('wikis.pages.update')) {
            return [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ];
        }

        if ($this->routeIs('wikis.shareBook')) {
            return [
                'user_id' => 'required|exists:users,id',
            ];
        }

        return [];
    }
}
