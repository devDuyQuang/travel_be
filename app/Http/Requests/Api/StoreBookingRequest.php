<?php

namespace App\Http\Requests\Api;

use App\Enums\PaymentMethod;
use App\Enums\BookingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_product_id' => ['required', 'integer', 'exists:products,id'],
            'booking_type' => ['nullable', Rule::in(BookingType::values())],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email:rfc', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30', 'regex:/^(0[0-9]{9,10}|\+84[0-9]{9,10})$/'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'adults' => ['nullable', 'integer', 'min:1', 'max:200'],
            'children' => ['nullable', 'integer', 'min:0', 'max:200'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:200'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['nullable', Rule::in(PaymentMethod::values())],
            'idempotency_key' => ['nullable', 'string', 'max:100'],
            'booking_details' => ['nullable', 'array'],
            'booking_details.pickup_location' => ['nullable', 'string', 'max:255'],
            'booking_details.dropoff_location' => ['nullable', 'string', 'max:255'],
            'booking_details.vehicle_type' => ['nullable', 'string', 'max:255'],
            'booking_details.passengers' => ['nullable', 'integer', 'min:1', 'max:200'],
            'booking_details.room_type' => ['nullable', 'string', 'max:255'],
            'booking_details.rooms' => ['nullable', 'integer', 'min:1', 'max:50'],
            'booking_details.golfers' => ['nullable', 'integer', 'min:1', 'max:200'],
            'extras' => ['nullable', 'array'],
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

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $type = $this->input('booking_type');
            $details = $this->input('booking_details', []);

            if (in_array($type, [BookingType::Tour->value, BookingType::Attraction->value], true)) {
                $guests = (int) $this->input('adults', 0) + (int) $this->input('children', 0);
                if ($guests < 1) {
                    $validator->errors()->add('adults', 'Vui lòng chọn ít nhất một khách.');
                }
            }

            if ($type === BookingType::Transport->value) {
                foreach ([
                    'pickup_location' => 'Vui lòng nhập điểm đón.',
                    'dropoff_location' => 'Vui lòng nhập điểm trả.',
                ] as $field => $message) {
                    if (blank($details[$field] ?? null)) {
                        $validator->errors()->add("booking_details.{$field}", $message);
                    }
                }

                if ((int) ($details['passengers'] ?? 0) < 1) {
                    $validator->errors()->add('booking_details.passengers', 'Vui lòng nhập số hành khách.');
                }
            }

            if ($type === BookingType::Hotel->value && blank($this->input('end_date'))) {
                $validator->errors()->add('end_date', 'Vui lòng chọn ngày trả phòng.');
            }

            if ($type === BookingType::TeeTime->value && blank($this->input('start_time'))) {
                $validator->errors()->add('start_time', 'Vui lòng chọn giờ chơi.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'service_product_id.required' => 'Vui lòng chọn dịch vụ.',
            'service_product_id.exists' => 'Dịch vụ không tồn tại.',
            'booking_type.in' => 'Loại booking không hợp lệ.',
            'customer_name.required' => 'Vui lòng nhập họ tên.',
            'customer_email.required' => 'Vui lòng nhập email.',
            'customer_email.email' => 'Email không đúng định dạng.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'customer_phone.regex' => 'Số điện thoại Việt Nam không hợp lệ.',
            'start_date.required' => 'Vui lòng chọn ngày sử dụng.',
            'start_date.after_or_equal' => 'Ngày sử dụng không được nằm trong quá khứ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'start_time.date_format' => 'Giờ sử dụng không hợp lệ.',
        ];
    }
}
