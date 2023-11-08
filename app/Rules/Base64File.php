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
use Illuminate\Support\Facades\File;

/**
 * MyClass Class Doc Comment
 *
 * This Rule handles Base64 file validation operations.
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
class Base64File implements Rule
{
    public $error;
    public static $modifiedBase64 = null;

    /**
     * Determine if the given value passes the validation rule.
     *
     * @param string $attribute The name of the attribute being validated.
     * @param mixed  $value     The value of the attribute to be validated.
     *
     * @return bool True if the validation rule passes, false otherwise.
     */
    public function passes($attribute, $value)
    {
        if (is_string($value)) {
            $img = explode("base64,", $value);
            $base64Data = end($img);

            $decoded = base64_decode($base64Data, true);

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($decoded);

            if (is_string($value) && File::exists(public_path($value))) {
                return true;
            }

            if (is_string($value) && !File::exists(public_path($value)) && ($decoded === false || $decoded === null)) {
                $this->error = 'O campo :attribute não possui um arquivo válido existente.';
                return false;
            }

            if ($decoded === false || $decoded === null) {
                $this->error = 'O campo :attribute não é um base64 válido.';
                return false;
            }

            $extensions = config('myconfig.extensions');
            if (!array_key_exists($mimeType, $extensions)) {
                $this->error = 'O campo :attribute não possui uma extensão do arquivo não é permitida (jpg, png, gif).';
                return false;
            }

            if (strlen($decoded) > 400000) {
                $this->error = 'O campo :attribute possui um arquivo maior que 400 bytes e não é permitido.';
                return false;
            }

            static::$modifiedBase64 = $base64Data;

            return $decoded !== false && $decoded !== null;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error;
    }

    /**
     * Returns the modified string in base64 format or an empty string.
     *
     * @return string|"" The modified string in base64 format, or an empty string if there is no value.
     */
    public static function getModifiedBase64()
    {
        return self::$modifiedBase64;
    }
}
