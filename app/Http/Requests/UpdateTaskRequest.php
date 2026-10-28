<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
            'title'=>'sometimes|string|max:255',
            'description'=>'nullable|string',
            'assigned_to'=>['nullable','integer','exists:users,id,role,employee',
            Rule::exists('project_user','user_id')->where('project_id',$this->task->project_id)],
            'status'=>'sometimes|in:to_do,in_progress,in_review,done',
            'priority'=>'sometimes|in:low,medium,high,urgent',
            'due_date' => 'nullable|date'
        ];
    }
}
