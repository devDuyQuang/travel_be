<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);

        return view('contact.main', compact('contacts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string'],
        ]);

        try {

            Contact::create([
                ...$data,
                'source_domain' => $request->getHost(),
                'status' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gửi liên hệ thành công',
            ]);
        } catch (\Throwable $e) {

            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
            ], 500);
        }
    }

    public function destroy($tenantDomain, $id)
    {
        Contact::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa thành công'
        ]);
    }
}
