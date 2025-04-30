<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasPermission('create', 'roles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,{{$this->role}}',
            'description' => 'required|string|max:255',
            'allow_to_be_assigne' => 'nullable',
        ];

        // return [
        //     'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($this->role)],
        //     'description' => ['required', 'string', 'max:255'],
        //     'allow_to_be_assigne' => ['nullable']
        //     // 'email' => [
        //     //     'required',
        //     //     'email',
        //     //     Rule::unique('users')->ignore($this->user)
        //     // ],
        // ];
    }
}
