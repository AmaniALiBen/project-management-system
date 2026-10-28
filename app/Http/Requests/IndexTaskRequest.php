<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search'=>'sometimes|string|max:255',
            'status'=>'sometimes|in:to_do,in_progress,in_review,done',
            'priority'=>'sometimes|in:low,medium,high,urgent',
            'assigned_to'=>'sometimes|int|exists:users,id,role,employee',
            'due_date'=>'sometimes|date',
            'per_page' => 'sometimes|integer|min:1|max:50',
        ];
    }
}
