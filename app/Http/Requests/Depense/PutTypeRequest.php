<?php

namespace App\Http\Requests\Depense;

use Illuminate\Foundation\Http\FormRequest;

class PutTypeRequest extends FormRequest
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
        return ['nom' => 'required|max:70|unique:type_depenses,nom,' . $this->route('typeDepense')->id];
    }
}