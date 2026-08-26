<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreWikiRequest extends FormRequest
{
    public function authorize(): bool
    {
        // General auth check, specific model gate logic is in controller or service
        return auth()->check();
    }

    public function rules(): array
    {
        // Depending on what is being stored, we could have different rules,
        // but typically a unified store request for Book, Chapter, or Page.
        if ($this->routeIs('wikis.storeBook')) {
            return [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'owner_kind' => 'required|in:organization,project,private',
                'owner_id' => 'nullable|integer',
                'is_private' => 'nullable|boolean',
            ];
        }

        if ($this->routeIs('wikis.storeChapter')) {
            return [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ];
        }

        if ($this->routeIs('wikis.pages.store')) {
            return [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ];
        }

        return [];
    }
}
