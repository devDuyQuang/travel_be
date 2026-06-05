@props([
  'id' => 'dt-'.\Illuminate\Support\Str::random(6),
  'columns' => [],
  'options' => [],
  'ajaxUrl' => null,
  'data' => null,
])

@php
  $hasControl = !empty($options['control']);
  $tableClass = $options['tableClass'] ?? 'table table-bordered w-100';
  $wrapperClass = $options['wrapperClass'] ?? 'card-datatable';

  $dtColumns = [];
  $columnKeys = [];

  foreach ($columns as $c) {
    $dtColumns[] = ['data' => $c['key']];
    $columnKeys[] = $c['key'];
  }

  $searchPlaceholder = $options['searchPlaceholder'] ?? '';
  $renders = $options['renders'] ?? [];
  $rendersByKey = $options['rendersByKey'] ?? [];
  $orderFromServer = $options['order'] ?? null;
  $responsiveFromServer = $options['responsive'] ?? null;
  $dataSrcOpt = array_key_exists('dataSrc', $options) ? $options['dataSrc'] : 'data';
  $pageLengthOpt = isset($options['pageLength']) ? (int) $options['pageLength'] : null;
  $extraColumnDefs = $options['columnDefs'] ?? [];
@endphp

<div class="px-3 pb-3">
  <div class="{{ $wrapperClass }}">
    <table id="{{ $id }}" class="{{ $tableClass }}">
      <thead>
        <tr>
          @if($hasControl)
            <th></th>
          @endif

          @foreach ($columns as $col)
            <th>{{ $col['title'] ?? $col['key'] }}</th>
          @endforeach
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('scripts')
<script>
(function () {
  const tableEl = document.getElementById(@json($id));
  if (!tableEl) return;

  const hasControl = @json($hasControl);
  const dtColumns = @json($dtColumns);
  const searchPlaceholder = @json($searchPlaceholder);
  const renders = @json($renders);
  const rendersByKey = @json($rendersByKey);
  const orderFromServer = @json($orderFromServer);
  const responsiveFromServer = @json($responsiveFromServer);
  const dataSrcOpt = @json($dataSrcOpt);
  const pageLengthOpt = @json($pageLengthOpt);
  const columnKeys = @json($columnKeys);
  const extraColumnDefs = @json($extraColumnDefs);

  const indexOfKey = function (key) {
    const index = columnKeys.indexOf(key);
    return index === -1 ? -1 : (hasControl ? index + 1 : index);
  };

  if (hasControl) {
    dtColumns.unshift({ data: null });
  }

  const base = {
    processing: true,
    serverSide: true,
    columns: dtColumns,
    dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>>' +
         '<"table-responsive"t>' +
         '<"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>>',


    language: {
      search: 'Tìm kiếm:',
      info: 'Hiển thị _START_ đến _END_ của _TOTAL_ dòng',
      infoEmpty: 'Không có dữ liệu',
      infoFiltered: '(lọc từ _MAX_ dòng)',
      zeroRecords: 'Không tìm thấy dữ liệu phù hợp',
      emptyTable: 'Không có dữ liệu trong bảng',
      processing: 'Đang xử lý...',
      paginate: {
        next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
        previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>',
        first: '<i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>',
        last: '<i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>'
      }
    }
  };

  if (pageLengthOpt > 0) {
    base.pageLength = pageLengthOpt;
  }

  @if(!empty($ajaxUrl))
    base.ajax = {
      url: @json($ajaxUrl),
      dataSrc: dataSrcOpt
    };
  @elseif(!empty($data))
    base.data = @json($data);
  @endif

  base.responsive = responsiveFromServer !== null
    ? responsiveFromServer
    : (hasControl ? { details: { type: 'column' } } : true);

  base.order = orderFromServer !== null
    ? orderFromServer
    : (hasControl ? [[1, 'asc']] : [[0, 'asc']]);

  base.columnDefs = [];

  if (renders && typeof renders === 'object') {
    Object.keys(renders).forEach(function (idxStr) {
      const idx = parseInt(idxStr, 10);

      base.columnDefs.push({
        targets: hasControl ? idx + 1 : idx,
        render: new Function('data', 'type', 'row', 'meta', renders[idxStr])
      });
    });
  }

  if (rendersByKey && typeof rendersByKey === 'object') {
    Object.keys(rendersByKey).forEach(function (key) {
      const target = indexOfKey(key);

      if (target >= 0) {
        base.columnDefs.push({
          targets: target,
          render: new Function('data', 'type', 'row', 'meta', rendersByKey[key])
        });
      }
    });
  }

  if (hasControl) {
    base.columnDefs.unshift({
      targets: 0,
      className: 'control',
      orderable: false,
      searchable: false,
      render: function () {
        return '';
      }
    });
  }

  if (Array.isArray(extraColumnDefs) && extraColumnDefs.length) {
    base.columnDefs = base.columnDefs.concat(extraColumnDefs);
  }

  jQuery(tableEl).DataTable(base);
})();
</script>
@endpush