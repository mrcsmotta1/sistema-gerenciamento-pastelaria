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
/**
 * @OA\Tag(
 *     name="Product",
 *     description="Product-related operations, including creation, consultation, updating, soft deletion and restoration."
 * )
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
     *
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Product"},
     *     summary="Get a list of products",
     *     description="Retrieve a list of products.",
     *     @OA\Response(
     *         response=200,
     *         description="List of products.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="product_type_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="price", type="string", example="10.50"),
     *                 @OA\Property(property="photo", type="string", example="storage/img/1699037097.jpg"),
     *                 @OA\Property(property="created_at", type="string"),
     *                 @OA\Property(property="updated_at", type="string"),
     *                 @OA\Property(property="deleted_at", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        try {
            return response()->json($this->productRepository->index());
        } catch (\Exception $th) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }

    }

    /**
     * Store a newly created product in product.
     *
     * @param \App\Http\Requests\ProductApiRequest $request The product data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response.
     *
     *  * @OA\Post(
     *     path="/api/products",
     *     tags={"Product"},
     *     summary="Store a new product",
     *     description="Store a new product.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_type_id", "name", "price", "photo"},
     *             @OA\Property(property="product_type_id", type="integer", description="ID of the product", example=1),
     *             @OA\Property(property="name", type="string", description="Name of the product"),
     *             @OA\Property(property="price", type="string", description="Price of the product", example="10.50"),
     *             @OA\Property(property="photo", type="string", description="Photo URL of the product ou Base64", example="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product created successfully"),
     *             @OA\Property(property="product", type="object",
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="price", type="string", example="10.50"),
     *                 @OA\Property(property="product_type_id", type="integer", example=1),
     *                 @OA\Property(property="photo", type="string", example="storage/img/1699647233.png"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="id", type="integer", example=1),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     ),
     * )
     */
    public function store(ProductApiRequest $request)
    {
        try {
            $data = $request->all();
            $modifiedBase64 = $this->base64File::getModifiedBase64();
            $data['photo'] = $modifiedBase64;

            $product = $this->productRepository->add($data);
            $message = [
                'message' => 'Product created successfully',
                'product' => $product,
            ];

            return response()->json([$message], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Show a specific product.
     *
     * @param string $id The ID of the product to retrieve.
     *
     * @return Product $result
     *
     *  * @OA\Get(
     *     path="/api/products/{productID}",
     *     operationId="getProductById",
     *     summary="Get product by ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="productID",
     *         in="path",
     *         description="ID of the product to retrieve",
     *         required=true,
     *         example=1,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="product_type_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="string", example="10.50"),
     *             @OA\Property(property="photo", type="string", example="storage/img/1699648415.png"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found"),
     *         )
     *     ),
     * )
     */
    public function show(string $id)
    {
        try {
            $result = $this->productRepository->show($id);

            return $result;
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Update the specified product in storage.
     *
     * @param \App\Http\Requests\ProductApiRequest $request The updated product data.
     * @param \App\Models\Product                  $product The product to update.
     *
     * @return \Illuminate\Http\JsonResponse       A JSON response.
     *
     * @OA\Put(
     *     path="/api/products/{productID}",
     *     operationId="updateProduct",
     *     summary="Update an existing product",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="productID",
     *         in="path",
     *         description="ID of the product to update",
     *         required=true,
     *         example=1,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="product_type_id", type="integer", example=1),
     *                 @OA\Property(property="price", type="string", example="10.50"),
     *                 @OA\Property(property="photo", type="string", example="storage/img/1699648415.png"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="product_type_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="string", example="10.50"),
     *             @OA\Property(property="photo", type="string", example="storage/img/1699648415.png"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found"),
     *         )
     *     ),
     * )
     */
    public function update(ProductApiRequest $request, $product)
    {
        try {
            $product = Product::find($product);

            $data = $request->all();

            $data['photo'] = $this->base64File::getModifiedBase64() ?? $data['photo'];

            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            return response()
                ->json($this->productRepository->update($product, $data));
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Soft delete the specified product.
     *
     * @param string $product $product The ID of the product to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
     *
     * @OA\Delete(
     *     path="/api/products/{productID}",
     *     summary="Delete a product by ID",
     *     description="Delete a product by ID (soft delete).",
     *     operationId="productID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="productID",
     *         in="path",
     *         description="ID of the product to delete",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order not found"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         )
     *     ),
     * )
     */
    public function destroy(string $product)
    {
        try {
            $productExist = Product::find($product);

            if (!$productExist) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            $this->productRepository->destroy($product);
            return response()->noContent();
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Restore a soft-deleted product.
     *
     * @param string $product The ID of the product to restore.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response.
     *
     * @OA\Post(
     *     path="/api/products/{productID}/restore",
     *     summary="Restore a soft-deleted product by ID.",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="productID",
     *         in="path",
     *         description="ID of the soft-deleted product",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product restored successfully.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found.",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         )
     *     ),
     * )
     */
    public function restore(string $product)
    {
        try {
            $productExist = Product::onlyTrashed()->find($product);

            if (!$productExist) {
                return response()->json(['message' => 'Product not found.'], 404);
            }
            $this->productRepository->restore($product);
            return response()->json(['message' => 'Product restored successfully']);
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }
}
