<div>
    <div class="container">
        <x-headers.page-header
            title="{{ $heading }}"
            description="{{ $pageDescription }}"
            icon="{{ $icon }}"
        >
            <a href="{{ route('people.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Back to People
            </a>
        </x-headers.page-header>

        <form wire:submit="save">
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="section-label mb-0" style="border:none;padding:0;margin:0;">
                        <i class="bi bi-person me-1"></i> Personal Information
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-form.text-input
                                name="form.name"
                                label="First Name"
                                wire-model="form.name"
                                placeholder="e.g. John"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.text-input
                                name="form.surname"
                                label="Surname"
                                wire-model="form.surname"
                                placeholder="e.g. Doe"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.text-input
                                name="form.sa_id_number"
                                label="South African ID Number"
                                wire-model="form.sa_id_number"
                                placeholder="13-digit ID number"
                                icon="card-text"
                                maxlength="13"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.text-input
                                name="form.birth_date"
                                label="Birth Date"
                                type="date"
                                wire-model="form.birth_date"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="section-label mb-0" style="border:none;padding:0;margin:0;">
                        <i class="bi bi-envelope me-1"></i> Contact Information
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-form.text-input
                                name="form.mobile_number"
                                label="Mobile Number"
                                wire-model="form.mobile_number"
                                placeholder="e.g. 0821234567"
                                icon="phone"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.text-input
                                name="form.email"
                                label="Email Address"
                                type="email"
                                wire-model="form.email"
                                placeholder="you@example.com"
                                icon="envelope"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="section-label mb-0" style="border:none;padding:0;margin:0;">
                        <i class="bi bi-globe me-1"></i> Language & Interests
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <x-form.select-input
                                name="form.language_id"
                                label="Language"
                                wire-model="form.language_id"
                                :options="$this->languages"
                                placeholder="- Select a language -"
                            />
                        </div>
                        <div class="col-12">
                            <x-form.checkbox-multi-select
                                name="form.interests"
                                label="Interests"
                                wire-model="form.interests"
                                :options="$this->interestOptions"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center gap-3 py-4">
        <a href="{{ route('people.index') }}" class="btn btn-outline-secondary px-4">
            Cancel
        </a>
        <button type="submit" class="btn btn-primary px-5 d-inline-flex align-items-center gap-2">
            <i class="bi bi-check2-circle"></i>
            {{ $submitLabel }}
        </button>
    </div>
</form>


    </div>
</div>
