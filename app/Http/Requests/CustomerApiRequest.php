<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Request
 * @package  App\Http\Requests
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Arquivo Class CustomerApiRequest.
 *
 * Request Customer.
 *
 * @phpcs
 * php version 8.1
 *
 * @category Request
 * @package  App\Http\Requests
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class CustomerApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string>
     */
    public function rules(): array
    {

        return [
            "name" => ['required', 'min:3', 'max:50'],
            "email" => ['required', 'email', Rule::unique('customers', 'email')->ignore($this->id)],
            'phone' => ['required', 'string', 'regex:/^\(\d{2}\)(9?\s?\d{4}-\d{4}|\d{4}-\d{4})$/'],
            "date_of_birth" => ['required', 'date_format:Y-m-d'],
            "address" => ['required', 'min:3', 'max:50'],
            "complement" => ['required', 'min:3', 'max:50'],
            "neighborhood" => ['required', 'min:3', 'max:50'],
            "zipcode" => ['required', 'regex:/^\d{5}-\d{3}$/'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo :attribute é obrigatório.',
            'name.min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'name.max' => 'O campo :attribute não deve ter mais de :max caracteres.',
            'email.required' => 'O campo :attribute é obrigatório.',
            'email.email' => 'O campo :attribute  deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este :attribute já está em uso.',
            'phone.required' => 'O campo :attribute é obrigatório.',
            'phone.string' => 'O campo :attribute deve ser uma string.',
            'phone.regex' => 'O formato do campo :attribute é inválido. Use (XX)9 XXXX-XXXX, (XX)9XXXX-XXXX ou (XX)XXXX-XXXX.',
            'date_of_birth.required' => 'O campo :attribute é obrigatório.',
            'date_of_birth.date_format' => 'O campo data de :attirube deve estar no formato YYYY-MM-DD.',
            'address.required' => 'O campo :attribute é obrigatório.',
            'address.min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'address.max' => 'O campo :attribute não deve ter mais de :max caracteres.',
            'complement.required' => 'O campo :attribute é obrigatório.',
            'complement.min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'complement.max' => 'O campo :attribute não deve ter mais de :max caracteres.',
            'neighborhood.required' => 'O campo :attribute é obrigatório.',
            'neighborhood.min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'neighborhood.max' => 'O campo :attribute não deve ter mais de :max caracteres.',
            'zipcode.*' => 'O campo :attribute é obrigatório.',
            'zipcode.regex' => 'O formato do campo :attribute é inválido. Use XXXXX-XXX.',
        ];
    }
}
