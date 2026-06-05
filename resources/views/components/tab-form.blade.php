@props([
    'action',
    'indexUrl' => '',
    'method' => 'POST',
    'tabs' => [],
    'enctype' => 'multipart/form-data',
    'formClass' => 'ajax-form',
])

@php
    $method = strtoupper($method);
    $isSimpleMethod = in_array($method, ['GET', 'POST']);
    $formMethodAttr = $isSimpleMethod ? $method : 'POST';
@endphp

<form
    action="{{ $action }}"
    method="{{ $formMethodAttr }}"
    enctype="{{ $enctype }}"
    data-index-url="{{ $indexUrl }}"
    {{ $attributes->merge(['class' => $formClass]) }}>

    @csrf

    @unless($isSimpleMethod)
        @method($method)
    @endunless

    <div class="nav-align-top nav-tabs-shadow">
        <ul class="nav nav-tabs" role="tablist" style="flex-wrap: wrap; gap: 10px;">
            @foreach($tabs as $tab)
                <li class="nav-item flex-fill">
                    <button
                        type="button"
                        class="nav-link {{ !empty($tab['active']) ? 'active' : '' }}"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{ $tab['id'] }}"
                        aria-controls="tab-{{ $tab['id'] }}"
                        aria-selected="{{ !empty($tab['active']) ? 'true' : 'false' }}">
                        {{ $tab['title'] }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" style="padding: 23px 0 0 !important;">
            @foreach($tabs as $tab)
                @php
                    $slotName = preg_replace('/[^A-Za-z0-9_]/', '_', $tab['id']);
                @endphp

                <div
                    class="tab-pane fade {{ !empty($tab['active']) ? 'show active' : '' }}"
                    id="tab-{{ $tab['id'] }}"
                    role="tabpanel">
                    {!! $$slotName ?? '' !!}
                </div>
            @endforeach

            {{ $slot }}

            @include('partials.debug.json-response')
        </div>
    </div>
</form>