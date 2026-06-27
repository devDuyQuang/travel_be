<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;       // ✅ import base Controller
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactLeadMail;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        // Lấy dữ liệu lead
        $fullName = trim($request->input('fullName', $request->input('name', '')));
        $phone    = trim($request->input('phone', ''));
        $note     = trim($request->input('note', $request->input('message', '')));

        $utm_source   = trim($request->input('utm_source', ''));
        $utm_medium   = trim($request->input('utm_medium', ''));
        $utm_campaign = trim($request->input('utm_campaign', ''));
        $landing_url  = trim($request->input('landing_url', ''));

        // Validate tối giản
        $errors = [];
        if ($fullName === '') $errors['fullName'] = 'Vui lòng nhập họ tên.';
        if ($phone === '')    $errors['phone']    = 'Vui lòng nhập số điện thoại.';
        if ($note === '')     $errors['note']     = 'Vui lòng nhập nội dung liên hệ.';
        if ($errors) {
            return response()->json(['message' => 'Validation failed', 'errors' => $errors], 422);
        }

        // ✅ Chỉ gửi cho email này
        $recipient = 'duongvv.thtb@gmail.com'; // trantrungphuoc7@gmail.com

        try {
            // Gửi mail
            Mail::to($recipient)->send(new ContactLeadMail(
                fullName: $fullName,
                phone: $phone,
                note: $note,
                utmSource: $utm_source,
                utmMedium: $utm_medium,
                utmCampaign: $utm_campaign,
                landingUrl: $landing_url,
                ip: $request->ip(),
                appName: config('app.name'),
                buttonText: 'Vào trang chủ',
                buttonUrl: config('app.url')
            ));

            return response()->json(['message' => 'Lead sent successfully.'], 200);

        } catch (\Throwable $e) {
            // Gợi ý: bạn có thể \Log::error($e) để ghi log chi tiết
            return response()->json([
                'message' => 'Exception while sending email',
                'error'   => app()->isLocal() ? $e->getMessage() : 'Mail service error',
            ], 500);
        }
    }
}
