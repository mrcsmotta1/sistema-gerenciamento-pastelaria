<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductApiRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Rules\Base64File;
use App\Services\ProductService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class ProductController extends Controller
{
    /**
     * Create a new ProductController instance.
     *
     * @param ProductRepository $productRepository Product repository instance.
     * @param ProductService    $productService    Product service instance.
     * @param Base64File        $base64File        base64File rules instance.
     */
    public function __construct(
        private ProductRepository $productRepository,
        private ProductService $productService,
        private Base64File $base64File
    ) {
    }

    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with a list of products.
     */
    public function index()
    {
        return response()->json($this->productRepository->index());
    }

    /**
     * Store a newly created product in product.
     *
     * @param \App\Http\Requests\ProductApiRequest $request The product data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response.
     */
    public function store(ProductApiRequest $request)
    {
        $data = $request->all();
        $modifiedBase64 = $this->base64File::getModifiedBase64();
        $data['photo'] = $modifiedBase64;

        $product = $this->productRepository->add($data);
        $message = [
            'message' => 'Product created successfully',
            'product' => $product,
        ];

        return response()->json([$message], Response::HTTP_CREATED);
    }

    /**
     * Show a specific product.
     *
     * @param string $id The ID of the product to retrieve.
     *
     * @return Product $result
     */
    public function show(string $id)
    {
        $result = $this->productRepository->show($id);

        return $result;
    }

    /**
     * Update the specified product in storage.
     *
     * @param \App\Http\Requests\ProductApiRequest $request The updated product data.
     * @param \App\Models\Product                  $product The product to update.
     *
     * @return \Illuminate\Http\JsonResponse       A JSON response.
     */
    public function update(ProductApiRequest $request, $product)
    {
        $product = Product::find($product);

        $data = $request->all();

        $data['photo'] = $this->base64File::getModifiedBase64() ?? $data['photo'];

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        return response()
            ->json($this->productRepository->update($product, $data));
    }

    /**
     * Soft delete the specified product.
     *
     * @param int $product $product The ID of the product to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
     */
    public function destroy(int $product)
    {
        $productExist = Product::find($product);

        if (!$productExist) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $this->productRepository->destroy($product);
        return response()->noContent();
    }

    /**
     * Restore a soft-deleted product.
     *
     * @param int $product The ID of the product to restore.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response.
     */
    public function restore(int $product)
    {
        $productExist = Product::withTrashed()->find($product);

        if (!$productExist) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
        $this->productRepository->restore($product);
        return response()->json(['message' => 'Product restored successfully']);
    }
}
