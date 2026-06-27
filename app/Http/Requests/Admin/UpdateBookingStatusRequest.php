<?php

namespace App\Http\Requests\Admin;

use App\Enums\BookingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(BookingStatus::values())],
            'note' => ['nullable', 'string', 'max:2000'],
            'cancel_reason' => [
                Rule::requiredIf(fn () => $this->input('status') === BookingStatus::Cancelled->value),
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }
}
