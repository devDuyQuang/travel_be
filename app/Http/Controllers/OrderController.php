<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Requests\Admin\UpdateInternalNoteRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Requests\Admin\UpdatePaymentRequest;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->select([
                'id',
                'order_code',
                'customer_name',
                'customer_email',
                'customer_phone',
                'total_amount',
                'order_status',
                'payment_status',
                'payment_method',
                'created_at',
            ])
            ->withCount('items')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = trim((string) $request->keyword);
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('order_code', 'like', "%{$keyword}%")
                        ->orWhere('customer_name', 'like', "%{$keyword}%")
                        ->orWhere('customer_email', 'like', "%{$keyword}%")
                        ->orWhere('customer_phone', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('order_status'), fn ($query) => $query->where('order_status', $request->order_status))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->payment_status))
            ->when($request->filled('payment_method'), fn ($query) => $query->where('payment_method', $request->payment_method))
            ->when($request->filled('created_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->created_from))
            ->when($request->filled('created_to'), fn ($query) => $query->whereDate('created_at', '<=', $request->created_to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('order.index', [
            'orders' => $orders,
            'orderStatuses' => OrderStatus::options(),
            'paymentStatuses' => PaymentStatus::options(),
            'paymentMethods' => PaymentMethod::options(),
        ]);
    }

    public function show(string $domain, Order $order): View
    {
        $order->load([
            'items.product:id,name,slug',
            'payments.histories.changer:id,name',
            'histories.changer:id,name',
        ]);

        return view('order.show', [
            'order' => $order,
            'orderStatuses' => OrderStatus::options(),
            'paymentStatuses' => PaymentStatus::options(),
            'paymentMethods' => PaymentMethod::options(),
        ]);
    }

    public function updateStatus(
        UpdateOrderStatusRequest $request,
        string $domain,
        Order $order,
        OrderService $orders
    ): RedirectResponse {
        $orders->transition(
            order: $order,
            toStatus: $request->validated('status'),
            note: $request->validated('note'),
            cancelReason: $request->validated('cancel_reason'),
            changedBy: $request->user()?->id,
        );

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    public function updateInternalNote(UpdateInternalNoteRequest $request, string $domain, Order $order): RedirectResponse
    {
        $order->update($request->validated());

        return back()->with('success', 'Đã cập nhật ghi chú nội bộ.');
    }

    public function updatePayment(
        UpdatePaymentRequest $request,
        string $domain,
        Order $order,
        PaymentService $payments
    ): RedirectResponse {
        $payment = $order->payments()->firstOrCreate([], [
            'provider' => 'manual',
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'status' => $order->payment_status,
            'method' => $order->payment_method,
        ]);

        $payments->updateManualPayment($payment, $request->validated(), $request->user()?->id);

        return back()->with('success', 'Đã cập nhật thanh toán.');
    }
}
