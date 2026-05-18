@props([
    'name',
    'label',
    'wireModel',
    'options' => [],
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'wireModifier' => 'blur',
])

@php
    $error = $errors->first($name) ?: $errors->first($name . '.*');
    $wireBinding = match ($wireModifier) {
        'live' => 'wire:model.live',
        'blur' => 'wire:model.blur',
        default => 'wire:model',
    };
@endphp

<label class="form-label d-block">{{ $label }}</label>
<div class="d-flex flex-wrap gap-2{{ $error ? ' is-invalid' : '' }}">
    @foreach ($options as $option)
        @php
            $value = is_object($option) ? $option->{$optionValue} : $option[$optionValue];
            $text = is_object($option) ? $option->{$optionLabel} : $option[$optionLabel];
        @endphp
        <div class="interest-pill">
            <input
                id="{{ $name }}-{{ $value }}"
                type="checkbox"
                value="{{ $value }}"
                {{ $wireBinding }}="{{ $wireModel }}"
            >
            <label for="{{ $name }}-{{ $value }}">{{ $text }}</label>
        </div>
    @endforeach
</div>
@if ($errors->has($name))
    <div class="text-danger small mt-2">{{ $errors->first($name) }}</div>
@endif
@if ($errors->has($name . '.*'))
    <div class="text-danger small mt-2">{{ $errors->first($name . '.*') }}</div>
@endif
