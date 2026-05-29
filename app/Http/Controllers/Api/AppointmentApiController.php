<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentApiController extends Controller
{
    public function store(Request $request)
    {
        // Mapping từ field của FE (dzName...) sang field Database (name...)
        $validator = Validator::make($request->all(), [
            'dzName'        => 'required|string|max:255',
            'dzEmail'       => 'nullable|email',
            'dzPhoneNumber' => 'required|string|max:20',
            'dzService'     => 'nullable|string',
            'dzMessage'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $appointment = Appointment::create([
                'name'    => $request->dzName,
                'email'   => $request->dzEmail,
                'phone'   => $request->dzPhoneNumber,
                'service' => $request->dzService,
                'message' => $request->dzMessage,
                'status'  => 0, // Mặc định chờ xử lý
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Đăng ký lịch hẹn thành công!',
                'data'    => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
