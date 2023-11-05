<?php

namespace App\Repositories;

use App\Http\Requests\ProductTypeApiRequest;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProductTypeRepository
 *
 * This Repository handles operations related to product type.
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
class ProductTypeRepository
{
    /**
     * Select All ProductType.
     *
     * @return ProductType The updated ProductType.
     */
    public function index(): Collection
    {
        return ProductType::query()->orderBy('name')->get();
    }

    /**
     * Add a new product type.
     *
     * @param ProductTypeApiRequest $request The HTTP request containing ProductType data.
     *
     * @return ProductType The newly created ProductType.
     */
    public function add(ProductTypeApiRequest $request): ProductType
    {
        return ProductType::create($request->all());
    }

    /**
     * Update an existing ProductType.
     *
     * @param ProductType           $ProductType The ProductType to update.
     * @param ProductTypeApiRequest $request     The HTTP request containing updated ProductType data.
     *
     * @return ProductType The updated ProductType.
     */
    public function update(ProductType $ProductType, ProductTypeApiRequest $request): ProductType
    {
        $ProductType = $ProductType->fill($request->all());
        $ProductType->save();

        return $ProductType;
    }

    /**
     * Delete a ProductType by their ID.
     *
     * @param int $ProductType The ID of the ProductType to delete.
     *
     * @return void
     */
    public function destroy(int $ProductType): void
    {
        ProductType::destroy($ProductType);
    }

    /**
     * Restore a soft-deleted Product Type.
     *
     * @param int $productType The ID of the productType to restore.
     *
     * @return void
     */
    public function restore(int $productType): void
    {
        $restore = ProductType::withTrashed()->where(['id' => $productType]);
        $restore->restore();
    }
}
