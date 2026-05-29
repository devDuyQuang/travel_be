@props([
  'name',
  'label' => null,
  'options' => [],       // mảng [value => label] | mảng ['value','label','disabled'] | object có id,name[,disabled]
  'value' => null,       // truyền: old('parent_id', $item->parent_id ?? '')
  'id' => null,          // nếu không truyền sẽ dùng $name
  'placeholder' => null, // null = không vẽ; string = vẽ 1 option rỗng
  'required' => false,
])

@php
  $fieldId = $id ?? $name;
  // Chuẩn hoá current value về string (tránh lệch kiểu int/string)
  $current = isset($value) ? (string)$value : '';

  // Chuẩn hoá options: hỗ trợ optgroup khi phần tử có 'children'
  $normalize = function($options) {
      $out = [];
      foreach ($options as $k => $opt) {
          // optgroup: ['label' => '...', 'children' => [...]]
          if (is_array($opt) && isset($opt['children']) && is_array($opt['children'])) {
              $out[] = [
                  'group' => true,
                  'label' => $opt['label'] ?? (is_string($k) ? $k : 'Group'),
                  'children' => $normalize($opt['children']),
              ];
              continue;
          }

          $disabled = false; $val = null; $lbl = null;

          if (is_object($opt)) {
              $val = isset($opt->id) ? (string)$opt->id : (string)$k;
              $lbl = $opt->name ?? $opt->label ?? (string)$opt;
              $disabled = (bool)($opt->disabled ?? false);
          } elseif (is_array($opt)) {
              $val = isset($opt['value']) ? (string)$opt['value'] : (string)($opt['id'] ?? $k);
              $lbl = $opt['label'] ?? $opt['name'] ?? (string)reset($opt);
              $disabled = (bool)($opt['disabled'] ?? false);
          } else {
              // dạng [value => label]
              $val = (string)(is_int($k) ? $opt : $k);
              $lbl = (string)$opt;
          }

          $out[] = ['group' => false, 'value' => $val, 'label' => $lbl, 'disabled' => $disabled];
      }
      return $out;
  };

  $normalized = $normalize($options);
@endphp

<div class="mb-6">
  @if($label)
    <label class="form-label" for="{{ $fieldId }}">
      {{ $label }} @if($required) <span class="text-danger">*</span> @endif
    </label>
  @endif

  <select
    name="{{ $name }}"
    id="{{ $fieldId }}"
    class="select2 form-select @error($name) is-invalid @enderror"
    data-allow-clear="true"
    @if($required) required @endif
  >
    @if($placeholder !== null)
      {{-- placeholder không selected nếu đã có $current --}}
      <option value="">{{ $placeholder }}</option>
    @endif

    @foreach($normalized as $opt)
      @if($opt['group'])
        <optgroup label="{{ $opt['label'] }}">
          @foreach($opt['children'] as $child)
            <option
              value="{{ $child['value'] }}"
              @selected($current !== '' && $current === $child['value'])
              @if($child['disabled']) disabled @endif
            >
              {{ $child['label'] }}
            </option>
          @endforeach
        </optgroup>
      @else
        <option
          value="{{ $opt['value'] }}"
          @selected($current !== '' && $current === $opt['value'])
          @if($opt['disabled']) disabled @endif
        >
          {{ $opt['label'] }}
        </option>
      @endif
    @endforeach
  </select>

  <div class="invalid-feedback" id="error-{{ $name }}">
    @error($name) {{ $message }} @enderror
  </div>
</div>
