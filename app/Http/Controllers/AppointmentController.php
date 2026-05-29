<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
{
    /**
     * Hiển thị trang danh sách (View)
     */
    public function index()
    {
        // Trả về view, dữ liệu sẽ được load qua AJAX datatable hoặc paginate tùy anh chọn
        // Ở đây em vẫn lấy stats để hiển thị trên đầu trang cho đẹp
        $stats = [
            'total'   => Appointment::count(),
            'pending' => Appointment::where('status', 0)->count(),
        ];

        return view(module() . '.main', compact('stats'));
    }

    public function datatable(Request $request)
    {
        $draw = (int) $request->get('draw', 1);
        $start = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 25);
        if ($length === -1) {
            $length = 100;
        }

        $searchValue = trim((string) $request->input('search.value', ''));

        $query = Appointment::select([
            'id',
            'name',
            'email',
            'phone',
            'service',
            'message',
            'status',
            'created_at',
        ]);

        // Tìm kiếm
        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('phone', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%")
                    ->orWhere('service', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = Appointment::count();
        $recordsFiltered = !empty($searchValue) ? (clone $query)->count() : $recordsTotal;

        $items = (clone $query)
            ->orderByDesc('id')
            ->offset($start)
            ->limit($length)
            ->get();

       $rows = $items->map(function ($item, $index) use ($start) {
    return [
        'DT_RowIndex' => $start + $index + 1,
        'id'         => $item->id,
        'name'       => $item->name,
        'contact'    => '',
        'email'      => $item->email,
        'phone'      => $item->phone,
        'service'    => $item->service ?? 'Tư vấn chung',
        'message'    => $item->message,
        'status'     => $item->status,
        'status_label' => match($item->status) {
            1 => '<span class="badge bg-label-success">Đã xác nhận</span>',
            2 => '<span class="badge bg-label-danger">Đã hủy</span>',
            default => '<span class="badge bg-label-warning">Chờ xử lý</span>',
        },
        'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
        'actions'    => '',
        '__details'  => '',
    ];
})->values();

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }

    /**
     * Lưu dữ liệu (Dùng chung cho cả Admin hoặc nhận từ Form request)
     */
    public function store(Request $request)
    {

        // Định nghĩa luật kiểm tra
        $validator = Validator::make($request->all(), [
            'dzName'        => 'required|string|min:3|max:255',
            'dzEmail'       => 'required|email|max:255', // Cho phép trống, nhưng nếu nhập phải là email
            'dzPhoneNumber' => [
                'required',
                'regex:/^(0|\+84)[3|5|7|8|9][0-9]{8}$/' // Regex chuẩn số điện thoại VN
            ],
            'dzService'     => 'required|string|not_in:Chọn dịch vụ',
            'dzMessage'     => 'nullable|string|max:1000',
        ], [
            // Thông báo lỗi tiếng Việt
            'dzName.required'        => 'Họ tên không được để trống.',
            'dzPhoneNumber.required' => 'Số điện thoại là bắt buộc.',
            'dzPhoneNumber.regex'    => 'Số điện thoại không đúng định dạng.',
            'dzEmail.email'          => 'Định dạng email không hợp lệ.',
            'dzService.not_in'       => 'Vui lòng chọn một dịch vụ cụ thể.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'name'    => $request->dzName,
            'email'   => $request->dzEmail,
            'phone'   => $request->dzPhoneNumber,
            'service' => $request->dzService,
            'message' => $request->dzMessage,
            'status'  => 0, // Mặc định chờ xử lý
        ];

        Appointment::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký lịch hẹn thành công!'
        ]);

        // $request->validate([
        //     'name'  => 'required|string|max:255',
        //     'phone' => 'required|string|max:20',
        // ]);

        // Appointment::create($request->all());

        // return redirect()->route('appointment.index')->with('success', 'Thêm lịch hẹn thành công');
    }

    /**
     * Trang chỉnh sửa
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view(module() . '.edit', compact('appointment'));
    }

    /**
     * Cập nhật thông tin và Trạng thái
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Nếu request có status, ta cập nhật status (cho nút xác nhận nhanh)
        // Nếu không, ta cập nhật toàn bộ form edit
        $appointment->update($request->all());

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Cập nhật thành công']);
        }

        return redirect()->route('appointment.index')->with('success', 'Cập nhật lịch hẹn thành công');
    }

    /**
     * Xóa lịch hẹn
     * Tham số $domain sẽ nhận giá trị 'localhost'
     * Tham số $id sẽ nhận giá trị ID thực của appointment
     */
    public function destroy($domain, $id)
    {
        // Tìm theo ID thực sự (tham số thứ 2)
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'status' => 'error',
                'message' => "Không tìm thấy Appointment với ID: $id (Domain: $domain)"
            ], 404);
        }

        $appointment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa thành công!'
        ]);
    }
}