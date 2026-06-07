@extends('index')

@section('title', 'Liên Hệ')

@section('content')
@include('partials.css.contact')

<main class="main-wrapper contact-list-page">
  <div class="main-content">

    <div class="contact-page-header">
      <h5 class="contact-page-title">
        <span class="material-icons-outlined">mail</span>
        Danh Sách Liên Hệ
      </h5>
    </div>

    <div class="contact-table-card">
      <div class="contact-table-wrap">
        <table class="contact-table">
          <thead>
            <tr>
              <th class="contact-index-col text-center">STT</th>
              <th class="contact-name-col">HỌ TÊN</th>
              <th class="contact-email-col">EMAIL</th>
              <th class="contact-phone-col">PHONE</th>
              <th class="contact-message-col text-center">NỘI DUNG</th>
              <th class="contact-date-col text-center">NGÀY GỬI</th>
            </tr>
          </thead>

          <tbody>
            @forelse($contacts as $index => $item)
              <tr>
                <td class="text-center">
                  {{ method_exists($contacts, 'firstItem') ? $contacts->firstItem() + $index : $loop->iteration }}
                </td>

                <td>{{ $item->full_name ?: '—' }}</td>

                <td>{{ $item->email ?: '—' }}</td>

                <td>{{ $item->phone ?: '—' }}</td>

                <td class="text-center">
                  @if($item->message)
                    <button
                      type="button"
                      class="contact-message-btn"
                      data-bs-toggle="modal"
                      data-bs-target="#contactMessageModal{{ $item->id }}"
                      title="Xem nội dung">
                      <span class="material-icons-outlined">visibility</span>
                    </button>
                  @else
                    —
                  @endif
                </td>

                <td class="text-center">
                  @if($item->created_at)
                    <div class="contact-date">
                      <strong>{{ $item->created_at->format('d/m/Y') }}</strong>
                      <small>{{ $item->created_at->format('H:i') }}</small>
                    </div>
                  @else
                    —
                  @endif
                </td>
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
                        <div class="contact-modal-meta mb-3">
                          <div><strong>Họ tên:</strong> {{ $item->full_name ?: '—' }}</div>
                          <div><strong>Email:</strong> {{ $item->email ?: '—' }}</div>
                          <div><strong>Phone:</strong> {{ $item->phone ?: '—' }}</div>
                          <div><strong>Ngày gửi:</strong> {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '—' }}</div>
                        </div>

                        <hr>

                        <div class="contact-modal-message">
                          {{ $item->message }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            @empty
              <tr>
                <td colspan="6" class="contact-empty-row">
                  Không có dữ liệu
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($contacts, 'links'))
        <div class="contact-pagination">
          {{ $contacts->links() }}
        </div>
      @endif
    </div>

  </div>
</main>
@endsection