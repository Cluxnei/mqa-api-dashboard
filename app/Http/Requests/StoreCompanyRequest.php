<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreCompanyRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'cnpj' => 'required|string|max:15|unique:companies',
            'phone' => 'required|string|max:12',
            'email' => 'required|email:rfc,dns',
            'zipcode' => 'required|string|max:9',
            'street' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'address_number' => 'required|string|max:20',
            'city' => 'required|string|max:40',
            'state' => 'required|string|max:30',
            'country' => 'required|string|max:40',
            'latitude' => 'numeric|nullable',
            'longitude' => 'numeric|nullable',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = array_map(static function (array $error) {
                return implode(', ', $error);
            }, array_values((new ValidationException($validator))->errors()));
            throw new HttpResponseException(
                response()->json(['success' => false, 'message' => implode(PHP_EOL, $errors)], 422)
            );
        }
        parent::failedValidation($validator);
    }
}
