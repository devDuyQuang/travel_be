@extends('index')
@section('title', page_title())

@section('content')
@include('partials.css.comment')

@php
$module = module();

$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteTextSafe = $deleteText ?? 'Xoá';

$statusRender = <<<'JS'
  if (type !== 'display') return data;

  const map = {
    approved: {
      text: 'Đã duyệt',
      className: 'comment-status-approved'
    },
    pending: {
      text: 'Chờ duyệt',
      className: 'comment-status-pending'
    },
    hidden: {
      text: 'Đã ẩn',
      className: 'comment-status-hidden'
    }
  };

  const item = map[row.status];

  if (!item) {
    return '<span class="comment-status-badge comment-status-hidden">Không rõ</span>';
  }

  return '<span class="comment-status-badge ' + item.className + '">' + item.text + '</span>';
JS;

$contentRender = <<<'JS'
  if (type !== 'display') return data;

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const fullText = String(data ?? '');
  const limit = 90;
  const text = fullText.length > limit ? fullText.substring(0, limit) + '...' : fullText;

  return '<span class="comment-content-text" title="' + esc(fullText) + '">' + esc(text) + '</span>';
JS;

$dateRender = <<<'JS'
  if (type !== 'display') return data;

  return data || '—';
JS;

$actionsRenderByKey = <<<JS
  if (type !== 'display') return '';

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const id = row.id;
  const name = row.name || '';
  const status = row.status || '';
  const replies = Array.isArray(row.replies) ? row.replies : [];
  const deleteUrl = {$deleteRouteTplJson}.replace('__ID__', id);

  let quickAction = '';

  if (status === 'pending') {
    quickAction =
      '<a class="dropdown-item btn-approve" href="javascript:void(0)" data-id="' + esc(id) + '">' +
        '<span class="material-icons-outlined me-2" style="font-size:18px;">check_circle</span>' +
        'Duyệt nhanh' +
      '</a>';
  }

  if (status === 'approved') {
    quickAction =
      '<a class="dropdown-item btn-reply-action" href="javascript:void(0)" data-id="' + esc(id) + '">' +
        '<span class="material-icons-outlined me-2" style="font-size:18px;">reply</span>' +
        'Trả lời' +
      '</a>';
  }

  return '' +
    '<div class="comment-action-wrap">' +
      '<div class="dropdown">' +
        '<button type="button" class="comment-action-btn dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">' +
          '<span class="material-icons-outlined">more_vert</span>' +
        '</button>' +

        '<div class="dropdown-menu dropdown-menu-end">' +
          '<a class="dropdown-item btn-view-replies" href="javascript:void(0)" data-id="' + esc(id) + '">' +
            '<span class="material-icons-outlined me-2" style="font-size:18px;">visibility</span>' +
            'Xem phản hồi (' + replies.length + ')' +
          '</a>' +

          quickAction +

          '<div class="dropdown-divider"></div>' +

          '<a class="dropdown-item btn-delete text-danger" href="javascript:void(0)" ' +
            'data-id="' + esc(id) + '" ' +
            'data-name="' + esc(name) + '" ' +
            'data-url="' + esc(deleteUrl) + '" ' +
            'data-bs-toggle="modal" ' +
            'data-bs-target="#deleteModal">' +
            '<span class="material-icons-outlined me-2" style="font-size:18px;">delete</span>' +
            '{$deleteTextSafe}' +
          '</a>' +
        '</div>' +
      '</div>' +
    '</div>';
JS;

$options = [
  'control' => false,
  'order' => [[2, 'desc']],
  'responsive' => false,
  'responsiveModal' => false,
  'autoWidth' => false,
  'scrollX' => false,
  'pageLength' => 10,
  'pagingType' => 'full_numbers',
  'searchPlaceholder' => 'Nhập từ khóa...',

  'language' => [
    'lengthMenu' => 'Hiển thị _MENU_ dòng',
    'search' => 'Tìm kiếm:',
    'info' => 'Hiển thị _START_ đến _END_ của _TOTAL_ dòng',
    'infoEmpty' => 'Hiển thị 0 đến 0 của 0 dòng',
    'infoFiltered' => '(lọc từ _MAX_ dòng)',
    'zeroRecords' => 'Không tìm thấy dữ liệu phù hợp',
    'emptyTable' => 'Không có dữ liệu',
    'paginate' => [
      'first' => '«',
      'previous' => '‹',
      'next' => '›',
      'last' => '»',
    ],
  ],

  'rendersByKey' => [
    'content' => $contentRender,
    'created_at' => $dateRender,
    'status' => $statusRender,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
    ['targets' => 0, 'className' => 'text-start comment-name-col'],
    ['targets' => 1, 'className' => 'text-start comment-content-col'],
    ['targets' => 2, 'className' => 'text-center text-nowrap comment-date-col'],
    ['targets' => 3, 'className' => 'text-center text-nowrap comment-status-col'],
    ['targets' => 4, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap comment-action-col'],
  ],
];
@endphp

<main class="main-wrapper comment-list-page">
  <div class="main-content">

    <div class="comment-page-header">
      <h5 class="comment-page-title">
        <span class="material-icons-outlined">forum</span>
        {{ page_title() }}
      </h5>
    </div>

    <div class="comment-table-card">
      <x-data-table
        id="reload-table"
        :columns="[
          ['key' => 'name', 'title' => 'NGƯỜI GỬI'],
          ['key' => 'content', 'title' => 'NỘI DUNG'],
          ['key' => 'created_at', 'title' => 'NGÀY TẠO'],
          ['key' => 'status', 'title' => 'TRẠNG THÁI'],
          ['key' => 'actions', 'title' => 'THAO TÁC'],
        ]"
        ajax-url="{{ panel_route($module.'.datatable') }}"
        :options="$options"
      />
    </div>

  </div>
</main>

<div class="modal fade comment-modal" id="approveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" style="margin-top: 30px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận duyệt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <div class="modal-body">
        Bạn có chắc chắn muốn duyệt bình luận này không?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" id="confirmApproveBtn">Đồng ý</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade comment-modal" id="replyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md" style="margin-top: 30px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Trả lời bình luận</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
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

<div class="modal fade comment-modal" id="viewRepliesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 30px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chi tiết bình luận & phản hồi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <div class="modal-body">
        <div id="parentCommentArea" class="comment-parent-box mb-3 p-3"></div>

        <h6 class="mb-3">Các phản hồi</h6>

        <div id="repliesListArea"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function($) {
  'use strict';

  let commentAction = {
    id: null,
    url: null,
    btnToggle: null,
    originalIcon: null
  };

  function escapeHtml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  $(document).on('click', '.btn-approve', function(e) {
    e.preventDefault();

    const $this = $(this);
    const id = $this.attr('data-id');

    if (!id) {
      if (typeof window.toastError === 'function') {
        window.toastError('Không tìm thấy ID bình luận.');
      }
      return;
    }

    commentAction.id = id;
    commentAction.url = "{{ panel_route('admin.comment.approve', ['id' => '__ID__']) }}".replace('__ID__', id);
    commentAction.btnToggle = $this.closest('.dropdown').find('.comment-action-btn');
    commentAction.originalIcon = commentAction.btnToggle.html();

    $('#approveModal').modal('show');
  });

  $('#confirmApproveBtn').on('click', function() {
    const $confirmBtn = $(this);
    const $modal = $('#approveModal');

    if (!commentAction.url) return;

    $confirmBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
    commentAction.btnToggle.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
      url: commentAction.url,
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}'
      },
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
          if (typeof window.toastError === 'function') {
            window.toastError(res.message || 'Không thể duyệt bình luận.');
          }

          commentAction.btnToggle.prop('disabled', false).html(commentAction.originalIcon);
        }
      },
      error: function(xhr) {
        const errorMsg = xhr.responseJSON?.message || 'Lỗi hệ thống khi duyệt.';

        if (typeof window.toastError === 'function') {
          window.toastError(errorMsg);
        }

        commentAction.btnToggle.prop('disabled', false).html(commentAction.originalIcon);
      },
      complete: function() {
        $confirmBtn.prop('disabled', false).html('Đồng ý');
      }
    });
  });

  $(document).on('click', '.btn-reply-action', function(e) {
    e.preventDefault();

    commentAction.id = $(this).attr('data-id');

    $('#replyContent').val('');
    $('#replyModal').modal('show');
  });

  $('#confirmReplyBtn').on('click', function() {
    const $btn = $(this);
    const content = $('#replyContent').val();

    if (!String(content).trim()) {
      if (typeof window.toastError === 'function') {
        window.toastError('Vui lòng nhập nội dung trả lời.');
      }
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

          if (typeof window.toastSuccess === 'function') {
            window.toastSuccess(res.message || 'Gửi trả lời thành công.');
          }

          if ($.fn.DataTable.isDataTable('#reload-table')) {
            $('#reload-table').DataTable().ajax.reload(null, false);
          }
        } else {
          if (typeof window.toastError === 'function') {
            window.toastError(res.message || 'Không thể gửi trả lời.');
          }
        }
      },
      error: function(xhr) {
        const errorMsg = xhr.responseJSON?.message || 'Lỗi hệ thống khi trả lời.';

        if (typeof window.toastError === 'function') {
          window.toastError(errorMsg);
        }
      },
      complete: function() {
        $btn.prop('disabled', false).html('Gửi trả lời');
      }
    });
  });

  $(document).on('click', '.btn-view-replies', function(e) {
    e.preventDefault();

    const table = $('#reload-table').DataTable();
    let tr = $(this).closest('tr');

    if (tr.hasClass('child')) {
      tr = tr.prev();
    }

    const rowData = table.row(tr).data();

    if (!rowData) {
      if (typeof window.toastError === 'function') {
        window.toastError('Không tìm thấy dữ liệu bình luận.');
      }
      return;
    }

    const parentHtml =
      '<div class="mb-2"><strong>' + escapeHtml(rowData.name) + '</strong></div>' +
      '<div>' + escapeHtml(rowData.content) + '</div>' +
      '<div class="mt-2 small text-muted">' + escapeHtml(rowData.created_at) + '</div>';

    $('#parentCommentArea').html(parentHtml);

    let repliesHtml = '';

    if (Array.isArray(rowData.replies) && rowData.replies.length > 0) {
      $.each(rowData.replies, function(i, reply) {
        repliesHtml +=
          '<div class="comment-reply-box p-3 mb-3 text-start">' +
            '<div class="d-flex justify-content-between align-items-center mb-1">' +
              '<span class="fw-bold text-info">' + escapeHtml(reply.name || 'Admin') + ' (Admin)</span>' +
              '<small class="text-muted">' + escapeHtml(reply.created_at || '') + '</small>' +
            '</div>' +
            '<div class="small">' + escapeHtml(reply.content || '') + '</div>' +
          '</div>';
      });
    } else {
      repliesHtml = '<div class="text-center p-4 text-muted border rounded mt-3">Chưa có phản hồi nào.</div>';
    }

    $('#repliesListArea').html(repliesHtml);
    $('#viewRepliesModal').modal('show');
  });

  $('#approveModal, #replyModal').on('hidden.bs.modal', function() {
    commentAction.id = null;
    commentAction.url = null;
    commentAction.btnToggle = null;
    commentAction.originalIcon = null;
  });

})(jQuery);
</script>
@endpush