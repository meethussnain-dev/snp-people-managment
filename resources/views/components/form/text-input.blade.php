@props([
    'name',
    'label',
    'wireModel',
    'type' => 'text',
    'placeholder' => '',
    'icon' => null,
    'maxlength' => null,
    'wireModifier' => 'blur',
])

@php
    $error = $errors->first($name);
    $inputClasses = 'form-control' . ($icon ? ' border-start-0 ps-0' : '') . ($error ? ' is-invalid' : '');
@endphp

@php
    $wireBinding = match ($wireModifier) {
        'live' => 'wire:model.live',
        'blur' => 'wire:model.blur',
        default => 'wire:model',
    };
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
            class="{{ $inputClasses }}"
            placeholder="{{ $placeholder }}"
            @if ($maxlength) maxlength="{{ $maxlength }}" @endif
            {{ $wireBinding }}="{{ $wireModel }}"
            {{ $attributes }}
        >
    </div>
    @if ($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
@else
    <input
        id="{{ $name }}"
        type="{{ $type }}"
        class="{{ $inputClasses }}"
        placeholder="{{ $placeholder }}"
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $wireBinding }}="{{ $wireModel }}"
        {{ $attributes }}
    >
    @if ($error)
        <div class="invalid-feedback">{{ $error }}</div>
    @endif
@endif
