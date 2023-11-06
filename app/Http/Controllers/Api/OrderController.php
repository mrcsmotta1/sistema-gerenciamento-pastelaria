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
     */
    public function index()
    {
        return $this->orderRepository->index();
    }

    /**
     * Store a newly created order in storage.
     *
     * @param \App\Http\Requests\OrderApiRequest $request The order data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response
     */
    public function store(OrderApiRequest $request)
    {
        $order = $this->orderRepository->store($request);

        return $order;
    }

    /**
     * Display the specified order.
     *
     * @param $order The order to display.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $order)
    {
        $result = $this->orderRepository->show($order);

        return $result;
    }

    /**
     * Update the specified order in storage.
     *
     * @param \App\Http\Requests\OrderApiRequest $request The updated order data.
     * @param \App\Models\Order                  $order   The order to update.
     *
     * @return \Illuminate\Http\JsonResponse     A JSON response updated.
     */
    public function update(OrderApiRequest $request, $order)
    {
        $order = Order::find($order);

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        return $this->orderRepository->update($order, $request);
    }

    /**
     * Soft delete the specified order.
     *
     * @param int $order order The ID of the order to soft delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
     */
    public function destroy(int $order)
    {
        $orderExist = Order::find($order);

        if (!$orderExist) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $this->orderRepository->destroy($order);
        return response()->noContent();
    }

    /**
     * Restore a soft-deleted order.
     *
     * @param int $order The ID of the order to restore.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response with restored messsage
     */
    public function restore(int $order)
    {
        $orderExist = Order::withTrashed()->find($order);

        if (!$orderExist) {
            return response()->json(['message' => 'Order not found.'], 404);
        }
        return $this->orderRepository->restore($order);
    }
}
