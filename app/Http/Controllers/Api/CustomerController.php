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
/**
 * @OA\Tag(
 *     name="Customer",
 *     description="Customer-related operations, including creation, consultation, updating, soft deletion and restoration."
 * )
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
     *
     *
     * @OA\Get(
     *     path="/api/customers",
     *     summary="Get a list of customers",
     *     tags={"Customer"},
     *     @OA\Response(
     *         response=200,
     *         description="List of customers.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="date_of_birth", type="string", format="date"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="complement", type="string"),
     *                 @OA\Property(property="neighborhood", type="string"),
     *                 @OA\Property(property="zipcode", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", example=null),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            return response()->json($this->customerRepository->index());
        } catch (\Exception $th) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param \App\Http\Requests\CustomerApiRequest $request The customer data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response with new customer.
     *
     * @OA\Post(
     *     path="/api/customers",
     *     summary="Store a newly created customer in storage",
     *     tags={"Customer"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe", description="The name of the customer."),
     *             @OA\Property(property="email", type="string", example="john@example.com", description="The email of the customer."),
     *             @OA\Property(property="phone", type="string", example="(12)9 1456-7890", description="The phone number of the customer."),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01", description="The date of birth of the customer."),
     *             @OA\Property(property="address", type="string", example="123 Main St", description="The address of the customer."),
     *             @OA\Property(property="complement", type="string", example="Apt 4B", description="The complement of the address."),
     *             @OA\Property(property="neighborhood", type="string", example="Downtown", description="The neighborhood of the customer."),
     *             @OA\Property(property="zipcode", type="string", example="12345-678", description="The ZIP code of the customer."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="The ID of the created customer."),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="(12)9 1456-7890"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="complement", type="string", example="Apt 4B"),
     *             @OA\Property(property="neighborhood", type="string", example="Downtown"),
     *             @OA\Property(property="zipcode", type="string", example="12345-123"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="Ocorreu um erro de validação."),
     *           @OA\Property(property="errors", type="object",
     *               @OA\Property(property="name", type="array",
     *                   @OA\Items(type="string", example="O campo name é obrigatório.")
     *               ),
     *               @OA\Property(property="email", type="array",
     *                   @OA\Items(type="string", example="O campo email é obrigatório.")
     *               ),
     *               @OA\Property(property="phone", type="array",
     *                   @OA\Items(type="string", example="O campo phone é obrigatório.")
     *               ),
     *               @OA\Property(property="date_of_birth", type="array",
     *                   @OA\Items(type="string", example="O campo date_of_birth é obrigatório.")
     *               ),
     *               @OA\Property(property="address", type="array",
     *                   @OA\Items(type="string", example="O campo address é obrigatório.")
     *               ),
     *               @OA\Property(property="complement", type="array",
     *                   @OA\Items(type="string", example="O campo complement é obrigatório.")
     *               ),
     *               @OA\Property(property="neighborhood", type="array",
     *                   @OA\Items(type="string", example="O campo neighborhood é obrigatório.")
     *               ),
     *               @OA\Property(property="zipcode", type="array",
     *                   @OA\Items(type="string", example="O campo zipcode é obrigatório.")
     *               ),
     *           ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         ),
     *     ),
     * )
     *
     */
    public function store(CustomerApiRequest $request)
    {
        try {
            return response()
                ->json(
                    $this->customerRepository
                        ->add($request),
                    Response::HTTP_CREATED
                );
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Display the specified customer.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/customers/{customerID}",
     *     summary="Get a customer by ID",
     *     description="Retrieve a customer by ID.",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="customerID",
     *         in="path",
     *         description="ID of the customer to retrieve",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="integer", format="int32")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", format="int32"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="date_of_birth", type="string", format="date"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="complement", type="string"),
     *             @OA\Property(property="neighborhood", type="string"),
     *             @OA\Property(property="zipcode", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer not found"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         )
     *     ),
     * )
     */
    public function show(string $customer)
    {
        try {
            $result = Customer::find($customer);

            if (!$result) {
                return response()->json(['message' => 'Customer not found.'], 404);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Update the specified customer in storage.
     *
     * @param \App\Http\Requests\CustomerApiRequest $request  Customer data.
     * @param \App\Models\Customer                  $customer The customer to update.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response with the updated.
     *
     * @OA\Put(
     *     path="/api/customers/{customerID}",
     *     summary="Update a customer",
     *     description="Update an existing customer resource.",
     *     operationId="updateCustomer",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="customerID",
     *         in="path",
     *         description="ID of the customer to update",
     *         required=true,
     *         @OA\Schema(type="integer", format="int32")
     *     ),
     *     @OA\RequestBody(
     *         description="Customer data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="(12)9 1456-7890"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="complement", type="string", example="Apt 4B"),
     *             @OA\Property(property="neighborhood", type="string", example="Downtown"),
     *             @OA\Property(property="zipcode", type="string", example="12345-678"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-11-10 13:28:46"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-11-10 13:37:20"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", example=null),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of customers.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="date_of_birth", type="string", format="date"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="complement", type="string"),
     *                 @OA\Property(property="neighborhood", type="string"),
     *                 @OA\Property(property="zipcode", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", example=null),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer not found"),
     *         )
     *     ),
     *     @OA\Response(
     *        response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="Ocorreu um erro de validação."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="O campo name é obrigatório.")
     *                 ),
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="O campo email é obrigatório.")
     *                 ),
     *                 @OA\Property(property="phone", type="array",
     *                     @OA\Items(type="string", example="O campo phone é obrigatório.")
     *                 ),
     *                 @OA\Property(property="date_of_birth", type="array",
     *                     @OA\Items(type="string", example="O campo date_of_birth é obrigatório.")
     *                 ),
     *                 @OA\Property(property="address", type="array",
     *                     @OA\Items(type="string", example="O campo address é obrigatório.")
     *                 ),
     *                 @OA\Property(property="complement", type="array",
     *                     @OA\Items(type="string", example="O campo complement é obrigatório.")
     *                 ),
     *                 @OA\Property(property="neighborhood", type="array",
     *                     @OA\Items(type="string", example="O campo neighborhood é obrigatório.")
     *                 ),
     *                 @OA\Property(property="zipcode", type="array",
     *                     @OA\Items(type="string", example="O campo zipcode é obrigatório.")
     *                 ),
     *             ),
     *           ),
     *         ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         )
     *     ),
     * )
     */
    public function update(CustomerApiRequest $request, $customer)
    {
        try {
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
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Soft delete the specified customer.
     *
     * @param int $customer The ID of the customer to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
     *
     * @param  string  $customer
     * @return \Illuminate\Http\Response
     *
     * @OA\Delete(
     *     path="/api/customers/{customerID}",
     *     summary="Delete a customer by ID",
     *     description="Delete a customer by ID (soft delete).",
     *     operationId="deleteCustomerById",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="customerID",
     *         in="path",
     *         description="ID of the customer to delete",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer not found"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         )
     *     ),
     * )
     */
    public function destroy(string $customer)
    {
        try {
            $customerExist = Customer::find($customer);

            if (!$customerExist) {
                return response()->json(['message' => 'Customer not found.'], 404);
            }

            $this->customerRepository->destroy($customer);
            return response()->noContent();
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Restore a soft-deleted customer.
     * @param  string  $customer
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/api/customers/{customerID}/restore",
     *     summary="Restore a soft-deleted customer by ID",
     *     description="Restore a soft-deleted customer by ID.",
     *     operationId="restoreCustomerById",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="customerID",
     *         in="path",
     *         description="ID of the soft-deleted customer to restore",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer restored successfully.",
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer not found"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         )
     *     ),
     * )
     */
    public function restore(string $customer)
    {
        try {
            $restoreExist = Customer::onlyTrashed()->find($customer);

            if (!$restoreExist) {
                return response()->json(['message' => 'Customer not found.'], 404);
            }

            return $this->customerRepository->restore($customer);
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }
}
