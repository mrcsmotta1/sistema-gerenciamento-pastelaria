<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApiRequest;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Response;

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
     * @param CustomerRepository $customerRepository The repository customer data.
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
     * @return \Illuminate\Http\JsonResponse A JSON response with new customer.
     */
    public function store(CustomerApiRequest $request)
    {
        return response()
            ->json(
                $this->customerRepository
                    ->add($request),
                Response::HTTP_CREATED
            );
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
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        return response()->json($result);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param \App\Http\Requests\CustomerApiRequest $request  Customer data.
     * @param \App\Models\Customer                  $customer The customer to update.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response with the updated.
     */
    public function update(CustomerApiRequest $request, $customer)
    {
        $customer = Customer::find($customer);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }
        return response()
            ->json(
                $this->customerRepository
                    ->update(
                        $customer,
                        $request
                    )
            );
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
        $customerExist = Customer::find($customer);

        if (!$customerExist) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        $this->customerRepository->destroy($customer);
        return response()->noContent();
    }

    /**
     * Restore a soft-deleted customer.
     *
     * @param int $customer The ID of the customer to restore.
     *
     * @return \Illuminate\Http\JsonResponse A JSON with successfully restored.
     */
    public function restore(int $customer)
    {
        $restoreExist = Customer::withTrashed()->find($customer);

        if (!$restoreExist) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        return $this->customerRepository->restore($customer);
    }
}
