<?php

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
     * @param ProductTypeRepository $productTypeRepository The repository for handling product Type data.
     */
    public function __construct(private ProductTypeRepository $productTypeRepository)
    {
    }

    /**
     * Display a listing of productType.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with a list of productType.
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
     * @return \Illuminate\Http\JsonResponse A JSON response with the newly created product type and HTTP status 201 (Created).
     */
    public function store(ProductTypeApiRequest $request)
    {
        return response()->json($this->productTypeRepository->add($request), Response::HTTP_CREATED);
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
            return response()->json(['message' => 'Oroduct Type not found'], 404);
        }

        return response()->json($result);
    }

    /**
     * Update the specified productType in storage.
     *
     * @param \App\Models\ProductType                  $productType The productType to update.
     * @param \App\Http\Requests\ProductTypeApiRequest $request     The updated productType data.
     *
     * @return \Illuminate\Http\JsonResponse          A JSON response with the updated productType.
     */
    public function update(ProductType $productType, ProductTypeApiRequest $request)
    {
        return response()->json($this->productTypeRepository->update($productType, $request));
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
        $this->productTypeRepository->destroy($productType);
        return response()->noContent();
    }

    /**
     * Restore a soft-deleted Product Type.
     *
     * @param int $productType The ID of the productType to restore.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the product Type has been successfully restored.
     */
    public function restore(int $productType)
    {
        $this->productTypeRepository->restore($productType);
        return response()->json(['message' => 'product type restored successfully']);
    }
}
