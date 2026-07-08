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
        $result = $bookings->createFromPublicRequest($request->validated(), $request->user()?->id);
        $booking = $result['booking'];

        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu booking đã được tiếp nhận. Golfnity sẽ kiểm tra tình trạng dịch vụ và phản hồi trong thời gian sớm nhất.',
            'data' => BookingResource::make($booking)->resolve($request),
            'meta' => [
                'account_created' => $result['account_created'],
                'mail_dispatched' => $result['mail_dispatched'],
            ],
        ], 201);
    }
}
