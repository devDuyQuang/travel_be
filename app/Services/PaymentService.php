<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class PaymentService
{
    public function createInitialPayment(Model $payable, array $data): Payment
    {
        $method = $data['payment_method'] ?? null;
        $status = $method ? PaymentStatus::Pending->value : PaymentStatus::Unpaid->value;

        return $payable->payments()->create([
            'provider' => 'manual',
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'VND',
            'status' => $status,
            'method' => $method,
        ]);
    }

    public function updateManualPayment(Payment $payment, array $data, ?int $changedBy = null): Payment
    {
        $from = $payment->status instanceof PaymentStatus
            ? $payment->status->value
            : (string) $payment->status;
        $to = $data['payment_status'];

        $payment->fill([
            'status' => $to,
            'method' => $data['payment_method'] ?? $payment->method,
            'transaction_code' => $data['transaction_code'] ?? $payment->transaction_code,
            'paid_at' => $to === PaymentStatus::Paid->value ? ($payment->paid_at ?? now()) : null,
        ])->save();

        if ($from !== $to) {
            $payment->histories()->create([
                'from_status' => $from ?: null,
                'to_status' => $to,
                'changed_by' => $changedBy,
                'note' => $data['note'] ?? null,
            ]);
        }

        $payable = $payment->payable;
        if ($payable) {
            $payable->forceFill([
                'payment_status' => $to,
                'payment_method' => $payment->method,
            ])->save();
        }

        return $payment->fresh('histories');
    }
}
