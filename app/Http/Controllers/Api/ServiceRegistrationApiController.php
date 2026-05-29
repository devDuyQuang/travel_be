<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceRegistrationApiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_name' => 'required|string',
            'full_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $registration = ServiceRegistration::create([
            'package_name'  => $request->package_name,
            'package_price' => $request->package_price,
            'full_name'     => $request->full_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'message'       => $request->message,
            'status'        => 'pending',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Đăng ký thành công!',
            'data'    => $registration
        ], 201);
    }
}