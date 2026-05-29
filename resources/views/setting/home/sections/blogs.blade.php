<form action="{{ panel_route('setting.updateBlogs') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom bg-light"><h6 class="mb-0">Thông tin chung</h6></div>
        <div class="card-body pt-4">
          <div class="mb-3"><label class="form-label fw-bold">Tiêu đề lớn (H1/H2)</label><textarea name="title" class="form-control" rows="3" placeholder="Stay Informed With Our Latest Health Blogs">{{ $v['title'] ?? '' }}</textarea></div>
          <hr class="my-4">
          <div class="mb-3"><label class="form-label fw-bold">Text nút "Xem tất cả"</label><input type="text" name="view_all_text" class="form-control" value="{{ $v['view_all']['text'] ?? '' }}" placeholder="View All"></div>
          <div class="mb-0"><label class="form-label fw-bold">Link nút "Xem tất cả"</label><input type="text" name="view_all_link" class="form-control" value="{{ $v['view_all']['link'] ?? '' }}" placeholder="#"></div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card shadow-none border bg-transparent h-100">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
          <h6 class="mb-0">Danh sách Blog Nổi bật</h6>
          <button type="button" class="btn btn-sm btn-primary" id="add-blog-item"><i class="ti tabler-plus me-1"></i>Thêm thẻ Blog</button>
        </div>
        <div class="card-body pt-4">
          <div id="home-blogs-container" class="row g-4">
            @foreach($v['items'] ?? [] as $index => $blog)
            <div class="col-md-6 blog-item">
              <div class="p-3 border rounded bg-light position-relative h-100">
                <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-blog-item" style="z-index:1"><i class="ti tabler-trash"></i></button>
                <div class="mb-3">
                  <label class="form-label small fw-bold">Ảnh nền thẻ</label>
                  <input type="file" name="blog_images[{{ $index }}]" class="form-control form-control-sm img-input" data-preview="preview-blog-{{ $index }}">
                  <div class="mt-2 border rounded bg-secondary-subtle d-flex align-items-center justify-content-center" style="height: 120px;">
                    <img src="{{ !empty($blog['image']) ? asset($blog['image']) : '' }}" id="preview-blog-{{ $index }}" class="h-100 w-100 object-fit-cover {{ empty($blog['image']) ? 'd-none' : '' }}">
                    @if(empty($blog['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                  </div>
                </div>
                <div class="mb-2"><label class="form-label small fw-bold">Ngày tháng</label><input type="text" name="items[{{ $index }}][date]" class="form-control form-control-sm" value="{{ $blog['date'] ?? '' }}" placeholder="12 JAN 2025"></div>
                <div class="mb-2"><label class="form-label small fw-bold">Tiêu đề</label><textarea name="items[{{ $index }}][title]" class="form-control form-control-sm" rows="2">{{ $blog['title'] ?? '' }}</textarea></div>
                <div class="row g-2">
                  <div class="col-6"><label class="form-label small fw-bold">Text nút</label><input type="text" name="items[{{ $index }}][link_text]" class="form-control form-control-sm" value="{{ $blog['link_text'] ?? '' }}" placeholder="Read More"></div>
                  <div class="col-6"><label class="form-label small fw-bold">Link</label><input type="text" name="items[{{ $index }}][link]" class="form-control form-control-sm" value="{{ $blog['link'] ?? '' }}" placeholder="#"></div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 mt-4 text-end">
      <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary">Hủy</button>
    </div>
  </div>
</form>
<template id="home-blog-template">
  <div class="col-md-6 blog-item">
    <div class="p-3 border rounded bg-light position-relative h-100">
      <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-blog-item" style="z-index:1"><i class="ti tabler-trash"></i></button>
      <div class="mb-3"><label class="form-label small fw-bold">Ảnh nền thẻ</label><input type="file" name="blog_images[__INDEX__]" class="form-control form-control-sm img-input" data-preview="preview-blog-__INDEX__"><div class="mt-2 border rounded bg-secondary-subtle d-flex align-items-center justify-content-center" style="height: 120px;"><img id="preview-blog-__INDEX__" class="h-100 w-100 object-fit-cover d-none"><i class="ti tabler-photo ti-lg text-muted"></i></div></div>
      <div class="mb-2"><label class="form-label small fw-bold">Ngày tháng</label><input type="text" name="items[__INDEX__][date]" class="form-control form-control-sm"></div>
      <div class="mb-2"><label class="form-label small fw-bold">Tiêu đề</label><textarea name="items[__INDEX__][title]" class="form-control form-control-sm" rows="2"></textarea></div>
      <div class="row g-2"><div class="col-6"><label class="form-label small fw-bold">Text nút</label><input type="text" name="items[__INDEX__][link_text]" class="form-control form-control-sm"></div><div class="col-6"><label class="form-label small fw-bold">Link</label><input type="text" name="items[__INDEX__][link]" class="form-control form-control-sm"></div></div>
    </div>
  </div>
</template>
<div class="modal fade" id="deleteBlogItemModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá thẻ blog này không?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-blog">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<script>
$(function() {
  $(document).on('change', '.img-input', function() {
    var f = this.files[0], pid = $(this).data('preview'), $p = $('#' + pid);
    if (f) { var r = new FileReader(); r.onload = function(e) { $p.attr('src', e.target.result).removeClass('d-none'); $p.siblings('.text-muted').addClass('d-none'); }; r.readAsDataURL(f); }
  });
  $('#add-blog-item').on('click', function() { var i = $('#home-blogs-container .blog-item').length; $('#home-blogs-container').append($('#home-blog-template').html().replace(/__INDEX__/g, i)); });
  var modal = document.getElementById('deleteBlogItemModal') && new bootstrap.Modal(document.getElementById('deleteBlogItemModal'));
  var itemToRemove = null;
  $(document).on('click', '.remove-blog-item', function() { itemToRemove = $(this).closest('.blog-item'); if (modal) modal.show(); });
  $('#confirm-delete-blog').on('click', function() {
    if (itemToRemove) {
      itemToRemove.remove();
      $('#home-blogs-container .blog-item').each(function(idx) {
        $(this).find('[name]').each(function() {
          var n = $(this).attr('name');
          if (n) $(this).attr('name', n.replace(/items\[\d+\]/, 'items[' + idx + ']').replace(/blog_images\[\d+\]/, 'blog_images[' + idx + ']'));
          if ($(this).hasClass('img-input')) { $(this).attr('data-preview', 'preview-blog-' + idx); $(this).siblings('div').find('img').attr('id', 'preview-blog-' + idx); }
        });
      });
    }
    if (modal) modal.hide();
  });
});
</script>
@endpush
