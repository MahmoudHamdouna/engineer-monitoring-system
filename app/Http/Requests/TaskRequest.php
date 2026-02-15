<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $taskId = $this->route('task')?->id;
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'priority' => ['required', Rule::in(['urgent', 'normal'])],
            'type' => ['required', Rule::in(['development', 'fix', 'review'])],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'review', 'done'])],
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after:start_date',
        ];
    }

    
}
