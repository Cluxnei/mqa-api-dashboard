<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreCompanyItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize(): bool
    {
        return auth()->guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    final public function rules(): array
    {
        return [
            'food_id' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'amount' => 'required|numeric',
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
