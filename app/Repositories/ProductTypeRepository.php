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
     * @param ProductTypeApiRequest $request The HTTP ProductType data.
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
     * @param ProductTypeApiRequest $request     The HTTP ProductType data.
     * @param $productType The ProductType to update.
     *
     * @return ProductType The updated ProductType.
     */
    public function update(ProductTypeApiRequest $request, $productType): ProductType
    {
        $ProductType = $productType->fill($request->all());
        $ProductType->save();

        return $ProductType;
    }

    /**
     * Delete a ProductType by their ID.
     *
     * @param string $ProductType The ID of the ProductType to delete.
     *
     * @return void
     */
    public function destroy(string $ProductType): void
    {
        ProductType::destroy($ProductType);
    }

    /**
     * Restore a soft-deleted Product Type.
     *
     * @param string $productType The ID of the productType to restore.
     *
     * @return void
     */
    public function restore(string $productType): void
    {
        $restore = ProductType::withTrashed()->where(['id' => $productType]);
        $restore->restore();
    }
}
