<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class CountDateRequest extends FormRequest
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
        return Arr::accessible($this->query('date')) ? [
            'dates' => 'required|array',
            'dates.*' => 'date_format:Y-m-d',
        ] :
        ['date' => 'required|date_format:Y-m-d'];
    }
}
