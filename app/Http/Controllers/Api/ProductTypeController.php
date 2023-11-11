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
use App\Http\Requests\ProductTypeApiRequest;
use App\Models\ProductType;
use App\Repositories\ProductTypeRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Arquivo Class ProductTypeController.
 *
 * Controller productType.
 *
 * @phpcs
 * php version 8.1
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
/**
 * @OA\Tag(
 *     name="Product Type",
 *     description="Operations related to the type of product, including creation, consultation, updating, soft deletion and restoration."
 * )
 */
class ProductTypeController extends Controller
{
    /**
     * Create a new ProductTypeController instance.
     *
     * @param ProductTypeRepository $productTypeRepository The product Type.
     */
    public function __construct(private ProductTypeRepository $productTypeRepository)
    {
    }

    /**
     * Display a listing of productType.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with a list of productType
     * @OA\Get(
     *     path="/api/product-types",
     *     description="Retrieve a list of product types.",
     *     tags={"Product Type"},
     *     summary="Index Product Type",
     *     @OA\Response(
     *         response=200,
     *         description="List of Product Types.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *             @OA\Property(
     *                 property="id", type="integer"
     *             ),
     *             @OA\Property(
     *              property="name", type="string"
     *             ),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *      )
     *   ),
     * )
     */
    public function index()
    {
        try {
            return response()->json($this->productTypeRepository->index());
        } catch (\Exception $th) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Store a newly created product type in storage.
     *
     * @param \App\Http\Requests\ProductTypeApiRequest $request The productType data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON with created product type
     *
     * @OA\Post(
     *     path="/api/product-types",
     *     summary="Create a new product type",
     *     tags={"Product Type"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="Laptop"),
     *             @OA\Property(property="description", type="string", example="A portable computer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product type created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Laptop"),
     *             @OA\Property(property="description", type="string", example="A portable computer"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-11-10 17:47:46"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-11-10 17:47:46"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", example=null),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="The description field is required.")),
     *             ),
     *         ),
     *     ),
     * )
     *
     */
    public function store(ProductTypeApiRequest $request)
    {
        return response()
            ->json(
                $this->productTypeRepository
                    ->add($request),
                Response::HTTP_CREATED
            );
    }

    /**
     * Display the specified product type.
     *
     * @param \App\Models\ProductType $productType The productType to display.
     *
     * @return \App\Models\ProductType The specified productType.
     *
     * @OA\Get(
     *     path="/api/product-types/{productTypeID}",
     *     summary="Retrieve a specific product type by ID.",
     *     tags={"Product Type"},
     *     @OA\Parameter(
     *         name="productTypeID",
     *         in="path",
     *         description="ID of the product type",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product type details.",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Type not found."
     *     )
     * )
     */
    public function show(string $productType)
    {
        try {
            $result = ProductType::find($productType);

            if (!$result) {
                return response()->json(['message' => 'Product Type not found.'], 404);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Update the specified productType in storage.
     *
     * @param \App\Http\Requests\ProductTypeApiRequest $request     ProductType data.
     *
     * @param string $productType The ID of the productType to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON with updated productType.
     * @OA\Put(
     *     path="/api/product-types/{productTypeID}",
     *     summary="Update a specific product type by ID.",
     *     tags={"Product Type"},
     *     @OA\Parameter(
     *         name="productTypeID",
     *         in="path",
     *         description="ID of the product type",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="O campo name é obrigatório."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string", example="Pastel Salgado"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Type not found."
     *     )
     * )
     */
    public function update(ProductTypeApiRequest $request, string $productType)
    {
        try {
            $productType = ProductType::find($productType);

            if (!$productType) {
                return response()->json(['message' => 'Product Type not found.'], 404);
            }

            return response()
                ->json(
                    $this->productTypeRepository
                        ->update(
                            $request,
                            $productType
                        )
                );
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Soft delete the specified productType.
     *
     * @param string $productType The ID of the productType to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
     *
     * @OA\Delete(
     *     path="/api/product-types/{productTypeID}",
     *     summary="Delete a specific product type by ID.",
     *     tags={"Product Type"},
     *     @OA\Parameter(
     *         name="productTypeID",
     *         in="path",
     *         description="ID of the product type",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Type not found."
     *     )
     * )
     */
    public function destroy(string $productType)
    {
        try {
            $productTypeExist = ProductType::find($productType);

            if (!$productTypeExist) {
                return response()->json(['message' => 'Product Type not found.'], 404);
            }

            $this->productTypeRepository->destroy($productType);
            return response()->noContent();
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Restore a soft-deleted Product Type.
     *
     * @param string $productType The ID of the productType to restore.
     *
     * @return \Illuminate\Http\JsonResponse
     * @OA\Post(
     *     path="/api/product-types/{productTypeID}/restore",
     *     summary="Restore a soft-deleted product type by ID.",
     *     tags={"Product Type"},
     *     @OA\Parameter(
     *         name="productTypeID",
     *         in="path",
     *         description="ID of the soft-deleted product type",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product type restored successfully.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Type not found.",
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
    public function restore(string $productType)
    {
        try {
            $productExist = ProductType::onlyTrashed()->find($productType);

            if (!$productExist) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            $this->productTypeRepository->restore($productType);
            return response()->json(['message' => 'Product Type restored successfully']);
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }
}
