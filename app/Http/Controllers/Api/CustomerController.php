<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApiRequest;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;

/**
 * Arquivo Class CustomerController.
 *
 * Controller Customer.
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
class CustomerController extends Controller
{
    /**
     * Create a new CustomerController instance.
     *
     * @param CustomerRepository $customerRepository The repository for handling customer data.
     */
    public function __construct(private CustomerRepository $customerRepository)
    {
    }

    /**
     * Display a listing of customers.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with a list of customers.
     */
    public function index()
    {
        return response()->json($this->customerRepository->index());
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param \App\Http\Requests\CustomerApiRequest $request The customer data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response with the newly created customer and HTTP status 201 (Created).
     */
    public function store(CustomerApiRequest $request)
    {
        return response()->json($this->customerRepository->add($request));
    }

    /**
     * Display the specified customer.
     *
     * @param \App\Models\Customer $customer The customer to display.
     *
     * @return \App\Models\Customer The specified customer.
     */
    public function show(string $customer)
    {
        $result = Customer::find($customer);

        if (!$result) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($result);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param \App\Models\Customer                  $customer The customer to update.
     * @param \App\Http\Requests\CustomerApiRequest $request  The updated customer data.
     *
     * @return \Illuminate\Http\JsonResponse          A JSON response with the updated customer.
     */
    public function update(Customer $customer, CustomerApiRequest $request)
    {
        return response()->json($this->customerRepository->update($customer, $request));
    }

    /**
     * Soft delete the specified customer.
     *
     * @param int $customer The ID of the customer to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
     */
    public function destroy(int $customer)
    {
        $this->customerRepository->destroy($customer);
        return response()->noContent();
    }

    /**
     * Restore a soft-deleted customer.
     *
     * @param int $customer The ID of the customer to restore.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the customer has been successfully restored.
     */
    public function restore(int $customer)
    {
        $restore = Customer::withTrashed()->where(['id' => $customer]);
        $restore->restore();
        return response()->json(['message' => 'Customer restored successfully']);
    }
}
