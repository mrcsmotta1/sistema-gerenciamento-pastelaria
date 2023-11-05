<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Arquivo Class ProductTypeApiRequest.
 *
 * Request ProductType.
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
class ProductTypeApiRequest extends FormRequest
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
        ];
    }
}
