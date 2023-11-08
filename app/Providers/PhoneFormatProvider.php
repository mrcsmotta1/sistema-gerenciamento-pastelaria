<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Providers
 * @package  App\Providers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Providers;

use Faker\Provider\Base;

/**
 * Arquivo Class PhoneFormatProvider.
 *
 * Providers Customer.
 *
 * @phpcs
 * php version 8.1
 *
 * @category Providers
 * @package  App\Providers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class PhoneFormatProvider extends Base
{
    /**
     * Generate a random phone number with a specific format.
     *
     * @return string A random phone number with the specified format.
     */
    public function phoneNumberWithFormat()
    {
        $formats = [
            '(%%)9 %%%%-%%%%',
            '(%%)9%%%-%%%%',
            '(%%)%%%%-%%%%',
        ];

        $format = $this->randomElement($formats);

        return $this->numerify($format);
    }
}
