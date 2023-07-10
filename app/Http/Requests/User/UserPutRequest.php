<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserPutRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return !empty($this->oldPassword) ? [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->route('user')->id,
            'password' => 'required|min:6|confirmed',
            'oldPassword' => 'required|min:6',
            'image' => 'nullable|image|mimes:png,jpg,jpeg'
        ] : [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->route('user')->id,
            'image' => 'nullable|image|mimes:png,jpg,jpeg'
        ];
    }
}
