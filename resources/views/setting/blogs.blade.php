@extends('index')

@section('content')
<h5 class="card-header">Tin tức / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateBlogs') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Section Info -->
        <div class="col-md-4">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Thông tin chung</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề lớn (H1/H2)</label>
                        <textarea name="title" class="form-control" rows="3" placeholder="Stay Informed With Our Latest Health Blogs">{{ $v['title'] ?? '' }}</textarea>
                    </div>
                    <hr class="my-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Text nút "Xem tất cả"</label>
                        <input type="text" name="view_all_text" class="form-control" value="{{ $v['view_all']['text'] ?? '' }}" placeholder="View All">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Link nút "Xem tất cả"</label>
                        <input type="text" name="view_all_link" class="form-control" value="{{ $v['view_all']['link'] ?? '' }}" placeholder="#">
                    </div>
                </div>
            </div>
        </div>

        <!-- Blogs List -->
        <div class="col-md-8">
            <div class="card shadow-none border h-100">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0">Danh sách Blog Nổi bật</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-blog"><i class="ti tabler-plus me-1"></i>Thêm thẻ Blog</button>
                </div>
                <div class="card-body pt-4">
                    <div id="blogs-container" class="row g-4">
                        @foreach($v['items'] ?? [] as $index => $blog)
                        <div class="col-md-6 blog-item">
                            <div class="p-3 border rounded bg-light position-relative h-100">
                                <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item z-index-1"><i class="ti tabler-trash"></i></button>
                                
                                <div class="mb-3 position-relative">
                                    <label class="form-label small fw-bold">Ảnh nền thẻ</label>
                                    <input type="file" name="blog_images[{{ $index }}]" class="form-control form-control-sm img-input" data-preview="preview-blog-{{ $index }}">
                                    <div class="mt-2 border rounded bg-secondary-subtle d-flex align-items-center justify-content-center" style="height: 120px; overflow: hidden;">
                                        <img src="{{ !empty($blog['image']) ? asset($blog['image']) : '' }}" id="preview-blog-{{ $index }}" class="h-100 w-100 object-fit-cover {{ empty($blog['image']) ? 'd-none' : '' }}">
                                        @if(empty($blog['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Ngày tháng</label>
                                    <input type="text" name="items[{{ $index }}][date]" class="form-control form-control-sm" value="{{ $blog['date'] ?? '' }}" placeholder="12 JAN 2025">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Tiêu đề</label>
                                    <textarea name="items[{{ $index }}][title]" class="form-control form-control-sm" rows="2">{{ $blog['title'] ?? '' }}</textarea>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Text nút</label>
                                        <input type="text" name="items[{{ $index }}][link_text]" class="form-control form-control-sm" value="{{ $blog['link_text'] ?? '' }}" placeholder="Read More">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Link</label>
                                        <input type="text" name="items[{{ $index }}][link]" class="form-control form-control-sm" value="{{ $blog['link'] ?? '' }}" placeholder="#">
                                    </div>
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
</div>

<!-- Templates -->
<template id="blog-template">
    <div class="col-md-6 blog-item">
        <div class="p-3 border rounded bg-light position-relative h-100">
            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item z-index-1"><i class="ti tabler-trash"></i></button>
            <div class="mb-3 position-relative">
                <label class="form-label small fw-bold">Ảnh nền thẻ (Background Image)</label>
                <input type="file" name="blog_images[__INDEX__]" class="form-control form-control-sm img-input" data-preview="preview-blog-__INDEX__">
                <div class="mt-2 border rounded bg-secondary-subtle d-flex align-items-center justify-content-center" style="height: 180px; overflow: hidden;">
                    <img id="preview-blog-__INDEX__" class="h-100 w-100 object-fit-cover d-none">
                    <i class="ti tabler-photo ti-lg text-muted"></i>
                </div>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">Ngày tháng (Date Badge)</label>
                <input type="text" name="items[__INDEX__][date]" class="form-control form-control-sm">
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">Tiêu đề bài viết</label>
                <textarea name="items[__INDEX__][title]" class="form-control form-control-sm" rows="2"></textarea>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <label class="form-label small fw-bold">Text action button</label>
                    <input type="text" name="items[__INDEX__][link_text]" class="form-control form-control-sm">
                </div>
                <div class="col-6">
                    <label class="form-label small fw-bold">Link bài viết</label>
                    <input type="text" name="items[__INDEX__][link]" class="form-control form-control-sm">
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteBlogItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá thẻ blog này không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-blog">Xoá</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Preview logic
    $(document).on('change', '.img-input', function() {
        const file = this.files[0];
        const previewId = $(this).data('preview');
        const $preview = $('#' + previewId);
        const $placeholder = $preview.siblings('.ti-lg, .ti');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $preview.attr('src', e.target.result).removeClass('d-none');
                $placeholder.addClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    // Add handle
    $('#add-blog').on('click', function() {
        const index = $('#blogs-container .blog-item').length;
        let html = $('#blog-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#blogs-container').append(html);
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteBlogItemModal'));

    $(document).on('click', '.remove-item', function() {
        itemToRemove = $(this).closest('.blog-item');
        deleteModal.show();
    });

    $('#confirm-delete-blog').on('click', function() {
        if (itemToRemove) {
            itemToRemove.fadeOut(300, function() {
                $(this).remove();
                reIndexItems();
            });
        }
        deleteModal.hide();
    });

    function reIndexItems() {
        $('#blogs-container .blog-item').each(function(idx) {
            $(this).find('[name]').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/items\[\d+\]/, 'items[' + idx + ']')
                                            .replace(/blog_images\[\d+\]/, 'blog_images[' + idx + ']'));
                }
                if ($(this).hasClass('img-input')) {
                    $(this).attr('data-preview', 'preview-blog-' + idx);
                    $(this).siblings('div').find('img').attr('id', 'preview-blog-' + idx);
                }
            });
        });
    }
});
</script>
<style>
.z-index-1 { z-index: 1; }
</style>
@endpush
@endsection
