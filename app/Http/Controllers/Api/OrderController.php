<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, OrderService $orders): JsonResponse
    {
        $order = $orders->create($request->validated(), $request->user()?->id);

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được tạo thành công.',
            'data' => OrderResource::make($order)->resolve($request),
        ], 201);
    }
}
