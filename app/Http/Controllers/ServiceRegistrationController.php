<?php

namespace App\Http\Controllers;

use App\Models\ServiceRegistration;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ServiceRegistrationController extends Controller
{
    public function index()
    {
        return view(module() . '.main');
    }

    /**
     * Datatable Ajax cho danh sách đăng ký gói điều trị (Manual)
     */
    public function datatable(Request $request)
    {
        $draw = (int) $request->get('draw', 1);
        $start = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 25);

        if ($length === -1) {
            $length = 100;
        }

        $searchValue = trim((string) $request->input('search.value', ''));

        // Khởi tạo Query với các trường cần thiết
        $query = ServiceRegistration::select([
            'id',
            'full_name',
            'email',
            'phone',
            'package_name',
            'package_price',
            'message',
            'status',
            'created_at',
        ]);

        // Xử lý tìm kiếm (Search)
        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('full_name', 'like', "%{$searchValue}%")
                    ->orWhere('phone', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%")
                    ->orWhere('package_name', 'like', "%{$searchValue}%");
            });
        }

        // Đếm tổng số bản ghi
        $recordsTotal = ServiceRegistration::count();
        $recordsFiltered = !empty($searchValue) ? (clone $query)->count() : $recordsTotal;

        // Lấy dữ liệu phân trang
        $items = (clone $query)
            ->orderByDesc('id')
            ->offset($start)
            ->limit($length)
            ->get();

        // Map lại dữ liệu để trả về JSON
        $rows = $items->map(function ($item) {
            return [
                'id'            => $item->id,
                'full_name'     => $item->full_name,
                'email'         => $item->email,
                'phone'         => $item->phone,
                'package_name'  => $item->package_name,
                'package_price' => $item->package_price ?? 'Liên hệ',
                'message'       => $item->message,
                'status'        => $item->status,

                // Map Label theo đúng class Tailwind/Bootstrap của anh
                'status_label'  => match($item->status) {
                    'contacted' => '<span class="badge bg-label-success">Đã liên hệ</span>',
                    'canceled'  => '<span class="badge bg-label-danger">Đã hủy</span>',
                    default     => '<span class="badge bg-label-warning">Chờ xử lý</span>',
                },

                // Format ngày tháng theo chuẩn ISO để FE xử lý hoặc format thủ công d/m/Y
                'created_at'    => $item->created_at ? $item->created_at->format('d/m/Y H:i') : null,
                '__details'     => '', // Trường ẩn cho Responsive DataTables
            ];
        })->values();

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }

    public function edit($id)
    {
        $item = ServiceRegistration::findOrFail($id);
        return view('admin.service_registrations.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = ServiceRegistration::findOrFail($id);

        $item->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
            // Cho phép sửa cả thông tin khách hàng nếu cần
            'full_name'  => $request->full_name,
            'phone'      => $request->phone,
        ]);

        return redirect()->route('service-registrations.index')->with('success', 'Cập nhật thành công!');
    }

    // public function destroy($id)
    // {
    //     $item = ServiceRegistration::findOrFail($id);
    //     $item->delete();

    //     return response()->json(['status' => 'success', 'message' => 'Đã xóa bản ghi']);
    // }

        public function destroy($domain, $id)
    {
        // Tìm theo ID thực sự (tham số thứ 2)
        $appointment = ServiceRegistration::find($id);

        if (!$appointment) {
            return response()->json([
                'status' => 'error',
                'message' => "Không tìm thấy ServiceRegistration với ID: $id (Domain: $domain)"
            ], 404);
        }

        $appointment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa thành công!'
        ]);
    }
}