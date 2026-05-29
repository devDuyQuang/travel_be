@extends('index')
@section('title', page_title())

@section('content')
@php
// Thêm admin. vào module để khớp với route:list: admin.comment.xxx
$module = module();

$editRouteTpl   = panel_route($module.'.edit',    ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);

$editRouteTplJson   = json_encode($editRouteTpl,   JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteTextSafe     = $deleteText ?? 'Xoá';
$deleteTextJson     = json_encode($deleteTextSafe, JSON_UNESCAPED_UNICODE);

// 1. Cập nhật Status Render cho 3 trạng thái
$statusRender = <<<JS
const map = {
    'approved': {t:'Đã duyệt', c:'bg-label-success'},
    'pending':  {t:'Chờ duyệt', c:'bg-label-warning'},
    'hidden':   {t:'Đã ẩn',    c:'bg-label-danger'}
};
const s = map[row.status];
return s ? `<span class="badge \${s.c}">\${s.t}</span>` : row.status;
JS;

// 2. Cập nhật Actions Render (Duyệt nhanh + Xóa)
$actionsRenderByKey = <<<JS
const id = row.id;
const name = row.name;
const status = row.status;
const replies = row.replies || []; // Lấy mảng replies

// Nút mở Modal xem chi tiết
let viewDetailBtn = `
    <a class="dropdown-item btn-view-replies"
       href="javascript:void(0)"
       data-id="\${id}"
       data-replies='\${JSON.stringify(replies)}'>
        <i class="icon-base ti tabler-eye me-2"></i> Xem phản hồi (\${replies.length})
    </a>`;

let quickAction = '';
if (status === 'pending') {
    // Nếu đang chờ duyệt: Chỉ hiện nút Duyệt nhanh
    quickAction = `
        <a class="dropdown-item btn-approve"
           href="javascript:void(0)"
           data-id="\${id}"
           data-bs-toggle="modal"
           data-bs-target="#approveModal">
            <i class="icon-base ti tabler-check me-2"></i> Duyệt nhanh
        </a>
    `;
} else if (status === 'approved') {
    // Nếu đã duyệt: Ẩn Duyệt nhanh, hiện nút Trả lời
    quickAction = `
        <a class="dropdown-item btn-reply-action"
           href="javascript:void(0)"
           data-id="\${id}"
           data-bs-toggle="modal"
           data-bs-target="#replyModal">
            <i class="icon-base ti tabler-arrow-back-up me-2"></i> Trả lời
        </a>
    `;
}


const deleteUrl = {$deleteRouteTplJson}.replace("__ID__", id);

return `
  <div class="dropdown">
    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
      <i class="icon-base ti tabler-dots-vertical"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        \${viewDetailBtn}
        \${quickAction}
        <div class="dropdown-divider"></div>
        <a class="dropdown-item btn-delete text-danger"
           href="javascript:void(0)"
           data-id="\${id}"
           data-name="\${name}"
           data-url="\${deleteUrl}"
           data-bs-toggle="modal"
           data-bs-target="#deleteModal">
            <i class="icon-base ti tabler-trash me-2"></i> {$deleteTextSafe}
        </a>
    </div>
  </div>
`;
JS;

// 3. Thêm Render để cắt chữ cho nội dung bình luận
$contentRender = <<<JS
    if (!data) return '';
    const fullText = data; // Giữ lại nội dung đầy đủ
    const limit = 70; // Giới hạn 70 ký tự
    if (fullText.length <= limit) return fullText;
    // Chỉ cắt khi hiển thị ở dòng của Table
    return `<span title="\${fullText.replace(/"/g, '&quot;')}">\${fullText.substr(0, limit)}...</span>`;
JS;
// 4. Render để hiển thị chi tiết trả lời (nếu có) dưới mỗi bình luận cha

// $detailsRender = <<<JS
// const replies = row.replies;
// // Log toàn bộ mảng replies để kiểm tra cấu hình dữ liệu
// //console.log('Dữ liệu replies của dòng ID ' + row.id + ':', replies);

// if (!replies || replies.length === 0) return '';

// let html = '<ul class="list-unstyled ms-4 mt-2 p-2 border-start border-primary border-3 bg-light">';

// $.each(replies, function(index, reply) {
//     // Log cụ thể từng nội dung reply
//     console.log(`Reply index \${index}:`, reply.content);

//     html += `
//         <li class="mb-2 pb-2 border-bottom text-start">
//             <div class="d-flex justify-content-between">
//                 <strong><i class="ti tabler-corner-down-right me-1"></i> \${reply.name} (Admin)</strong>
//                 <small class="text-muted">\${reply.created_at}</small>
//             </div>
//             <div class="text-secondary small mt-1">\${reply.content}</div>
//         </li>`;
// });

// html += '</ul>';
// return html;
// JS;

$options = [
    'control' => true,
    'order'   => [[3,'desc']], // Sắp xếp theo ngày tạo mới nhất
    'rendersByKey' => [
        'status' => $statusRender,
        'actions' => $actionsRenderByKey,
        'content' => $contentRender,
        '__details' => "return '';",
        ],
    'columnDefs' => [
        // Cột Trạng thái (Index 4)
        ['targets' => 4, 'className' => 'text-center'],
        // Cột actions là index 5
        ['targets' => 5, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center'],
        // Cột __details là index 6 (Cột cuối cùng)
        ['targets' => 6, 'className' => 'none'],
    ],
    'responsiveModal'       => false,
    'responsiveHeaderField' => 'name',
    'modalFields' => ['name', 'content', 'created_at', 'status'],
    'modalRenders'          => [
        'status' => $statusRender,
    ],
    'searchPlaceholder' => 'Nhập từ khóa...',
];
@endphp

<x-table-header :title="page_title()" icon="tabler-message" :create-route="panel_route($module.'.create')" />

<x-data-table
    id="reload-table"
    :columns="[
        ['key'=>'name','title'=>'Người gửi'],
        ['key'=>'content','title'=>'Nội dung'],
        ['key'=>'created_at','title'=>'Ngày tạo'],
        ['key'=>'status','title'=>'Trạng thái'],
        ['key'=>'actions','title'=>'Thao tác'],
        ['key'=>'__details','title'=>'', 'className'=>'none'] // Cột ẩn để chứa dữ liệu chi tiết (replies)
    ]"
    ajax-url="{{ panel_route($module.'.datatable') }}"
    :options="$options"
/>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="margin-top: 30px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận duyệt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn phê duyệt bình luận này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmApproveBtn">Đồng ý</button>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal (Top Center) -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" style="margin-top: 30px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trả lời bình luận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nội dung trả lời</label>
                    <textarea id="replyContent" class="form-control" rows="4" placeholder="Nhập nội dung phản hồi..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmReplyBtn">Gửi trả lời</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem danh sách câu trả lời -->
<div class="modal fade" id="viewRepliesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết bình luận & Trả lời</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="parentCommentArea" class="mb-3 p-3 bg-lighter rounded">
                    <!-- Nội dung bình luận cha sẽ hiện ở đây -->
                </div>
                <h6>Các phản hồi:</h6>
                <div id="repliesListArea">
                    <!-- Danh sách reply sẽ render ở đây -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function($) {
    "use strict";

    // Biến dùng chung để quản lý trạng thái thao tác
    let commentAction = {
        id: null,
        url: null,
        btnToggle: null,
        originalIcon: null
    };

    /**
     * PHẦN 1: DUYỆT BÌNH LUẬN (Approve)
     */
    $(document).on('click', '.btn-approve', function(e) {
        e.preventDefault();
        const _this = $(this);
        const id = _this.attr('data-id');

        if (!id) {
            const msg = 'Không tìm thấy ID bình luận!';
            if (typeof window.toastError === 'function') window.toastError(msg); else alert(msg);
            return;
        }

        // Lưu thông tin vào biến tạm
        commentAction.id = id;
        commentAction.url = "{{ panel_route('admin.comment.approve', ['id' => '__ID__']) }}".replace('__ID__', id);
        commentAction.btnToggle = _this.closest('.dropdown').find('.dropdown-toggle');
        commentAction.originalIcon = commentAction.btnToggle.html();

        $('#approveModal').modal('show');
    });

    $('#confirmApproveBtn').on('click', function() {
        const $confirmBtn = $(this);
        const $modal = $('#approveModal');

        $confirmBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
        commentAction.btnToggle.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: commentAction.url,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $modal.modal('hide');
                    if (typeof window.toastSuccess === 'function') {
                        window.toastSuccess(res.message || 'Duyệt thành công.');
                    }
                    if ($.fn.DataTable.isDataTable('#reload-table')) {
                        $('#reload-table').DataTable().ajax.reload(null, false);
                    }
                } else {
                    if (typeof window.toastError === 'function') window.toastError(res.message);
                    commentAction.btnToggle.prop('disabled', false).html(commentAction.originalIcon);
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Lỗi hệ thống khi duyệt.';
                if (typeof window.toastError === 'function') window.toastError(errorMsg); else alert(errorMsg);
                commentAction.btnToggle.prop('disabled', false).html(commentAction.originalIcon);
            },
            complete: function() {
                $confirmBtn.prop('disabled', false).html('Đồng ý');
            }
        });
    });

    /**
     * PHẦN 2: TRẢ LỜI BÌNH LUẬN (Reply)
     */
    $(document).on('click', '.btn-reply-action', function(e) {
        e.preventDefault();
        commentAction.id = $(this).attr('data-id');
        $('#replyContent').val('');
        $('#replyModal').modal('show');
    });

    $('#confirmReplyBtn').on('click', function() {
        const $btn = $(this);
        const content = $('#replyContent').val();

        if (!content.trim()) {
            if (typeof window.toastError === 'function') window.toastError('Vui lòng nhập nội dung trả lời!');
            return;
        }

        const url = "{{ panel_route('admin.comment.reply', ['id' => '__ID__']) }}".replace('__ID__', commentAction.id);

        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Đang gửi...');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                content: content
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#replyModal').modal('hide');
                    if (typeof window.toastSuccess === 'function') window.toastSuccess(res.message);
                    if ($.fn.DataTable.isDataTable('#reload-table')) {
                        $('#reload-table').DataTable().ajax.reload(null, false);
                    }
                } else {
                    if (typeof window.toastError === 'function') window.toastError(res.message);
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Lỗi hệ thống khi trả lời.';
                if (typeof window.toastError === 'function') window.toastError(errorMsg); else alert(errorMsg);
            },
            complete: function() {
                $btn.prop('disabled', false).html('Gửi trả lời');
            }
        });
    });

    /** 3. XEM CHI TIẾT REPLIES */
    $(document).on('click', '.btn-view-replies', function(e) {
        e.preventDefault();
        const _this = $(this);
        const table = $('#reload-table').DataTable();

        let tr = _this.closest('tr');
        if (tr.hasClass('child')) {
            tr = tr.prev();
        }

        const rowData = table.row(tr).data();

        // --- PHẦN CHECK LOG ---
        console.log('Button Clicked!');
        console.log('Row Data:', rowData); // Kiểm tra xem rowData có tồn tại không
        if (rowData) {
            console.log('Replies Data:', rowData.replies); // Kiểm tra mảng replies có dữ liệu không
        }
        // ----------------------


    if (!rowData) {
        console.error('Không tìm thấy dữ liệu dòng (rowData is undefined)');
        return;
    }

        // 1. Đổ nội dung bình luận cha
        var parentHtml = '<div class="mb-2"><strong><i class="ti tabler-user me-1"></i> ' + rowData.name + '</strong></div>' +
                     '<div class="p-3 bg-white border rounded shadow-sm">' + rowData.content + '</div>' +
                     '<div class="mt-2 small text-muted"><i class="ti tabler-calendar me-1"></i> ' + rowData.created_at + '</div>';

        $('#parentCommentArea').html(parentHtml);

        // 2. Đổ danh sách replies
        var repliesHtml = '';
        if (rowData.replies && rowData.replies.length > 0) {
            $.each(rowData.replies, function(i, r) {
                repliesHtml += '<div class="ms-4 mt-3 p-3 border-start border-primary border-3 bg-light rounded text-start">' +
                                    '<div class="d-flex justify-content-between align-items-center mb-1">' +
                                        '<span class="fw-bold text-primary"><i class="ti tabler-corner-down-right me-1"></i> ' + r.name + ' (Admin)</span>' +
                                        '<small class="text-muted">' + r.created_at + '</small>' +
                                    '</div>' +
                                    '<div class="text-dark small">' + r.content + '</div>' +
                            '</div>';
            });
        } else {
            repliesHtml = '<div class="text-center p-4 text-muted border rounded mt-3">Chưa có phản hồi nào.</div>';
        }

        $('#repliesListArea').html(repliesHtml);
        $('#viewRepliesModal').modal('show');
    });

    // Reset dữ liệu khi đóng các modal
    $('#approveModal, #replyModal').on('hidden.bs.modal', function () {
        commentAction.id = null;
    });

})(jQuery);
</script>
@endpush
