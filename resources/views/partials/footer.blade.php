<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div
            class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
            <div class="text-body">
            &#169;
            <script>
                document.write(new Date().getFullYear());
            </script>
            , made with ❤️ by <a href="/dashboard" target="_blank" class="footer-link">Shenlong</a>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá: <strong id="deleteName">—</strong> ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Xoá</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Quản lý Lịch làm việc -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lịch làm việc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Form thêm lịch làm việc -->
                <form id="scheduleForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Thứ trong tuần</label>
                            <select class="form-select" name="day_of_week" required>
                                <option value="">Chọn thứ</option>
                                <option value="Monday">Thứ Hai</option>
                                <option value="Tuesday">Thứ Ba</option>
                                <option value="Wednesday">Thứ Tư</option>
                                <option value="Thursday">Thứ Năm</option>
                                <option value="Friday">Thứ Sáu</option>
                                <option value="Saturday">Thứ Bảy</option>
                                <option value="Sunday">Chủ Nhật</option>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Thời gian làm việc</label>
                            <input type="text" class="form-control" name="working_hours" placeholder="Ví dụ: 08:00 - 17:00" required>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-plus me-1"></i> Thêm
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Bảng danh sách lịch làm việc -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="scheduleTable">
                        <thead class="table-light">
                            <tr>
                                <th width="30%">Thứ</th>
                                <th width="50%">Thời gian làm việc</th>
                                <th width="20%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $schedule = $item->value['working_schedule'] ?? [];
                            @endphp

                            @if(empty($schedule))
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Chưa có lịch làm việc nào.
                                    </td>
                                </tr>
                            @else
                                @foreach($schedule as $index => $item)
                                    <tr data-index="{{ $index }}">
                                        <td>
                                            @switch($item['day'])
                                                @case('Monday') Thứ Hai @break
                                                @case('Tuesday') Thứ Ba @break
                                                @case('Wednesday') Thứ Tư @break
                                                @case('Thursday') Thứ Năm @break
                                                @case('Friday') Thứ Sáu @break
                                                @case('Saturday') Thứ Bảy @break
                                                @case('Sunday') Chủ Nhật @break
                                                @default {{ $item['day'] }}
                                            @endswitch
                                        </td>
                                        <td>{{ $item['hours'] }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-icon btn-danger delete-schedule-item"
                                                    data-index="{{ $index }}"
                                                    title="Xóa">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Quản lý Mạng xã hội -->
<div class="modal fade" id="socialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mạng xã hội</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Form thêm mạng xã hội mới -->
                <form id="socialForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Icon (tên class) / Xem <a href="https://icons.getbootstrap.com" target="_blank">Bootstrap Icons</a></label>
                            <input type="text" class="form-control" name="icon" placeholder="Ví dụ: facebook, youtube, instagram, tiktok" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Link mạng xã hội</label>
                            <input type="url" class="form-control" name="url" placeholder="https://facebook.com/..." required>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-plus me-1"></i> Thêm
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Bảng danh sách mạng xã hội -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="socialTable">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Icon</th>
                                <th width="55%">Link</th>
                                <th width="20%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $socials = $item->value['social_links'] ?? [];
                            @endphp

                            @if(empty($socials))
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Chưa có mạng xã hội nào.
                                    </td>
                                </tr>
                            @else
                                @foreach($socials as $index => $social)
                                    <tr data-index="{{ $index }}">
                                        <td>
                                            <i class="bx bxl-{{ $social['icon'] }}"></i>
                                            <code class="ms-2 small">{{ $social['icon'] }}</code>
                                        </td>
                                        <td>
                                            <a href="{{ $social['url'] }}" target="_blank" class="text-break">
                                                {{ $social['url'] }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-icon btn-danger delete-social-item"
                                                    data-index="{{ $index }}"
                                                    title="Xóa">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Quản lý Mạng xã hội -->
<!-- Modal Quản lý FAQ (Câu hỏi thường gặp) -->
<div class="modal fade" id="faqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Câu hỏi thường gặp (FAQ)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Form thêm FAQ mới -->
                <form id="faqForm">
                    <div class="row g-3 align-items-end mb-4">
                        <div class="col-md-5">
                            <label class="form-label">Câu hỏi</label>
                            <input type="text" class="form-control" name="question" placeholder="Ví dụ: Làm thế nào để đặt lịch khám?" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Câu trả lời</label>
                            <textarea class="form-control" name="answer" rows="2" placeholder="Nhập câu trả lời chi tiết..." required></textarea>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="bx bx-plus me-1"></i> Thêm
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Bảng danh sách FAQ -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="faqTable">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">Câu hỏi</th>
                                <th width="50%">Câu trả lời</th>
                                <th width="15%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $faqs = $item->value['faqs'] ?? [];
                            @endphp

                            @if(empty($faqs))
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Chưa có câu hỏi thường gặp nào.
                                    </td>
                                </tr>
                            @else
                                @foreach($faqs as $index => $faq)
                                    <tr data-index="{{ $index }}">
                                        <td class="align-middle">
                                            <strong>{{ $faq['question'] }}</strong>
                                        </td>
                                        <td class="align-middle text-muted">
                                            {!! Str::limit(strip_tags($faq['answer']), 150) !!}
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-sm btn-icon btn-danger delete-faq-item"
                                                    data-index="{{ $index }}"
                                                    title="Xóa">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">
  @csrf
  @method('DELETE')
</form>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080" id="app-toast-container"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const scheduleForm = document.getElementById('scheduleForm');
    const scheduleTable = document.getElementById('scheduleTable').querySelector('tbody');
    let deleteIndex = null;

    // Thêm lịch làm việc mới
    scheduleForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const daySelect = this.querySelector('[name="day_of_week"]');
        const hoursInput = this.querySelector('[name="working_hours"]');

        const dayValue = daySelect.value;
        const dayText = daySelect.options[daySelect.selectedIndex].text;
        const hours = hoursInput.value.trim();

        if (!dayValue || !hours) return;

        // Kiểm tra trùng thứ
        const existingRows = scheduleTable.querySelectorAll('tr[data-index]');
        for (let row of existingRows) {
            if (row.querySelector('td').textContent.trim() === dayText) {
                alert('Thứ này đã được thêm rồi!');
                return;
            }
        }

        // Thêm dòng mới vào bảng
        const newRow = document.createElement('tr');
        const index = Date.now(); // dùng timestamp làm key tạm
        newRow.dataset.index = index;

        newRow.innerHTML = `
            <td>${dayText}</td>
            <td>${hours}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-icon btn-danger delete-schedule-item" data-index="${index}">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;

        // Xóa thông báo "chưa có" nếu có
        const emptyRow = scheduleTable.querySelector('tr td[colspan]');
        if (emptyRow) emptyRow.parentElement.remove();

        scheduleTable.appendChild(newRow);

        // Thêm input hidden để submit form chính
        addHiddenInput(`working_schedule[${index}][day]`, dayValue);
        addHiddenInput(`working_schedule[${index}][hours]`, hours);

        // Reset form
        this.reset();
    });

    // Xóa item
    scheduleTable.addEventListener('click', function (e) {
        if (e.target.closest('.delete-schedule-item')) {
            const btn = e.target.closest('.delete-schedule-item');
            deleteIndex = btn.dataset.index;

            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }
    });

    // Xác nhận xóa
   const confirmDeleteScheduleBtn = document.getElementById('confirmDeleteSchedule');

    if (confirmDeleteScheduleBtn) {
      confirmDeleteScheduleBtn.addEventListener('click', function () {
    if (deleteScheduleIndex !== null) {
      const row = scheduleTable.querySelector(`tr[data-index="${deleteScheduleIndex}"]`);
      if (row) row.remove();

      document.querySelectorAll(`input[name^="working_schedule[${deleteScheduleIndex}]"]`).forEach(el => el.remove());

      if (scheduleTable.children.length === 0) {
        scheduleTable.innerHTML = `
          <tr>
            <td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu</td>
          </tr>
        `;
      }

      deleteScheduleIndex = null;
    }
  });
}

    // Hàm helper thêm hidden input vào form chính (giả sử form chính có id="mainForm")
    function addHiddenInput(name, value) {
        const form = document.getElementById('mainForm'); // thay bằng ID form chính của bạn
        if (!form) return;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const socialForm = document.getElementById('socialForm');
    const socialTable = document.getElementById('socialTable').querySelector('tbody');
    let deleteSocialIndex = null;

    // Thêm mạng xã hội mới
    socialForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const iconInput = this.querySelector('[name="icon"]');
        const urlInput = this.querySelector('[name="url"]');

        const icon = iconInput.value.trim();
        const url = urlInput.value.trim();

        if (!icon || !url) return;

        // Kiểm tra trùng icon (tránh thêm 2 Facebook chẳng hạn)
        const existingRows = socialTable.querySelectorAll('tr[data-index]');
        for (let row of existingRows) {
            if (row.querySelector('code').textContent.trim() === icon) {
                alert('Icon này đã được thêm rồi!');
                return;
            }
        }

        // Thêm dòng mới vào bảng
        const newRow = document.createElement('tr');
        const index = Date.now();
        newRow.dataset.index = index;

        newRow.innerHTML = `
            <td>
                <i class="bx bxl-${icon}"></i>
                <code class="ms-2 small">${icon}</code>
            </td>
            <td>
                <a href="${url}" target="_blank" class="text-break">${url}</a>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-icon btn-danger delete-social-item" data-index="${index}">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;

        // Xóa dòng "chưa có" nếu tồn tại
        const emptyRow = socialTable.querySelector('tr td[colspan]');
        if (emptyRow) emptyRow.parentElement.remove();

        socialTable.appendChild(newRow);

        // Thêm hidden input vào form chính
        addHiddenInput(`social_links[${index}][icon]`, icon);
        addHiddenInput(`social_links[${index}][url]`, url);

        // Reset form
        this.reset();
    });

    // Xóa item
    socialTable.addEventListener('click', function (e) {
        if (e.target.closest('.delete-social-item')) {
            const btn = e.target.closest('.delete-social-item');
            deleteSocialIndex = btn.dataset.index;

            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteSocialModal'));
            modal.show();
        }
    });

    // Xác nhận xóa
  const confirmDeleteSocialBtn = document.getElementById('confirmDeleteSocial');

   if (confirmDeleteSocialBtn) {
      confirmDeleteSocialBtn.addEventListener('click', function () {
    if (deleteSocialIndex !== null) {
      const row = socialTable.querySelector(`tr[data-index="${deleteSocialIndex}"]`);
      if (row) row.remove();

      document.querySelectorAll(`input[name^="social_links[${deleteSocialIndex}]"]`).forEach(el => el.remove());

      if (socialTable.children.length === 0) {
        socialTable.innerHTML = `
          <tr>
            <td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu</td>
          </tr>
        `;
      }

      deleteSocialIndex = null;
    }
  });
}
    // Helper: thêm hidden input vào form chính (thay 'mainForm' bằng id form thực tế của bạn)
    function addHiddenInput(name, value) {
        const form = document.getElementById('mainForm'); // <-- Đổi thành ID form chính của trang setting
        if (!form) return;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const faqForm = document.getElementById('faqForm');
    const faqTable = document.getElementById('faqTable').querySelector('tbody');
    let deleteFaqIndex = null;

    // Thêm FAQ mới
    faqForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const questionInput = this.querySelector('[name="question"]');
        const answerInput = this.querySelector('[name="answer"]');

        const question = questionInput.value.trim();
        const answer = answerInput.value.trim();

        if (!question || !answer) return;

        // Kiểm tra trùng câu hỏi (tùy chọn)
        const existingQuestions = Array.from(faqTable.querySelectorAll('tr[data-index] td:first-child strong'))
            .map(el => el.textContent.trim());
        if (existingQuestions.includes(question)) {
            alert('Câu hỏi này đã tồn tại!');
            return;
        }

        // Thêm dòng mới vào bảng
        const newRow = document.createElement('tr');
        const index = Date.now();
        newRow.dataset.index = index;

        newRow.innerHTML = `
            <td class="align-middle"><strong>${question}</strong></td>
            <td class="align-middle text-muted">${answer.substring(0, 150)}${answer.length > 150 ? '...' : ''}</td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-icon btn-danger delete-faq-item" data-index="${index}">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;

        // Xóa dòng "chưa có" nếu tồn tại
        const emptyRow = faqTable.querySelector('tr td[colspan]');
        if (emptyRow) emptyRow.parentElement.remove();

        faqTable.appendChild(newRow);

        // Thêm hidden inputs vào form chính (thay 'mainForm' bằng id form setting của bạn)
        addHiddenInput(`faqs[${index}][question]`, question);
        addHiddenInput(`faqs[${index}][answer]`, answer);

        // Reset form
        this.reset();
    });

    // Bắt sự kiện xóa
    faqTable.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-faq-item');
        if (btn) {
            deleteFaqIndex = btn.dataset.index;
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteFaqModal'));
            modal.show();
        }
    });

    // Xác nhận xóa
 const confirmDeleteFaqBtn = document.getElementById('confirmDeleteFaq');

    if (confirmDeleteFaqBtn) {
      confirmDeleteFaqBtn.addEventListener('click', function () {
    if (deleteFaqIndex !== null) {
      const row = faqTable.querySelector(`tr[data-index="${deleteFaqIndex}"]`);
      if (row) row.remove();

      document.querySelectorAll(`input[name^="faqs[${deleteFaqIndex}]"]`).forEach(el => el.remove());

      if (faqTable.children.length === 0) {
        faqTable.innerHTML = `
          <tr>
            <td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu</td>
          </tr>
        `;
      }

      deleteFaqIndex = null;
    }
  });
}
    // Helper: thêm hidden input vào form chính
    function addHiddenInput(name, value) {
        const form = document.getElementById('mainForm'); // <-- Thay bằng ID form setting chính xác của bạn
        if (!form) return;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }
});
</script>