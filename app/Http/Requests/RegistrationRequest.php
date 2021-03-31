<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    final public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users',
            'cpf' => 'required|string|unique:users|max:15',
            'password' => 'required|string|max:100',
            'gender' => ['required', 'string', 'max:2', Rule::in('M', 'F', '')],
            'phone' => 'required|string|max:255|unique:users',
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
