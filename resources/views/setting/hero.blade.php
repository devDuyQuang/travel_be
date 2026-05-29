@extends('index')
@section('title', 'Phần Đầu / Trang Chủ')

@section('content')
<h5 class="card-header">Cấu hình Phần Đầu / Trang Chủ</h5>

<div class="card-body">
  <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>
  
  <form action="{{ panel_route('setting.updateHero', $item->id ?? 1) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Content Section -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Nội dung chính & Banner</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-field name="title" label="Tiêu đề chính (Title)" :value="old('title', data_get($item->value, 'title'))" />
                        </div>
                        <div class="col-md-6">
                            <x-textarea-field name="description" label="Mô tả (Description)" :value="old('description', data_get($item->value, 'description'))" rows="2" />
                        </div>
                        <div class="col-md-6">
                            <x-input-field name="button_one_text" label="Text Button 1" :value="old('button_one_text', data_get($item->value, 'button_one_text'))" />
                        </div>
                        <div class="col-md-6">
                            <x-input-field name="button_one_link" label="Link Button 1" :value="old('button_one_link', data_get($item->value, 'button_one_link'))" />
                        </div>
                        <div class="col-md-6">
                            <x-input-field name="button_two_text" label="Text Button 2" :value="old('button_two_text', data_get($item->value, 'button_two_text'))" />
                        </div>
                        <div class="col-md-6">
                            <x-input-field name="button_two_link" label="Link Button 2" :value="old('button_two_link', data_get($item->value, 'button_two_link'))" />
                        </div>
                        <div class="col-md-12">
                            <x-file-input label="Banner Hero" name="banner_hero_file" :multiple="false" :current-url="$currentBannerHeroUrl ?? null" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Section -->
        <div class="col-md-6">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Thông tin câu hỏi</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <x-input-field name="question_title" label="Tiêu đề câu hỏi" :value="old('question_title', data_get($item->value, 'question_title'))" />
                        </div>
                        <div class="col-md-12">
                            <x-input-field name="question_email" label="Email nhận câu hỏi" :value="old('question_email', data_get($item->value, 'question_email'))" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Percentage Section -->
        <div class="col-md-6">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Tỷ lệ hài lòng (%)</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <x-input-field name="percent" label="Phần trăm (%)" :value="old('percent', data_get($item->value, 'percent'))" />
                        </div>
                        <div class="col-md-12">
                            <x-input-field name="percent_text" label="Text phần trăm" :value="old('percent_text', data_get($item->value, 'percent_text'))" />
                        </div>
                        <div class="col-md-12">
                            <x-input-field name="percent_link" label="Link phần trăm" :value="old('percent_link', data_get($item->value, 'percent_link'))" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patients Section -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Thông tin bệnh nhân</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-field name="patient_title" label="Tiêu đề bệnh nhân" :value="old('patient_title', data_get($item->value, 'patient_title'))" />
                        </div>
                        <div class="col-md-6">
                            <x-input-field name="patient_des" label="Mô tả bệnh nhân" :value="old('patient_des', data_get($item->value, 'patient_des'))" />
                        </div>
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
@endsection
