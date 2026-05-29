@extends('index')

@section('title', 'Liên Hệ')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Danh sách liên hệ</h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($contacts as $item)
                        <tr>
                            <td>{{ $item->id }}</td>

                            <td>{{ $item->full_name ?: '—' }}</td>

                            <td>{{ $item->email ?: '—' }}</td>

                            <td>{{ $item->phone ?: '—' }}</td>

                            <td>
                                @if($item->message)
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contactMessageModal{{ $item->id }}"
                                        title="Xem nội dung"
                                    >
                                        <i class="ti tabler-eye"></i>
                                    </button>
                                @else
                                    —
                                @endif
                            </td>

                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '—' }}</td>
                        </tr>

                        @if($item->message)
                            <div class="modal fade" id="contactMessageModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Nội dung liên hệ #{{ $item->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <div><strong>Họ tên:</strong> {{ $item->full_name ?: '—' }}</div>
                                                <div><strong>Email:</strong> {{ $item->email ?: '—' }}</div>
                                                <div><strong>Phone:</strong> {{ $item->phone ?: '—' }}</div>
                                                <div><strong>Ngày gửi:</strong> {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '—' }}</div>
                                            </div>

                                            <hr>

                                            <div style="white-space: pre-wrap; line-height: 1.7;">
                                                {{ $item->message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $contacts->links() }}
    </div>
</div>

@endsection