<!-- @extends('index')

@section('title', 'Thêm vai trò')

@section('content')
<h5 class="card-header">Thêm vai trò</h5>

<div class="card-body">
    <form action="{{ panel_route('role.store') }}" method="POST">
        @include('role._form')
    </form>
</div>
@endsection -->


@extends('index')

@section('title', 'Thêm vai trò')

@section('content')
<h5 class="card-header">Thêm vai trò</h5>

<div class="card-body">
    <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

    <form action="{{ panel_route('role.store') }}" method="POST">
        @include('role._form')
    </form>
</div>
@endsection