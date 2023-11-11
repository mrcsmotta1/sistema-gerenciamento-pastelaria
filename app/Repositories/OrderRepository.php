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

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderCreated;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\OrderApiRequest;

/**
 * Class ProductRepository
 *
 * This Repository handles operations related to Product.
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
class OrderRepository
{
    /**
     * Select All order.
     *
     * @return JsonResponse A JSON response indicating indicating the result of list
     */
    public function index(): JsonResponse
    {
        return DB::transaction(function () {
            $orders = Order::query()
                ->with('items')
                ->get();

            $formattedOrders = $orders->map(
                function ($order) {
                    return [
                        'order_id' => $order->id,
                        'customer_id' => $order->customer_id,
                        'creation_date' => $order->created_at->format('Y-m-d H:i:s'),
                        'products' => $order->items->map(
                            function ($item) {
                                return [
                                    'product_id' => $item->product_id,
                                    'quantity' => $item->quantity,
                                ];
                            }
                        ),
                    ];
                }
            );

            return response()->json($formattedOrders);
        });
    }

    /**
     * Store a newly created order in storage.
     *
     * @param \App\Http\Requests\OrderApiRequest $order The ID of the order.
     *
     * @return JsonResponse A JSON response indicating the result of the update.
     */
    public function store(OrderApiRequest $order): JsonResponse
    {
        $orderSave = new Order(
            [
                'customer_id' => $order->customer_id,
            ]
        );

        $orderSave->save();
        $orderNumber = $orderSave->id;
        $dateOfBirth = Carbon::parse($orderSave->customer->date_of_birth)
            ->format('d/m/Y');

        foreach ($order->products as $productData) {
            $orderItem = new OrderItem(
                [
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                ]
            );

            $orderSave->items()->save($orderItem);
        }

        $message = [
            'message' => 'Order created successfully',
            'order' => $orderSave,
        ];

        Mail::to($orderSave->customer->email)
            ->queue(new OrderCreated($orderSave, $orderNumber, $dateOfBirth));

        return response()->json([$message], Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     *
     * @param int $order The ID of the order to show.
     *
     * @return JsonResponse A JSON response indicating the result of the update.
     */
    public function show(int $order): JsonResponse
    {
        $result = Order::with('items')->find($order);

        if (!$result) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $return = [
            'order_id' => $result['id'],
            'customer_id' => $result->customer_id,
            'creation_date' => $result->created_at->format('Y-m-d H:i:s'),
            'products' => $result->items->map(
                function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                    ];
                }
            ),
        ];

        return response()->json($return);
    }

    /**
     * Update an existing order.
     *
     * @param Order           $order   The order to be updated.
     * @param OrderApiRequest $request The request with updated data.
     *
     * @return JsonResponse A JSON response indicating the result of the update.
     */
    public function update(Order $order, OrderApiRequest $request): JsonResponse
    {
        $order = $order->fill($request->all());

        $updatedProducts = $request->input('products');

        // Mapeie os produtos atualizados pelo ID
        $updatedProductsById = collect($updatedProducts)->keyBy('product_id');

        // Obtenha todos os produtos associados ao pedido
        $existingProducts = $order->items;

        foreach ($existingProducts as $existingProduct) {
            $productId = $existingProduct->product_id;

            if ($updatedProductsById->has($productId)) {
                $productData = $updatedProductsById->get($productId);

                // Verifique se o produto é diferente do original
                if ($productData['quantity'] != $existingProduct->quantity) {
                    // Atualize a quantidade se for diferente
                    $existingProduct->update(
                        [
                            'quantity' => $productData['quantity'],
                        ]
                    );
                }

                $updatedProductsById->forget($productId);
            } else {
                $existingProduct->removed = true;
                $existingProduct->save();
                $existingProduct->delete();
            }
        }

        // Insira novos produtos que não existiam no pedido anteriormente
        $updatedProductsById->each(
            function ($productData) use ($order) {
                $order->items()->create(
                    [
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity'],
                    ]
                );
            }
        );

        $order->refresh();

        $response = [
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'creation_date' => $order->created_at->format('Y-m-d H:i:s'),
            'products' => $order->items->map(
                function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                    ];
                }
            ),
        ];

        return response()->json($response);
    }

    /**
     * Delete a order by their ID.
     *
     * @param int $order The ID of the order to delete.
     *
     * @return void
     */
    public function destroy(int $order): void
    {
        $order = Order::find($order);

        $order->items()->delete();

        $order->delete();
    }

    /**
     * Restores a soft-deleted order by its ID.
     *
     * @param string $order The ID of the order to be restored.
     *
     * @return JsonResponse A JSON response indicating the result of the update.
     */
    public function restore(string $order): JsonResponse
    {
        $restore = Order::withTrashed()->find($order);
        $restore->restore();

        $restore->items()->where('removed', false)->restore();

        return response()->json(['message' => 'Order restored successfully']);
    }
}
