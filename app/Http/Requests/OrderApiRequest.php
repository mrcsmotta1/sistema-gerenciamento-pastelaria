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

use App\Models\Order;
use App\Rules\UniqueProductIds;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Order
 *
 * Represents a Order in the system.
 *
 * @category Request
 * @package  App\Http\Requests
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class OrderApiRequest extends FormRequest
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
        $orderId = $this->route('order');

        $rules = [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'products' => ['required', 'array', new UniqueProductIds()],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ];

        if ($this->isMethod('put')) {
            $rules['customer_id'] = [
                'sometimes', function ($attribute, $value, $fail) use ($orderId) {
                    $order = Order::find($orderId);
                    if ($order && $order->customer_id != $value) {
                        $fail('O campo :attribute não pode ser alterado.');
                    }
                }];
        }

        return $rules;
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'O campo :attribute é obrigatório.',
            'customer_id.integer' => 'O campo :attribute deve ser numero inteiro.',
            'customer_id.exists' => 'O campo :attribute deve ser um Cliente valido.',
            'products.array' => 'O campo :attribute deve ser um array.',

            'products.*.product_id.required' => 'O campo :attribute é obrigatório.',
            'products.*.product_id.exists' => 'O campo :attribute não é um produto valido.',

            'products.*.quantity.required' => 'O campo :attribute é obrigatório.',
            'products.*.quantity.integer' => 'O campo :attribute deve ser numero inteiro.',
            'products.*.quantity.min' => 'O campo :attribute precisa ter a quantidade minima de 1.',
            'products.*.quantity.max' => 'O campo :attribute precisa ter a quantidade máxima de 20.',
        ];
    }
}
