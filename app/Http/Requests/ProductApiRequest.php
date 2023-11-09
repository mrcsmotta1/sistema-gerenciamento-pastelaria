<?php

namespace App\Http\Requests;

use App\Rules\Base64File;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Arquivo Class ProductApiRequest.
 *
 * Request Product.
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
class ProductApiRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'max:50'],
            'price' => ['required','numeric', 'min:0.01','regex:/^-?(?:\d+|\d*\.\d+)$/',
            function ($attribute, $value, $fail) {
                $numero_formatado = number_format($value, 2, '.', '');
                if ($numero_formatado !== (string)$value) {
                    $fail('O campo ' .  $attribute . ' deve ser um float separado por ponto (ex: 1.00)');
                }
            },],
            'photo' => ['required', new Base64File()],
            'product_type_id' => ['required', 'integer', 'exists:product_types,id']
        ];
    }

    /**
     * Define the validation rules for the fields in the product creation/update form.
     *
     * @return array An associative array containing validation rules for each field.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo :attribute é obrigatório.',
            'name.string' => 'O campo :attribute deve ser uma string.',
            'name.max' => 'O campo :attribute não pode ter mais de 255 caracteres.',
            'price.required' => 'O campo :attribute é obrigatório.',
            'price.min' => 'O campo :attribute deve ser maior que 0.00.',
            'photo.required' => 'O campo :attribute é obrigatório.',
            'product_type_id.integer' => 'O campo :attribute deve ser um valor inteiro.',
            'product_type_id.exists' => 'O campo :attribute não é um tipo de produto válido.',
        ];
    }
}
