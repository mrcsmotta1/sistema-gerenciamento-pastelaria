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
     */
    public function index()
    {
        return response()->json($this->productTypeRepository->index());
    }

    /**
     * Store a newly created product type in storage.
     *
     * @param \App\Http\Requests\ProductTypeApiRequest $request The productType data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON with created product type
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
     * Display the specified productType.
     *
     * @param \App\Models\ProductType $productType The productType to display.
     *
     * @return \App\Models\ProductType The specified productType.
     */
    public function show(string $productType)
    {
        $result = ProductType::find($productType);

        if (!$result) {
            return response()->json(['message' => 'Product Type not found.'], 404);
        }

        return response()->json($result);
    }

    /**
     * Update the specified productType in storage.
     *
     * @param \App\Http\Requests\ProductTypeApiRequest $request     ProductType data.
     * @param $productType productTypeId.
     *
     * @return \Illuminate\Http\JsonResponse A JSON with updated productType.
     */
    public function update(ProductTypeApiRequest $request, $productType)
    {
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
    }

    /**
     * Soft delete the specified productType.
     *
     * @param int $productType The ID of the productType to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
     */
    public function destroy(string $productType)
    {
        $productTypeExist = ProductType::find($productType);

        if (!$productTypeExist) {
            return response()->json(['message' => 'Product Type not found.'], 404);
        }

        $this->productTypeRepository->destroy($productType);
        return response()->noContent();
    }

    /**
     * Restore a soft-deleted Product Type.
     *
     * @param int $productType The ID of the productType to restore.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(int $productType)
    {
        $this->productTypeRepository->restore($productType);
        return response()->json(['message' => 'Product Type restored successfully']);
    }
}
