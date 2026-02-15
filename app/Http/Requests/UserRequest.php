<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => 'required|string|max:255|regex:/^[A-Za-z- ]+$/',
            'email' => [
                'required',
                'email',
                Rule::unique('users','email')->ignore($userId),
            ],
            'role' => ['required', Rule::in(['admin','leader','engineer'])],
            'team_id' => 'nullable|exists:teams,id',
            'password' => $userId
                ? 'nullable|string|min:8|confirmed'
                : 'nullable|string|min:8',
        ];
    }

    public function validatedData(): array
    {
        $data = $this->validated();

        if (!isset($data['password'])) {
            $data['password'] = Hash::make('password');
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }
}
