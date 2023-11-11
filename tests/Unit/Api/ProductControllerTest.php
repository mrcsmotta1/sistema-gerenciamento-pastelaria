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
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * Class CustomerControllerTest
 *
 * This file deals with testing on Product.
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
class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public $base64Image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAXQAAAF0BVWAulAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAADfSURBVDiNpdK9LkRBFADg76yfRJRKiSi9ghfwHBoR/RYiiEJBNGoakY3oPIBOo1QrJBokCoUXOJoh17XrzjLJFDNzvjlnciYy039Gb1wQEb2IOIyIl4jYlJnVsyQ8Q5b5NC4+b+DETi2ewKCFjzJTLb5o4YOv8w48icsW3v8W03HBcQvv/YgZAaewjkU8FLw9NHYInsFVQSdYwMbIKhtwCTcl43Oj7JVfn9no8T1Wy3oOr+h3dqmAXZw2qpnFG6Yr2qyPd8yXjWVcY1D1yQpawy0ecYetmuyZKT7L+Ov4AOVwwJdv6ZjEAAAAAElFTkSuQmCC";

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_test_create_product_controller(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test that the 'get_index' method returns the expected product type.
     *
     * This test checks if the 'get_index' method of the system correctly
     * returns the product type as expected. It ensures that the system
     * is properly configured to fetch and return the product type.
     *
     * @return void
     */
    public function test_get_index_product_must_return_product_endpoint(): void
    {
        $productType = ProductType::factory(1)->createOne();

        Storage::fake('public');

        $products = [
            'name' => 'teste',
            "product_type_id" => $productType->id,
            'price' => 19.99,
            'photo' => $this->base64Image,
        ];

        $responseProductType = $this->postJson('/api/products', $products);

        $productType = json_decode($responseProductType->getContent());
        $productTypeID = $productType[0]->product->id;

        $responseProducts = $this->getJson("/api/products/{$productTypeID}");

        $responseProducts->assertStatus(200);
        $responseProducts->assertJsonCount(8);

        $responseProducts->assertJson(function (AssertableJson $json) use ($products) {
            $json->whereAllType([
                'id' => 'integer',
                'product_type_id' => 'integer',
                'name' => 'string',
                'price' => 'double',
                'photo' => 'string',
                'created_at' => 'string',
                'updated_at' => 'string',
                'deleted_at' => 'null',
            ]);

            $json->whereAll([
                'name' => $products['name'],
                'product_type_id' => $products['product_type_id'],
                'price' => $products['price'],
            ]);
        });
    }

    /**
     * Test the creation of a product with a base64-encoded image.
     *
     * This test verifies if the system is capable of creating a new product
     * with an image provided in base64 format. It should ensure that
     * the image is decoded correctly and associated with the created product.
     *
     * @return void
     */
    public function test_store_product_endpoint(): void
    {
        Storage::fake('public');

        $productType = ProductType::factory(1)->createOne();

        $response = $this->postJson('/api/products', [
            'name' => 'teste',
            "product_type_id" => $productType->id,
            'price' => 19.99,
            'photo' => $this->base64Image,
        ]);

        $response->assertStatus(201);

        $content = $response->getContent();
        $data = json_decode($content, true);
        $photo = $data[0]['product']['photo'];
        $filename = basename($photo);
        $filename2 = 'img/' . $filename;

        $this->assertTrue(Storage::disk('public')->exists($filename2));
    }

    /**
     * Test the PUT request to update a product via the API endpoint.
     *
     * @return void
     */
    public function test_put_update_product_endpoint(): void
    {
        Storage::fake('public');

        $productType = ProductType::factory(1)->createOne();

        $products = [
            'name' => 'teste',
            "product_type_id" => $productType['id'],
            'price' => "19.99",
            'photo' => $this->base64Image,
        ];

        $responseProductType = $this->postJson('/api/products', $products);

        $content = $responseProductType->json();
        $idProduct = $content[0]['product']['id'];

        $productUpdate = [
            "name" => "Test Update",
            "product_type_id" => $productType['id'],
            "price" => "10.10",
            "photo" => $this->base64Image,
        ];

        $response = $this->putJson("/api/products/{$idProduct}", $productUpdate);

        $contentPut = $response->json();

        $namePhoto = $contentPut['photo'];

        $response->assertStatus(200);
        $response->assertJsonCount(8);

        $response->assertJsonStructure([
            'id',
            'name',
            'product_type_id',
            'price',
            'photo',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

        $response->assertJson(function (AssertableJson $json) use ($productUpdate, $namePhoto) {
            $json->whereAllType([
                'id' => 'integer',
                'product_type_id' => 'integer',
                'name' => 'string',
                "price" => "string",
                'photo' => 'string',
                'created_at' => 'string',
                'updated_at' => 'string',
                'deleted_at' => 'null',
            ]);

            $json->whereAll([
                'name' => $productUpdate['name'],
                'price' => (string) $productUpdate['price'],
                'photo' => $namePhoto,
            ]);
        });
    }

    /**
     * Test the DELETE request to destroy a product via the API endpoint.
     *
     * @return void
     */
    public function test_delete_destroy_product_endpoint(): void
    {
        Storage::fake('public');

        $productType = ProductType::factory(1)->createOne();

        $products = [
            'name' => 'teste',
            "product_type_id" => $productType->id,
            'price' => "19.99",
            'photo' => $this->base64Image,
        ];

        $responseProductType = $this->postJson('/api/products', $products);

        $content = $responseProductType->json();
        $idProduct = $content[0]['product']['id'];

        $response = $this->deleteJson("/api/products/{$idProduct}");

        $response->assertStatus(204);
    }

    /**
     * Test the POST request to restore a previously deleted product via the API endpoint.
     *
     * @return void
     */
    public function test_post_restore_product_endpoint(): void
    {
        Storage::fake('public');

        $productType = ProductType::factory(1)->createOne();

        $products = [
            'name' => 'teste',
            "product_type_id" => $productType->id,
            'price' => "19.99",
            'photo' => $this->base64Image,
        ];

        $responseProductType = $this->postJson('/api/products', $products);

        $content = $responseProductType->json();
        $idProduct = $content[0]['product']['id'];

        $response = $this->deleteJson("/api/products/{$idProduct}");

        $response = $this->postJson("/api/products/{$idProduct}/restore");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product restored successfully',
        ]);
    }

    /**
     * Test that creating a product with a base64 image is valid.
     *
     * @return void
     */
    public function test_create_product_must_be_valid_with_base64_image_endpoint(): void
    {

        Storage::fake('public');

        $productType = ProductType::factory(1)->createOne();

        $base64Image = "data:image/png;base64,iVBORw0KGgoAAAANSUhYXBlLm9yZ5vuPBoAAADfSURBVDiNpdK9LkRBFA";

        $response = $this->postJson('/api/products', [
            'name' => 'teste',
            "product_type_id" => $productType->id,
            'price' => 19.99,
            'photo' => $base64Image,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo photo não possui um arquivo válido existente.',
        ]);
    }

    /**
     * Test that the 'product_type_id' field must exist when creating a product via the API endpoint.
     *
     * @return void
     */
    public function test_product_type_id_field_must_be_exist_when_creating_product_endpoint(): void
    {

        Storage::fake('public');

        $response = $this->postJson('/api/products', [
            'name' => 'teste',
            "product_type_id" => 10,
            'price' => 19.99,
            'photo' => $this->base64Image,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo product type id não é um tipo de produto válido.',
        ]);
    }

    /**
     * Test that the 'name' field must exist when creating a product via the API endpoint.
     *
     * @return void
     */
    public function test_name_field_must_be_required_creating_product_endpoint(): void
    {

        Storage::fake('public');

        $productType = ProductType::factory(1)->createOne();

        $response = $this->postJson('/api/products', [
            'name' => '',
            "product_type_id" => $productType->id,
            'price' => 19.99,
            'photo' => $this->base64Image,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo name é obrigatório.',
        ]);
    }

    /**
     * Test that the 'price' field must exist when creating a product via the API endpoint.
     *
     * @return void
     */
    public function test_price_field_must_be_required_creating_product_endpoint(): void
    {

        Storage::fake('public');

        $productType = ProductType::factory(1)->createOne();

        $response = $this->postJson('/api/products', [
            'name' => 'teste',
            "product_type_id" => $productType->id,
            'price' => '',
            'photo' => $this->base64Image,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo price é obrigatório.',
        ]);
    }

     /**
     * Test that the 'price' field must be a have value freater than zero when creating a product via the API endpoint.
     *
     * @return void
     */
    public function test_price_field_must_be_have_value_greater_than_zero_when_creating_product_endpoint(): void
    {

        Storage::fake('public');

        $productType = ProductType::factory(1)->createOne();

        $response = $this->postJson('/api/products', [
            'name' => 'Teste',
            "product_type_id" => $productType->id,
            'price' => 0,
            'photo' => $this->base64Image,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo price deve ser maior que 0.00. (and 1 more error)',
        ]);
    }
}
