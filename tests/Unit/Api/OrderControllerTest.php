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

use App\Mail\OrderCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Helpers\EntityCreationHelper;
use Tests\TestCase;

/**
 * Class OrderControllerTest
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
class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public $base64Image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAXQAAAF0BVWAulAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAADfSURBVDiNpdK9LkRBFADg76yfRJRKiSi9ghfwHBoR/RYiiEJBNGoakY3oPIBOo1QrJBokCoUXOJoh17XrzjLJFDNzvjlnciYy039Gb1wQEb2IOIyIl4jYlJnVsyQ8Q5b5NC4+b+DETi2ewKCFjzJTLb5o4YOv8w48icsW3v8W03HBcQvv/YgZAaewjkU8FLw9NHYInsFVQSdYwMbIKhtwCTcl43Oj7JVfn9no8T1Wy3oOr+h3dqmAXZw2qpnFG6Yr2qyPd8yXjWVcY1D1yQpawy0ecYetmuyZKT7L+Ov4AOVwwJdv6ZjEAAAAAElFTkSuQmCC";

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_test_order(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test that the 'Order' method returns a list of orders.
     *
     * This test verifies that the 'Order' method in the controller correctly
     * retrieves and returns a list of Order.
     *
     * @return void
     */
    public function test_get_index_order_must_return_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $idOrder = EntityCreationHelper::createOrder($this, $idCustomer, $idProduct);

        $responseGetOrder = $this->getJson('/api/orders');

        $responseGetOrder->assertStatus(200);

        $responseGetOrder->assertJsonCount(1);

        $responseGetOrder->assertJson(function (AssertableJson $json) use ($idCustomer, $idOrder, $idProduct) {
            $json->whereAllType([
                '0.order_id' => 'integer',
                '0.customer_id' => 'integer',
                '0.creation_date' => 'string',
            ]);

            $json->whereAll([
                "0.order_id" => $idOrder,
                "0.customer_id" => $idCustomer,
                '0.products.0.product_id' => $idProduct,
            ]);
        });
    }

    /**
     * Test the 'store' endpoint for creating Order.
     *
     * This test verifies that the 'order' endpoint in the orders controller correctly handles
     * the creation of new order.
     *
     * @return void
     */
    public function test_post_store_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $data = [
            "customer_id" => $idCustomer,
            "products" => [
                [
                    "product_id" => $idProduct,
                    "quantity" => 2,
                ],
            ],
        ];

        $responseOrder = $this->postJson('/api/orders', $data);
        $resultOrder = json_decode($responseOrder->getContent())[0];
        $idOrder = $resultOrder->order->id;

        $responseOrder->assertStatus(201);

        $responseOrder->assertJsonCount(1);

        $responseOrder->assertJson(function (AssertableJson $json) use ($data, $idOrder) {
            $json->whereAllType([
                '0.message' => 'string',
                '0.order.id' => 'integer',
                '0.order.customer.id' => 'integer',
            ]);

            $json->whereAll([
                '0.message' => 'Order created successfully',
                '0.order.id' => $idOrder,
                '0.order.customer.id' => $data['customer_id'],
            ]);
        });
    }

    /**
     * Test the 'show' endpoint for fetching a single order.
     *
     * This test verifies that the 'show' endpoint in the order controller correctly retrieves
     * and returns a single order type based on the given identifier.
     *
     * @return void
     */
    public function test_get_show_single_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $idOrder = EntityCreationHelper::createOrder($this, $idCustomer, $idProduct);

        $responseGetOrder = $this->getJson("/api/orders/{$idOrder}");

        $responseGetOrder->assertStatus(200);

        $responseGetOrder->assertJsonCount(4);

        $responseGetOrder->assertJson(function (AssertableJson $json) use ($idOrder, $idCustomer, $idProduct) {
            $json->whereAllType([
                'order_id' => 'integer',
                'customer_id' => 'integer',
                'creation_date' => 'string',
                'products.0.product_id' => 'integer',
            ]);

            $json->whereAll([
                'order_id' => $idOrder,
                'customer_id' => $idCustomer,
                'products.0.product_id' => $idProduct,
            ]);
        });
    }

    /**
     * Test the 'update' endpoint for updating Order information.
     *
     * This test verifies that the 'update' endpoint in the Order controller correctly handles
     * the update of Order information based on the provided data.
     *
     * @return void
     */
    public function test_put_update_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $idOrder = EntityCreationHelper::createOrder($this, $idCustomer, $idProduct);

        $data = [
            "customer_id" => $idCustomer,
            "products" => [
                [
                    "product_id" => $idProduct,
                    "quantity" => 4,
                ],
            ],
        ];

        $response = $this->putJson("/api/orders/{$idOrder}", $data);

        $response->assertStatus(200);

        $response->assertJsonCount(4);

        $response->assertJsonStructure([
            'order_id',
            'customer_id',
            'products',
        ]);

        $response->assertJson(function (AssertableJson $json) use ($idOrder, $idCustomer, $data) {
            $json->whereAllType([
                'order_id' => 'integer',
                'customer_id' => 'integer',
                'creation_date' => 'string',
                'products.0.product_id' => 'integer',
                'products.0.quantity' => 'integer',
            ]);

            $json->whereAll([
                'order_id' => $idOrder,
                'customer_id' => $idCustomer,
                'products.0.product_id' => $data['products'][0]['product_id'],
                'products.0.quantity' => $data['products'][0]['quantity'],
            ]);
        });
    }

    /**
     * Test the 'Order' endpoint for deleting a Order.
     *
     * This test verifies that the 'soft-deleted' endpoint in the Order controller correctly handles
     * the deletion of a Order based on the provided identifier.
     *
     * @return void
     */
    public function test_delete_destroy_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $idOrder = EntityCreationHelper::createOrder($this, $idCustomer, $idProduct);

        $responseGetOrder = $this->deleteJson("/api/orders/{$idOrder}");

        $responseGetOrder->assertStatus(204);

        $responseGetOrder->assertNoContent();
    }

    /**
     * Test the 'restore' endpoint for restoring a soft-deleted Order.
     *
     * This test verifies that the 'restore' endpoint in the Order controller correctly handles
     * the restoration of a soft-deleted Order based on the provided identifier.
     *
     * @return void
     */
    public function test_post_restore_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $idOrder = EntityCreationHelper::createOrder($this, $idCustomer, $idProduct);

        $this->deleteJson("/api/orders/{$idOrder}");

        $response = $this->postJson("/api/orders/{$idOrder}/restore");

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Order restored successfully',
        ]);
    }

    /**
     * Test that the "show" endpoint for a order returns an error
     * when the requested order does not exist.
     *
     * @return void
     */
    public function test_get_show_order_must_return_error_when_order_does_not_exist_endpoint(): void
    {
        $response = $this->getJson('/api/orders/2');

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'Order not found.',
        ]);
    }

    /**
     * Test that the 'name' field must be required when creating a order.
     *
     * This test verifies that the 'name' field is a required field when creating a order.
     * It checks that an error occurs when attempting to create a order without a name.
     *
     * @return void
     */
    public function test_product_id_field_must_be_exist_when_creating_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $data = [
            "customer_id" => $idCustomer,
            "products" => [
                [
                    "product_id" => 10,
                    "quantity" => 2,
                ],
            ],
        ];

        $responseOrder = $this->postJson('/api/orders', $data);

        $responseOrder->assertStatus(422);

        $responseOrder->assertJsonCount(2);

        $responseOrder->assertJson([
            'message' => 'O campo products.0.product_id não é um produto valido.',
        ]);
    }

    /**
     * Test that the 'name' field must be required when creating a order.
     *
     * This test verifies that the 'name' field is a required field when creating a order.
     * It checks that an error occurs when attempting to create a order without a name.
     *
     * @return void
     */
    public function test_product_id_field_must_be_required_when_creating_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $data = [
            "customer_id" => $idCustomer,
            "products" => [
                [
                    "quantity" => 2,
                ],
            ],
        ];

        $responseOrder = $this->postJson('/api/orders', $data);

        $responseOrder->assertStatus(422);

        $responseOrder->assertJsonCount(2);

        $responseOrder->assertJson([
            'message' => 'O campo products.0.product_id é obrigatório.',
        ]);
    }

    /**
     * Test that the 'name' field must be required when creating a order.
     *
     * This test verifies that the 'name' field is a required field when creating a order.
     * It checks that an error occurs when attempting to create a order without a name.
     *
     * @return void
     */
    public function test_quantity_field_must_be_required_when_creating_order_endpoint(): void
    {
        Storage::fake('public');

        $idCustomer = EntityCreationHelper::createCustomer($this);

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $data = [
            "customer_id" => $idCustomer,
            "products" => [
                [
                    "product_id" => $idProduct,
                ],
            ],
        ];

        $responseOrder = $this->postJson('/api/orders', $data);

        $responseOrder->assertStatus(422);

        $responseOrder->assertJsonCount(2);

        $responseOrder->assertJson([
            'message' => 'O campo products.0.quantity é obrigatório.',
        ]);
    }

    /**
     * Test the 'store' endpoint for send email by creating Order.
     *
     * This test verifies that the 'order' endpoint  must  send an email after ordering
     *
     * @return void
     */
    public function test_post_store_must_send_order_email_to_queue_endpoint(): void
    {
        Storage::fake('public');
        Mail::fake();

        $customer = EntityCreationHelper::createCustomerReturnEmailAndDateOfBirthAndaAdress($this);
        $idCustomer = $customer['id'];
        $dateOfBirthCustomer = $customer['date_of_birth'];
        $emailCustomer = $customer['email'];

        $idProductType = EntityCreationHelper::createProductType($this);

        $idProduct = EntityCreationHelper::createProduct($this, $idProductType, $this->base64Image);

        $data = [
            "customer_id" => $idCustomer,
            "products" => [
                [
                    "product_id" => $idProduct,
                    "quantity" => 2,
                ],
            ],
        ];

        $responseOrder = $this->postJson('/api/orders', $data);
        $resultOrder = json_decode($responseOrder->getContent())[0];
        $idOrder = $resultOrder->order->id;

        Mail::assertQueued(OrderCreated::class, function ($mail) use ($emailCustomer, $idOrder, $dateOfBirthCustomer) {
            $this->assertEquals($mail->to[0]['address'], $emailCustomer);
            $this->assertEquals($mail->order['id'], $idOrder);
            $this->assertEquals($mail->order->customer['date_of_birth'], $dateOfBirthCustomer);
            return true;
        });
    }
}
