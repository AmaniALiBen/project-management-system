<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
        $start_date=$this->input('start_date')??$this->project->start_date->toDateString();
       
        $rules= [
            'name'=>'sometimes|string|max:255',
            'description'=>'nullable|string',
            'status'=>'sometimes|in:planned,active,on_hold,completed,cancelled',
            'start_date' => 'sometimes|date',
            'due_date' => 'nullable|date|after_or_equal:'.$start_date,
        ];

        if ($this->user()->role === 'admin') {
            $rules['manager_id'] = 'sometimes|exists:users,id,role,manager';
        }
        return $rules;
    }
}
