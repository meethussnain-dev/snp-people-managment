@props([
    'name',
    'label',
    'wireModel',
    'placeholder' => 'Search...',
])

<label for="{{ $name }}" class="form-label mb-1">{{ $label }}</label>
<div class="input-group">
    <span class="input-group-text bg-white border-end-0" style="border-color:#cbd5e1;">
        <i class="bi bi-search" style="color:#94a3b8;font-size:0.8rem;"></i>
    </span>
    <input
        id="{{ $name }}"
        type="text"
        class="form-control border-start-0 ps-0"
        placeholder="{{ $placeholder }}"
        wire:model.live.debounce.400ms="{{ $wireModel }}"
        {{ $attributes }}
    >
</div>
