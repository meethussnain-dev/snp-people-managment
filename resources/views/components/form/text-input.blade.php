@php
    $fieldError = $errors->first($name);
    $fieldClasses = 'form-control' . ($icon ? ' border-start-0 ps-0' : '') . ($fieldError ? ' is-invalid' : '');
@endphp

<label for="{{ $name }}" class="form-label">{{ $label }}</label>

@if ($icon)
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0" style="border-color:#cbd5e1;">
            <i class="bi bi-{{ $icon }}" style="color:#94a3b8;font-size:0.85rem;"></i>
        </span>
        <input
            id="{{ $name }}"
            type="{{ $type }}"
            class="{{ $fieldClasses }}"
            placeholder="{{ $placeholder }}"
            @if ($maxlength) maxlength="{{ $maxlength }}" @endif
            {{ $wireBinding }}="{{ $wireModel }}"
            {{ $attributes }}
        >
    </div>
    @if ($fieldError)
        <div class="invalid-feedback d-block">{{ $fieldError }}</div>
    @endif
@else
    <input
        id="{{ $name }}"
        type="{{ $type }}"
        class="{{ $fieldClasses }}"
        placeholder="{{ $placeholder }}"
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $wireBinding }}="{{ $wireModel }}"
        {{ $attributes }}
    >
    @if ($fieldError)
        <div class="invalid-feedback d-block">{{ $fieldError }}</div>
    @endif
@endif
