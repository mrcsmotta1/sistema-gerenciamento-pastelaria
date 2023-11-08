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
 * Arquivo Class StringWithLimitProvider.
 *
 * Providers Product Type.
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
class StringWithLimitProvider extends Base
{
    /**
     * Generate a random string with size limits.
     *
     * This method generates a random string and ensures that its size falls within
     * the limits specified by $minLength and $maxLength. If the generated size
     * is less than $minLength, additional words are concatenated. If it exceeds
     * $maxLength, it is truncated to meet the limit.
     *
     * @param int $minLength The minimum size of the string.
     * @param int $maxLength The maximum size of the string.
     *
     * @return string A random string with size limits applied.
     */
    public function stringWithLimit($minLength, $maxLength)
    {
        $value = $this->generator->word;

        while (strlen($value) < $minLength) {
            $value = $this->generator->word;
        }

        if (strlen($value) > $maxLength) {
            $value = substr($value, 0, $maxLength);
        }

        return $value;
    }
}
