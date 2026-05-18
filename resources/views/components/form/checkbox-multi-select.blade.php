<label class="form-label d-block">{{ $label }}</label>
<div class="d-flex flex-wrap gap-2{{ $error ? ' is-invalid' : '' }}">
    @foreach ($options as $option)
        <div class="interest-pill">
            <input
                id="{{ $name }}-{{ $optionValue($option) }}"
                type="checkbox"
                value="{{ $optionValue($option) }}"
                {{ $wireBinding }}="{{ $wireModel }}"
            >
            <label for="{{ $name }}-{{ $optionValue($option) }}">{{ $optionText($option) }}</label>
        </div>
    @endforeach
</div>
@if ($errors->has($name))
    <div class="text-danger small mt-2">{{ $errors->first($name) }}</div>
@endif
@if ($errors->has($name . '.*'))
    <div class="text-danger small mt-2">{{ $errors->first($name . '.*') }}</div>
@endif
