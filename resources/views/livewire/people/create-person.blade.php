<div>
    <div class="container">
        <x-headers.page-header
            title="{{ $heading }}"
            description="{{ $pageDescription }}"
            icon="person-plus"
        >
            <a href="{{ route('people.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Back to People
            </a>
        </x-headers.page-header>

        @include('livewire.people.partials.person-form')
    </div>
</div>
