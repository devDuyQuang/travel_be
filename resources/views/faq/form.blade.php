@extends('index')

@section('title', ($faq->exists ? 'Sửa' : 'Thêm') . ' FAQ')

@section('content')
<main class="main-wrapper"><div class="main-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">{{ $faq->exists ? 'Sửa FAQ' : 'Thêm FAQ' }}</h5>
      <a class="btn btn-outline-secondary" href="{{ panel_route('faq.index') }}">Quay lại</a>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ $faq->exists ? panel_route('faq.update', $faq->id) : panel_route('faq.store') }}">
        @csrf @if($faq->exists) @method('PUT') @endif
        <div class="row g-3">
          <div class="col-md-8"><x-input-field name="question" label="Câu hỏi" :required="true" :value="old('question', $faq->question)" /></div>
          <div class="col-md-4"><x-input-field name="category" label="Nhóm FAQ" :value="old('category', $faq->category)" /></div>
          <div class="col-12"><x-ckeditor name="answer" label="Câu trả lời" :value="old('answer', $faq->answer)" /></div>
          <div class="col-md-4"><x-input-field name="sort_order" label="Thứ tự" type="number" :value="old('sort_order', $faq->sort_order ?? 0)" /></div>
          <div class="col-md-8 d-flex align-items-end"><label class="mb-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq->is_active ?? true))> Hiển thị</label></div>
        </div>
        <div class="mt-4"><button class="btn btn-primary">Lưu FAQ</button></div>
      </form>
    </div>
  </div>
</div></main>
@endsection
