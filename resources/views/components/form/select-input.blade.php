@php
    $fieldError = $errors->first($name);
@endphp

<label for="{{ $name }}" class="form-label">{{ $label }}</label>
<select
    id="{{ $name }}"
    class="form-select{{ $fieldError ? ' is-invalid' : '' }}"
    {{ $wireBinding }}="{{ $wireModel }}"
    {{ $attributes }}
>
    @if (! is_null($placeholder))
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $key => $option)
        <option value="{{ $optionValue($option, $key) }}">{{ $optionText($option) }}</option>
    @endforeach
</select>
@if ($fieldError)
    <div class="invalid-feedback d-block">{{ $fieldError }}</div>
@endif
