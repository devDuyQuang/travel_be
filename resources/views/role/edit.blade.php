@extends('index')

@section('title', 'Sửa vai trò')

@section('content')
@include('partials.css.role')

<main class="main-wrapper role-form-page">
  <div class="main-content">
    <h5 class="role-form-title">
      Sửa vai trò
    </h5>

    <div class="card-body role-form-card">
      <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

      <form
        action="{{ panel_route('role.update', $item->id) }}"
        method="POST"
        class="ajax-form"
        data-index-url="{{ panel_route('role.index') }}">
        @include('role._form')
      </form>
    </div>
  </div>
</main>
@endsection