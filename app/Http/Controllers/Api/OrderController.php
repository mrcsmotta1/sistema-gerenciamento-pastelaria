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
use App\Http\Requests\OrderApiRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;

/**
 * Class Order
 *
 * Represents a Order in the system.
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
/**
 * @OA\Tag(
 *     name="Order",
 *     description="Product-related operations, including creation, consultation, updating, soft deletion and restoration."
 * )
 */
class OrderController extends Controller
{
    /**
     * Create a new OrderController instance.
     *
     * @param OrderRepository $orderRepository The repository for handling order data
     */
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    /**
     * Retrieve a list of orders with associated items.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with a list of orders.
     *
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Retrieve a list of orders with associated items.",
     *     tags={"Order"},
     *     @OA\Response(
     *         response=200,
     *         description="List of orders.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="order_id", type="integer", example=1),
     *                 @OA\Property(property="customer_id", type="integer", example=2),
     *                 @OA\Property(property="creation_date", type="string", format="date-time", description="Formato: 'Y-m-d H:i:s'"),
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="product_id", type="integer", example=4),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                     )
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        try {
            return $this->orderRepository->index();
        } catch (\Exception $th) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Store a newly created order in storage.
     *
     * @param \App\Http\Requests\OrderApiRequest $request The order data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response
     *
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Store a newly created order with associated items.",
     *     tags={"Order"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_id", "products"},
     *             @OA\Property(property="customer_id", type="integer", example=2),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     required={"product_id", "quantity"},
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                 )
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order created successfully"),
     *             @OA\Property(property="order", type="object",
     *                 @OA\Property(property="customer_id", type="integer", example=2),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-11-10 16:02:22"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-11-10 16:02:22"),
     *                 @OA\Property(property="id", type="integer", example=4),
     *                 @OA\Property(property="customer", type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="John Doee"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="phone", type="string", example="(12)9 1456-7890"),
     *                     @OA\Property(property="date_of_birth", type="string", example="1990-01-01"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="complement", type="string", example="Apt 4B"),
     *                     @OA\Property(property="neighborhood", type="string", example="Downtown"),
     *                     @OA\Property(property="zipcode", type="string", example="12345-678"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-11-10 13:28:46"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-11-10 14:09:44"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null),
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="O campo product_id é obrigatório.")
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         ),
     *     ),
     * )
     */
    public function store(OrderApiRequest $request)
    {
        try {
            $order = $this->orderRepository->store($request);

            return $order;
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Display the specified order.
     *
     * @param $order The order to display.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/orders/{order}",
     *     summary="Retrieve information about a specific order",
     *     tags={"Order"},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="ID of the order to retrieve",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(
     *             @OA\Property(property="order_id", type="integer", example=1),
     *             @OA\Property(property="customer_id", type="integer", example=2),
     *             @OA\Property(property="creation_date", type="string", format="date-time"),
     *             @OA\Property(property="products", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=3),
     *                     @OA\Property(property="quantity", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         ),
     *     ),
     *   )
     * )
     */
    public function show(string $order)
    {
        try {
            $result = $this->orderRepository->show($order);

            return $result;
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Update the specified order in storage.
     *
     * @param \App\Http\Requests\OrderApiRequest $request The updated order data.
     * @param \App\Models\Order                  $order   The order to update.
     *
     * @return \Illuminate\Http\JsonResponse     A JSON response updated.
     *
     * @OA\Put(
     *     path="/api/orders/{orderID}",
     *     summary="Update information about a specific order",
     *     tags={"Order"},
     *     @OA\Parameter(
     *         name="orderID",
     *         in="path",
     *         description="ID of the order to update",
     *         required=true,
     *         example=2,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated order information",
     *         @OA\JsonContent(
     *             @OA\Property(property="order_id", type="integer", example=2),
     *             @OA\Property(property="customer_id", type="integer", example=2),
     *             @OA\Property(property="creation_date", type="string", format="date-time", example="2023-11-05 21:04:25"),
     *             @OA\Property(property="products", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated order details",
     *         @OA\JsonContent(
     *             @OA\Property(property="order_id", type="integer", example=2),
     *             @OA\Property(property="customer_id", type="integer", example=2),
     *             @OA\Property(property="creation_date", type="string", format="date-time"),
     *             @OA\Property(property="products", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=2),
     *                     @OA\Property(property="quantity", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro ao processar a solicitação."),
     *         ),
     *     ),
     *   )
     * )
     */
    public function update(OrderApiRequest $request, $order)
    {
        try {
            $order = Order::find($order);

            if (!$order) {
                return response()->json(['message' => 'Order not found.'], 404);
            }

            return $this->orderRepository->update($order, $request);
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Soft delete the specified order.
     *
     * @param string $order order The ID of the order to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
     *
     * @OA\Delete(
     *     path="/api/orders/{orderID}",
     *     summary="Delete a order by ID",
     *     description="Delete a order by ID (soft delete).",
     *     operationId="orderID",
     *     tags={"Order"},
     *     @OA\Parameter(
     *         name="orderID",
     *         in="path",
     *         description="ID of the order to delete",
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
     *         description="order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order not found"),
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
    public function destroy(string $order)
    {
        try {
            $orderExist = Order::find($order);

            if (!$orderExist) {
                return response()->json(['message' => 'Order not found.'], 404);
            }

            $this->orderRepository->destroy($order);
            return response()->noContent();
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Restore a soft-deleted order.
     *
     * @param int $order The ID of the order to restore.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response with restored messsage
     *
     * @OA\Post(
     *     path="/api/orders/{orderID}/restore",
     *     summary="Restore a soft-deleted order by ID",
     *     description="Restore a soft-deleted order by ID.",
     *     operationId="restoreOrderById",
     *     tags={"Order"},
     *     @OA\Parameter(
     *         name="orderID",
     *         in="path",
     *         description="ID of the soft-deleted order to restore",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order restored successfully.",
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order not found"),
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
    public function restore(string $order)
    {
        try {
            $orderExist = Order::onlyTrashed()->find($order);

            if (!$orderExist) {
                return response()->json(['message' => 'Order not found.'], 404);
            }

            return $this->orderRepository->restore($order);
        } catch (\Exception $e) {
            $message = "Ocorreu um erro ao processar a solicitação.";
            $statusCode = 500;

            return response()->json(['message' => $message], $statusCode);
        }
    }
}
