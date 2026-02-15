<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $projectId = $this->route('project')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z- ]+$/',
                Rule::unique('projects', 'name')->ignore($projectId),
            ],

            'description' => ['nullable', 'string', 'max:2000'],

            'team_id' => [
                'required',
                'integer',
                'exists:teams,id',
            ],

            'system_id' => [
                'nullable',
                'integer',
                'exists:systems,id',
            ],

            'start_date' => ['nullable', 'date'],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date', 
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.unique' => 'Project name already exists.',
            'team_id.required' => 'Team is required.',
            'team_id.exists' => 'Selected team is invalid.',
            'end_date.after_or_equal' => 'End date must be after start date.',
        ];
    }
}
