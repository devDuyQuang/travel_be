<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, BookingService $bookings): JsonResponse
    {
        $booking = $bookings->create($request->validated(), $request->user()?->id);

        return response()->json([
            'success' => true,
            'message' => 'Booking đã được gửi thành công. Golfnity sẽ liên hệ xác nhận sớm nhất.',
            'data' => BookingResource::make($booking)->resolve($request),
        ], 201);
    }
}
