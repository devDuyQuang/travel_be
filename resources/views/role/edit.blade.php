<!-- @extends('index')

@section('title', 'Sửa vai trò')

@section('content')
<h5 class="card-header">Sửa vai trò</h5>

<div class="card-body">
    <form action="{{ panel_route('role.update', $item->id) }}" method="POST">
        @include('role._form')
    </form>
</div>
@endsection -->


@extends('index')

@section('title', 'Sửa vai trò')

@section('content')
<h5 class="card-header">Sửa vai trò</h5>

<div class="card-body">
    <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

    <form action="{{ panel_route('role.update', $item->id) }}" method="POST">
        @include('role._form')
    </form>
</div>
@endsection