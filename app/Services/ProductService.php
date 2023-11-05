<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Services
 * @package  App\Services
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductService
 *
 * @category Services
 * @package  App\Services
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class ProductService
{
    /**
     * Create a new product using the provided data.
     *
     * @param array $data The data required to create the product.
     *
     * @return Product The newly created product.
     */
    public function createProduct(array $data)
    {
        $product = new Product();
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->product_type_id = $data['product_type_id'];

        $base64Image = $data['photo'];
        $imageData = base64_decode($base64Image);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);

        $extensions = config('myconfig.extensions');
        $extension = $extensions[$mimeType];
        $imageName = time() . '.' . $extension;

        $folder = 'img';

        Storage::disk('public')->put("{$folder}/{$imageName}", $imageData, 'public');

        $product->photo = "storage/{$folder}/{$imageName}";

        $product->save();

        return $product;
    }

    /**
     * Create a new Image using the provided data.
     *
     * @param string $img The data required to create the Image.
     *
     * @return string The newly created image.
     */
    public function createImg(string $img)
    {
        $base64Image = $img;
        $imageData = base64_decode($base64Image);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);

        $extensions = config('myconfig.extensions');
        $extension = $extensions[$mimeType];
        $imageName = time() . '.' . $extension;

        $folder = 'img';

        Storage::disk('public')->put("{$folder}/{$imageName}", $imageData, 'public');

        return "storage/{$folder}/{$imageName}";
    }
}
