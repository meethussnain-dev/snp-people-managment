@props([
    'title',
    'description' => null,
    'icon' => null,
])

<div class="page-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
        <h1>
            @if ($icon)
                <i class="bi bi-{{ $icon }} me-2" style="color:#2563eb;"></i>
            @endif
            {{ $title }}
        </h1>
        @if ($description)
            <p>{{ $description }}</p>
        @endif
    </div>

    @if (trim($slot))
        <div class="d-inline-flex align-items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
