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

use App\Http\Requests\CustomerApiRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

/**
 * Class CustomerRepository
 *
 * This Repository handles operations related to customers.
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
class CustomerRepository
{
    /**
     * Select All customer.
     *
     * @return Customer The updated customer.
     */
    public function index()
    {
        return DB::transaction(function () {
            return Customer::query()->orderBy('name')->get();
        });
    }

    /**
     * Add a new customer.
     *
     * @param CustomerApiRequest $request The HTTP request containing customer data.
     *
     * @return Customer The newly created customer.
     */
    public function add(CustomerApiRequest $request): Customer
    {
        return DB::transaction(function () use ($request) {
            return Customer::create($request->all());
        });
    }

    /**
     * Update an existing customer.
     *
     * @param Customer           $customer The customer to update.
     * @param CustomerApiRequest $request  The HTTP request with customer data.
     *
     * @return Customer The updated customer.
     *
     *
     */
    public function update(Customer $customer, CustomerApiRequest $request): Customer
    {
        $customer = $customer->fill($request->all());
        $customer->save();

        return $customer;
    }

    /**
     * Delete a customer by their ID.
     *
     * @param string $customer The ID of the customer to delete.
     *
     * @return void
     */
    public function destroy(string $customer): void
    {
        Customer::destroy($customer);
    }

    /**
     * Restores a customer by their unique identifier.
     *
     * @param int $customer The unique identifier of the customer to restore.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(string $customer)
    {
        $restoreExist = Customer::withTrashed()->find($customer);

        $restoreExist->restore();
        return response()->json(['message' => 'Customer restored successfully']);
    }
}
