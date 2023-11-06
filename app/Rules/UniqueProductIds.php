<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Rules
 * @package  App\Rules
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * MyClass Class Doc Comment
 *
 * This rule deals with validation of unique products
 *
 * @phpcs
 * php version 8.1
 *
 * @category Rules
 * @package  App\Rules
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class UniqueProductIds implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute The name of the attribute being validated.
     * @param mixed  $value     The value of the attribute being validated.
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $productIds = collect($value)->pluck('product_id');

        return $productIds->count() === $productIds->unique()->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute[product_id] não podem ser repetidos.';
    }
}
