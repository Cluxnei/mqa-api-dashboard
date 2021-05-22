<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreFoodRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    final public function rules(): array
    {
        return [
            'name' => 'required|string|max:250|unique:foods',
            'units' => 'required|array|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'units.required' => 'O campo unidades de medida é obrigatório.',
        ];
    }


}
