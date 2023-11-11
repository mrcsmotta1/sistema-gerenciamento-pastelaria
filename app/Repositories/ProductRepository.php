<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Repository
 * @package  App\Repositories
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Repositories;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class ProductRepository
 *
 * This Repository handles operations related to Product.
 *
 * @phpcs
 * php version 8.1
 *
 * @category Repository
 * @package  App\Repositories
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class ProductRepository
{
    /**
     * Create a new ProductRepository instance.
     *
     * @param ProductService $productService Instância de Services de produto.
     */
    public function __construct(private ProductService $productService)
    {
    }

    /**
     * Adds a new product with the provided data.
     *
     * @param array $data The data of the product to be added.
     *
     * @return Product The newly created product.
     */
    public function add($data): Product
    {
        return DB::transaction(function () use ($data) {
            return $this->productService->createProduct($data);
        });
    }

    /**
     * Update an existing Product.
     *
     * @param Product $product The product to update.
     * @param $request The HTTP request containing updated product data.
     *
     * @return Product The updated product.
     */
    public function update(Product $product, $request): Product
    {
        $isBase64 = base64_encode(base64_decode($request['photo'], true)) === $request['photo'] ? true : false;

        if ($isBase64) {
            $pathImage = $this->productService->createImg($request['photo']);
            $request['photo'] = $pathImage;
        }

        $product->update($request);

        $product->save();

        return $product;
    }

    /**
     * Delete a product by their ID.
     *
     * @param int $product The ID of the product to delete.
     *
     * @return void
     */
    public function destroy(int $product): void
    {
        Product::destroy($product);
    }

    /**
     * Restores a soft-deleted product by its ID.
     *
     * @param int $product The ID of the product to be restored.
     *
     * @return void
     */
    public function restore(int $product): void
    {
        $restore = Product::withTrashed()->where(['id' => $product]);
        $restore->restore();
    }

    /**
     * Select All product.
     *
     * @return Product The updated product.
     */
    public function index(): Collection
    {
        return DB::transaction(function () {
            return Product::query()->orderBy('name')->get();
        });
    }

    /**
     * Display the specified product.
     *
     * @param $product The product ID.
     *
     * @return \App\Models\Product The specified product.
     */
    public function show($product)
    {
        $result = Product::find($product);

        if (!$result) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($result);
    }
}
