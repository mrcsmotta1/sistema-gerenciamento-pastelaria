<?php

namespace App\Repositories;

use App\Http\Requests\CustomerApiRequest;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

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
    public function index(): Collection
    {
        return Customer::query()->orderBy('name')->get();
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
        return Customer::create($request->all());
    }

    /**
     * Update an existing customer.
     *
     * @param Customer           $customer The customer to update.
     * @param CustomerApiRequest $request  The HTTP request containing updated customer data.
     *
     * @return Customer The updated customer.
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
     * @param int $customer The ID of the customer to delete.
     *
     * @return void
     */
    public function destroy(int $customer): void
    {
        Customer::destroy($customer);
    }
}
