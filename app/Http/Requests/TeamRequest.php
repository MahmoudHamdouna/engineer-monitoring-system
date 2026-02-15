<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // نأخذ الـ id إذا كانت عملية update
        $teamId = $this->route('team')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z- ]+$/',
                Rule::unique('teams','name')->ignore($teamId),
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[A-Za-z- ]+$/', // يسمح بحروف وأرقام ومسافات وبعض الرموز
            ],

            'leader_id' => [
                'nullable',
                'integer',
                 Rule::exists('users','id')
                ->where(fn($query) => $query->where('role','leader')), // Closure صح
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'This team name is already taken.',
            'specialization.regex' => 'Specialization contains invalid characters.',
            'leader_id.exists' => 'Selected leader must be a valid leader.',
        ];
    }
}
