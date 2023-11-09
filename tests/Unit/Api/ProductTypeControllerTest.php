<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Tests
 * @package  Tests\Unit\Api
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace Tests\Unit\Api;

use App\Models\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * Class ProductTypeControllerTest
 *
 * This file deals with testing on Product Type.
 *
 * @phpcs
 * php version 8.1
 *
 * @category Tests
 * @package  Tests\Unit\Api
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class ProductTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_test_product_type(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test that the 'get_index_product_type' method returns a list of customers.
     *
     * This test verifies that the 'get_index_product_type' method in the controller correctly
     * retrieves and returns a list of Product Types.
     *
     * @return void
     */
    public function test_get_index_product_type_must_return_product_type_endpoint(): void
    {
        $productType = ProductType::factory(3)->create();
        $response = $this->getJson('/api/product-types');

        // dd($response->baseResponse);
        $sortedCollection = $productType->sortBy('name');

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $response->assertJson(function (AssertableJson $json) use ($sortedCollection) {
            $json->whereAllType([
                '0.id' => 'integer',
                '0.name' => 'string',
                '0.updated_at' => 'string',
                '0.deleted_at' => 'null',
            ]);

            $productType = $sortedCollection->first();

            $json->whereAll([
                '0.id' => $productType->id,
                '0.name' => $productType->name,
            ]);
        });
    }

    /**
     * Test the 'store' endpoint for creating product type.
     *
     * This test verifies that the 'producttype' endpoint in the customers controller correctly handles
     * the creation of new product type.
     *
     * @return void
     */
    public function test_post_store_product_type_endpoint(): void
    {
        $productType = ProductType::factory()->makeOne()->toArray();

        $response = $this->postJson('/api/product-types', $productType);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($productType) {
            $json->whereAllType([
                'name' => 'string',
                'updated_at' => 'string',
                'created_at' => 'string',
                'id' => 'integer',
            ]);

            $json->whereAll([
                'name' => $productType['name'],
            ]);
        });
    }

    /**
     * Test the 'show' endpoint for fetching a single product type.
     *
     * This test verifies that the 'show' endpoint in the product types controller correctly retrieves
     * and returns a single product type type based on the given identifier.
     *
     * @return void
     */
    public function test_get_show_single_product_type_endpoint(): void
    {
        $productType = ProductType::factory(1)->createOne();

        $response = $this->getJson('/api/product-types/' . $productType->id);

        $response->assertStatus(200);
        $response->assertJsonCount(5);

        $response->assertJsonStructure([
            'id',
            'name',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

        $response->assertJson(function (AssertableJson $json) use ($productType) {
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'created_at' => 'string',
                'updated_at' => 'string',
                'deleted_at' => 'null',
            ]);

            $json->whereAll([
                'id' => $productType->id,
                'name' => $productType->name,
            ]);
        });
    }

    /**
     * Test the 'update' endpoint for updating Product Type information.
     *
     * This test verifies that the 'update' endpoint in the Product Type controller correctly handles
     * the update of Product Type information based on the provided data.
     *
     * @return void
     */
    public function test_put_update_product_type_endpoint(): void
    {
        $result = ProductType::factory(1)->createOne();

        $id = $result->id;

        $productType = [
            "name" => "Test Update",
        ];

        $response = $this->putJson("/api/product-types/{$id}", $productType);

        $response->assertStatus(200);
        $response->assertJsonCount(5);

        $response->assertJsonStructure([
            'id',
            'name',
        ]);

        $response->assertJson(function (AssertableJson $json) use ($productType) {
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'created_at' => 'string',
                'updated_at' => 'string',
                'deleted_at' => 'null',
            ]);

            $json->whereAll([
                'name' => $productType['name'],
            ]);
        });
    }

    /**
     * Test the 'product type' endpoint for deleting a product type.
     *
     * This test verifies that the 'soft-deleted' endpoint in the product types controller correctly handles
     * the deletion of a product type based on the provided identifier.
     *
     * @return void
     */
    public function test_delete_destroy_product_type_endpoint(): void
    {
        $productType = ProductType::factory(1)->createOne();
        $productTypeId = $productType['id'];

        $response = $this->deleteJson("/api/product-types/{$productTypeId}");

        $response->assertStatus(204);
    }

    /**
     * Test the 'restore' endpoint for restoring a soft-deleted product type.
     *
     * This test verifies that the 'restore' endpoint in the product types controller correctly handles
     * the restoration of a soft-deleted product type based on the provided identifier.
     *
     * @return void
     */
    public function test_post_restore_product_type_endpoint(): void
    {
        $rsultProdutoTypeId = ProductType::factory(1)->createOne();
        $produtoTypeId = $rsultProdutoTypeId['id'];

        $response = $this->deleteJson("/api/product-types/{$produtoTypeId}");

        $response = $this->postJson("/api/product-types/{$produtoTypeId}/restore");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product Type restored successfully',
        ]);
    }

    /**
     * Test that the 'name' field must be required when creating a customer.
     *
     * This test verifies that the 'name' field is a required field when creating a customer.
     * It checks that an error occurs when attempting to create a customer without a name.
     */
    public function test_name_field_must_be_required_when_creating_customer_endpoint(): void
    {
        $productType = ProductType::factory()->makeOne()->toArray();
        unset($productType['name']);

        $response = $this->postJson('/api/product-types', $productType);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo name é obrigatório.',
        ]);
    }

    /**
     * Test if the id does not exist in the show endpoint should return an error
     * when the requested product type does not exist.
     *
     * @return void
     */
    public function test_get_show_product_type_must_return_error_when_product_type_id_does_not_exist_endpoint(): void
    {
        $response = $this->getJson('/api/product-types/2');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Product Type not found.',
        ]);
    }

    /**
     * Test if the id does not exist in the put endpoint should return an error
     * when attempting to update a product type that does not exist.
     *
     * @return void
     */
    public function test_put_product_type_must_return_error_when_product_type_id_does_not_exist_endpoint(): void
    {
        $productType = [
            "name" => "Test Update",
        ];

        $response = $this->putJson('/api/product-types/32', $productType);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Product Type not found.',
        ]);
    }

    /**
     * Test if the id does not exist in the delete endpoint should return an error
     * when attempting to update a product type that does not exist.
     *
     * @return void
     */
    public function test_delete_product_type_must_return_error_when_product_type_id_does_not_exist_endpoint(): void
    {
        $response = $this->deleteJson('/api/product-types/32');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Product Type not found.',
        ]);
    }
}
