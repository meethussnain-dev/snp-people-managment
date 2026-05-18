@props([
    'name',
    'label',
    'wireModel',
    'options' => [],
    'placeholder' => null,
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'wireModifier' => 'blur',
])

@php
    $error = $errors->first($name);
    $wireBinding = match ($wireModifier) {
        'live' => 'wire:model.live',
        'blur' => 'wire:model.blur',
        default => 'wire:model',
    };
@endphp

<label for="{{ $name }}" class="form-label">{{ $label }}</label>
<select
    id="{{ $name }}"
    class="form-select{{ $error ? ' is-invalid' : '' }}"
    {{ $wireBinding }}="{{ $wireModel }}"
    {{ $attributes }}
>
    @if (! is_null($placeholder))
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $optionKey => $option)
        @php
            $value = is_object($option) ? $option->{$optionValue} : (is_array($option) ? $option[$optionValue] : $optionKey);
            $text = is_object($option) ? $option->{$optionLabel} : (is_array($option) ? $option[$optionLabel] : $option);
        @endphp
        <option value="{{ $value }}">{{ $text }}</option>
    @endforeach
</select>
@if ($error)
    <div class="invalid-feedback">{{ $error }}</div>
@endif
