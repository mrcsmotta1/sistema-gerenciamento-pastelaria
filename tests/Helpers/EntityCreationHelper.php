<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Tests
 * @package  Tests\Helpers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace Tests\Helpers;

use App\Models\Customer;
use App\Models\ProductType;

/**
 * Class EntityCreationHelper
 *
 * Class provides methods to create entities for testing purposes.
 *
 * @phpcs
 * php version 8.1
 *
 * @category Tests
 * @package  Tests\Helpers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class EntityCreationHelper
{
    /**
     * Create a customer and return the customer ID.
     *
     * @param \Tests\TestCase $testInstance The current test instance.
     *
     * @return int The ID of the created customer.
     */
    public static function createCustomer($testInstance): int
    {
        $customer = Customer::factory(1)->makeOne()->toArray();
        $responseCostumer = $testInstance->postJson('/api/customers', $customer);
        $dataCustomer = $responseCostumer->getContent();
        $customerDecode = json_decode($dataCustomer, true);

        return $customerDecode['id'];
    }

    /**
     * Create a customer and return the customer ID data of birth and email.
     *
     * @param \Tests\TestCase $testInstance The current test instance.
     *
     * @return array Customer ID data of birth and email
     */
    public static function createCustomerReturnEmailAndDateOfBirthAndaAdress($testInstance): array
    {
        $customer = Customer::factory(1)->makeOne()->toArray();
        $responseCostumer = $testInstance->postJson('/api/customers', $customer);
        $dataCustomer = $responseCostumer->getContent();
        $customerDecode = json_decode($dataCustomer, true);

        return  [
            "id" => $customerDecode['id'],
            "date_of_birth" => $customerDecode['date_of_birth'],
            'email' => $customerDecode['email'],
        ];
    }

    /**
     * Create a product type and return the product type ID.
     *
     * @param \Tests\TestCase $testInstance The current test instance.
     *
     * @return int The ID of the created product type.
     */
    public static function createProductType($testInstance): int
    {
        $productType = ProductType::factory(1)->makeOne()->toArray();
        $responseProductType = $testInstance->postJson('/api/product-types', $productType);
        $dataProdutctType = $responseProductType->getContent();
        $productTypeDecode = json_decode($dataProdutctType, true);

        return $productTypeDecode['id'];
    }

    /**
     * Create a product and return the product ID.
     *
     * @param \Tests\TestCase $testInstance  The current test instance.
     * @param int             $productTypeId The ID of the associated product type.
     * @param string          $base64Image   The base64 image data for the product.
     *
     * @return int The ID of the created product.
     */
    public static function createProduct($testInstance, $productTypeId, $base64Image): int
    {

        $products = [
            'name' => 'teste',
            "product_type_id" => $productTypeId,
            'price' => "19.99",
            'photo' => $base64Image,
        ];

        $responseProduct = $testInstance->postJson('/api/products', $products);

        $contentProduct = $responseProduct->json();

        return $contentProduct[0]['product']['id'];
    }

    /**
     * Create an order and return the order ID.
     *
     * @param \Tests\TestCase $testInstance The current test instance.
     * @param int             $customerId   The ID of the associated customer.
     * @param int             $productId    The ID of the associated product.
     *
     * @return int The ID of the created order.
     */
    public static function createOrder($testInstance, $customerId, $productId): int
    {

        $data = [
            "customer_id" => $customerId,
            "products" => [
                [
                    "product_id" => $productId,
                    "quantity" => 2,
                ],
            ],
        ];

        $responseOrder = $testInstance->postJson('/api/orders', $data);
        $resultOrder = json_decode($responseOrder->getContent())[0];
        return $resultOrder->order->id;
    }
}
