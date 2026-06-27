<?php

namespace App\Http\Requests\Api;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email:rfc', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30', 'regex:/^(0[0-9]{9,10}|\+84[0-9]{9,10})$/'],
            'shipping_address_line' => ['required', 'string', 'max:500'],
            'shipping_ward' => ['nullable', 'string', 'max:255'],
            'shipping_district' => ['nullable', 'string', 'max:255'],
            'shipping_province' => ['required', 'string', 'max:255'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['nullable', Rule::in(PaymentMethod::values())],
            'idempotency_key' => ['nullable', 'string', 'max:100'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập họ tên.',
            'customer_email.required' => 'Vui lòng nhập email.',
            'customer_email.email' => 'Email không đúng định dạng.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'customer_phone.regex' => 'Số điện thoại Việt Nam không hợp lệ.',
            'shipping_address_line.required' => 'Vui lòng nhập địa chỉ nhận hàng.',
            'shipping_province.required' => 'Vui lòng nhập tỉnh/thành.',
            'items.required' => 'Giỏ hàng không có sản phẩm.',
            'items.*.product_id.exists' => 'Sản phẩm không tồn tại.',
            'items.*.quantity.min' => 'Số lượng sản phẩm phải lớn hơn 0.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $phone = preg_replace('/[\s().-]+/', '', (string) $this->input('customer_phone'));
        if (str_starts_with($phone, '84')) {
            $phone = '+' . $phone;
        }

        $this->merge([
            'customer_name' => trim((string) $this->input('customer_name')),
            'customer_email' => trim((string) $this->input('customer_email')),
            'customer_phone' => $phone,
        ]);
    }
}
