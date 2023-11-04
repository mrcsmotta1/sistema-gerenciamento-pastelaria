<?php

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
            'name.required' => 'O campo nome é obrigatório.',
            'name.min' => 'O campo nome deve ter no mínimo :min caracteres.',
            'name.max' => 'O campo nome não deve ter mais de :max caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este email já está em uso.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.string' => 'O campo telefone deve ser uma string.',
            'phone.regex' => 'O formato do telefone é inválido. Use (XX)9 XXXX-XXXX, (XX)9XXXX-XXXX ou (XX)XXXX-XXXX.',
            'date_of_birth.required' => 'O campo data de nascimento é obrigatório.',
            'date_of_birth.date_format' => 'O campo data de nascimento deve estar no formato YYYY-MM-DD.',
            'address.required' => 'O campo endereço é obrigatório.',
            'address.min' => 'O campo endereço deve ter no mínimo :min caracteres.',
            'address.max' => 'O campo endereço não deve ter mais de :max caracteres.',
            'complement.required' => 'O campo complemento é obrigatório.',
            'complement.min' => 'O campo complemento deve ter no mínimo :min caracteres.',
            'complement.max' => 'O campo complemento não deve ter mais de :max caracteres.',
            'neighborhood.required' => 'O campo bairro é obrigatório.',
            'neighborhood.min' => 'O campo bairro deve ter no mínimo :min caracteres.',
            'neighborhood.max' => 'O campo bairro não deve ter mais de :max caracteres.',
            'zipcode.*' => 'O campo CEP é obrigatório.',
            'zipcode.regex' => 'O formato do CEP é inválido. Use XXXXX-XXX.',
        ];
    }
}
