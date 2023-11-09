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

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * Class CustomerControllerTest
 *
 * This file deals with testing on Client.
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
class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_test_create_customer_controller(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test that the 'get_index_customers' method returns a list of customers.
     *
     * This test verifies that the 'get_index_customers' method in the controller correctly
     * retrieves and returns a list of customers.
     *
     * @return void
     */
    public function test_get_index_customers_must_return_customers_endpoint(): void
    {
        $customers = Customer::factory(3)->create();
        $response = $this->getJson('/api/customers');

        $sortedCollection = $customers->sortBy('name');

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $response->assertJson(function (AssertableJson $json) use ($sortedCollection) {
            $json->whereAllType([
                '0.id' => 'integer',
                '0.name' => 'string',
                '0.email' => 'string',
                '0.phone' => 'string',
                '0.address' => 'string',
                '0.complement' => 'string',
                '0.neighborhood' => 'string',
                '0.zipcode' => 'string',
                '0.date_of_birth' => 'string',
            ]);

            $customer = $sortedCollection->first();

            $json->whereAll([
                '0.id' => $customer->id,
                '0.name' => $customer->name,
                '0.email' => $customer->email,
                '0.phone' => $customer->phone,
                '0.address' => $customer->address,
                '0.complement' => $customer->complement,
                '0.neighborhood' => $customer->neighborhood,
                '0.zipcode' => $customer->zipcode,
                '0.date_of_birth' => $customer->date_of_birth,
            ]);
        });
    }

    /**
     * Test the 'store' endpoint for creating customers.
     *
     * This test verifies that the 'store' endpoint in the customers controller correctly handles
     * the creation of new customers.
     *
     * @return void
     */
    public function test_post_store_customers_endpoint_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($customer) {
            $json->whereAllType([
                'name' => 'string',
                'email' => 'string',
                'phone' => 'string',
                'date_of_birth' => 'string',
                'address' => 'string',
                'complement' => 'string',
                'neighborhood' => 'string',
                'zipcode' => 'string',
                'updated_at' => 'string',
                'created_at' => 'string',
                'id' => 'integer',
            ]);

            $json->whereAll([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'address' => $customer['address'],
                'complement' => $customer['complement'],
                'neighborhood' => $customer['neighborhood'],
                'zipcode' => $customer['zipcode'],
                'date_of_birth' => $customer['date_of_birth'],
            ]);
        });
    }

    /**
     * Test the 'show' endpoint for fetching a single customer.
     *
     * This test verifies that the 'show' endpoint in the customers controller correctly retrieves
     * and returns a single customer based on the given identifier.
     *
     * @return void
     */
    public function test_get_show_single_customer_endpoint(): void
    {
        $customer = Customer::factory(1)->createOne();

        $response = $this->getJson('/api/customers/' . $customer->id);

        $response->assertStatus(200);
        $response->assertJsonCount(12);

        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'phone',
            'address',
            'date_of_birth',
            'complement',
            'neighborhood',
            'zipcode',
            'date_of_birth',
        ]);

        $response->assertJson(function (AssertableJson $json) use ($customer) {
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'email' => 'string',
                'phone' => 'string',
                'date_of_birth' => 'string',
                'address' => 'string',
                'complement' => 'string',
                'neighborhood' => 'string',
                'zipcode' => 'string',
                'created_at' => 'string',
                'updated_at' => 'string',
                'deleted_at' => 'null',
            ]);

            $json->whereAll([
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'complement' => $customer->complement,
                'neighborhood' => $customer->neighborhood,
                'zipcode' => $customer->zipcode,
                'date_of_birth' => $customer->date_of_birth,
            ]);
        });
    }

    /**
     * Test the 'update' endpoint for updating customer information.
     *
     * This test verifies that the 'update' endpoint in the customers controller correctly handles
     * the update of customer information based on the provided data.
     *
     * @return void
     */
    public function test_put_update_customers_endpoint(): void
    {
        $result = Customer::factory(1)->createOne();
        $id = $result['id'];

        $customer = [
            "name" => "Test Update",
            "email" => "test@test.com",
            "phone" => "(11)9 1234-1234",
            "date_of_birth" => "1982-12-01",
            "address" => "Rua Hum",
            "complement" => "number 23",
            "neighborhood" => "Aclimação",
            "zipcode" => "01234-001",
        ];

        $response = $this->putJson("/api/customers/{$id}", $customer);

        $response->assertStatus(200);
        $response->assertJsonCount(12);

        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'phone',
            'address',
            'date_of_birth',
            'complement',
            'neighborhood',
            'zipcode',
            'date_of_birth',
        ]);

        $response->assertJson(function (AssertableJson $json) use ($customer) {
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'email' => 'string',
                'phone' => 'string',
                'date_of_birth' => 'string',
                'address' => 'string',
                'complement' => 'string',
                'neighborhood' => 'string',
                'zipcode' => 'string',
                'created_at' => 'string',
                'updated_at' => 'string',
                'deleted_at' => 'null',
            ]);

            $json->whereAll([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'address' => $customer['address'],
                'complement' => $customer['complement'],
                'neighborhood' => $customer['neighborhood'],
                'zipcode' => $customer['zipcode'],
                'date_of_birth' => $customer['date_of_birth'],
            ]);
        });
    }

    /**
     * Test the 'destroy' endpoint for deleting a customer.
     *
     * This test verifies that the 'soft deleted' endpoint in the customers controller correctly handles
     * the deletion of a customer based on the provided identifier.
     *
     * @return void
     */
    public function test_delete_destroy_customers_endpoint(): void
    {
        $result = Customer::factory(1)->createOne();
        $id = $result['id'];

        $response = $this->deleteJson("/api/customers/{$id}");

        $response->assertStatus(204);
    }

    /**
     * Test the 'restore' endpoint for restoring a soft-deleted customer.
     *
     * This test verifies that the 'restore' endpoint in the customers controller correctly handles
     * the restoration of a soft-deleted customer based on the provided identifier.
     *
     * @return void
     */
    public function test_post_restore_customers_endpoint(): void
    {
        $result = Customer::factory(1)->createOne();
        $id = $result["id"];

        $response = $this->deleteJson("/api/customers/{$id}");

        $response = $this->postJson("/api/customers/{$id}/restore");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Customer restored successfully',
        ]);
    }

    /**
     * Test that the "show" endpoint for a customer returns an error
     * when the requested customer does not exist.
     *
     * @return void
     */
    public function test_get_show_customer_must_return_error_when_customer_does_not_exist_endpoint(): void
    {
        $response = $this->getJson('/api/customers/2');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Customer not found.',
        ]);
    }

    /**
     * Test that a unique email constraint is enforced when creating a customer.
     *
     * This test ensures that the controller correctly enforces a unique constraint on the email
     * field when creating a customer. It checks that attempting to create a customer with a
     * duplicate email results in an appropriate error response.
     *
     * @return void
     */
    public function test_unique_email_constraint_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        $response = $this->postJson('/api/customers', $customer);

        $customerWhitEmailRepet = Customer::factory()->makeOne()->toArray();
        $customerWhitEmailRepet['email'] = $customer['email'];

        $response = $this->postJson('/api/customers', $customerWhitEmailRepet);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Este email já está em uso.',
        ]);
    }

    /**
     * Test that the 'email' field in the Customer model must be unique and required.
     *
     * This test ensures that the 'email' field in the Customer model is both unique and
     * required. It verifies that an error occurs when attempting to save a customer
     * without a unique email or with a missing email.
     *
     * @return void
     */
    public function test_email_field_must_be_unique_and_required_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        unset($customer['email']);

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo email é obrigatório.',
        ]);
    }

    /**
     * Test that the 'name' field must be required when creating a customer.
     *
     * This test verifies that the 'name' field is a required field when creating a customer.
     * It checks that an error occurs when attempting to create a customer without a name.
     *
     * @return void
     */
    public function test_name_field_must_be_required_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        unset($customer['name']);

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo name é obrigatório.',
        ]);
    }

    /**
     * Test that the 'phone' field must be required when creating a customer.
     *
     * This test verifies that the 'phone' field is a required field when creating a customer.
     * It checks that an error occurs when attempting to create a customer without a phone.
     *
     * @return void
     */
    public function test_phone_field_must_be_required_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        unset($customer['phone']);

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo phone é obrigatório.',
        ]);
    }

    /**
     * Test that the 'date_of_birth' field must be required when creating a customer.
     *
     * This test verifies that the 'date_of_birth' field is a required field when creating a customer.
     * It checks that an error occurs when attempting to create a customer without a date of birth.
     *
     * @return void
     */
    public function test_date_of_birth_field_must_be_required_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        unset($customer['date_of_birth']);

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo date of birth é obrigatório.',
        ]);
    }

    /**
     * Test that the 'address' field must be required when creating a customer.
     *
     * This test verifies that the 'address' field is a required field when creating a customer.
     * It checks that an error occurs when attempting to create a customer without a address.
     *
     * @return void
     */
    public function test_address_of_birth_field_must_be_required_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        unset($customer['address']);

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo address é obrigatório.',
        ]);
    }

    /**
     * Test that the 'complement' field must be required when creating a customer.
     *
     * This test verifies that the 'complement' field is a required field when creating a customer.
     * It checks that an error occurs when attempting to create a customer without a complement.
     *
     * @return void
     */
    public function test_complement_field_must_be_required_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        unset($customer['complement']);

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo complement é obrigatório.',
        ]);
    }

    /**
     * Test that the 'neighborhood' field must be required when creating a customer.
     *
     * This test verifies that the 'neighborhood' field is a required field when creating a customer.
     * It checks that an error occurs when attempting to create a customer without a neighborhood.
     *
     * @return void
     */
    public function test_neighborhood_field_must_be_required_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        unset($customer['neighborhood']);

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo neighborhood é obrigatório.',
        ]);
    }

    /**
     * Test that the 'zipcode' field must be required when creating a customer.
     *
     * This test verifies that the 'zipcode' field is a required field when creating a customer.
     * It checks that an error occurs when attempting to create a customer without a zipcode.
     *
     * @return void
     */
    public function test_zipcode_field_must_be_required_when_creating_customer_endpoint(): void
    {
        $customer = Customer::factory()->makeOne()->toArray();
        unset($customer['zipcode']);

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'O campo zipcode é obrigatório.',
        ]);
    }
}
