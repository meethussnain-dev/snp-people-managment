<div>
<div class="container">

    {{-- Page Header --}}
    <div class="page-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h1>
                <i class="bi bi-{{ $personId ? 'pencil-square' : 'person-plus' }} me-2" style="color:#2563eb;"></i>
                {{ $heading }}
            </h1>
            <p>All fields are required.</p>
        </div>
        <a href="{{ route('people.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to People
        </a>
    </div>

    <form wire:submit.prevent="submit">
        <div class="row g-4">

            {{-- Personal Information --}}
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
                                <label for="name" class="form-label">First Name</label>
                                <input id="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="e.g. John"
                                       wire:model.defer="name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="surname" class="form-label">Surname</label>
                                <input id="surname" type="text"
                                       class="form-control @error('surname') is-invalid @enderror"
                                       placeholder="e.g. Doe"
                                       wire:model.defer="surname">
                                @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sa_id_number" class="form-label">South African ID Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-color:#cbd5e1;">
                                        <i class="bi bi-card-text" style="color:#94a3b8;font-size:0.85rem;"></i>
                                    </span>
                                    <input id="sa_id_number" type="text" maxlength="13"
                                           class="form-control border-start-0 ps-0 @error('sa_id_number') is-invalid @enderror"
                                           placeholder="13-digit ID number"
                                           wire:model.defer="sa_id_number">
                                    @error('sa_id_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input id="birth_date" type="date"
                                       class="form-control @error('birth_date') is-invalid @enderror"
                                       wire:model.defer="birth_date">
                                @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
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
                                <label for="mobile_number" class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-color:#cbd5e1;">
                                        <i class="bi bi-phone" style="color:#94a3b8;font-size:0.85rem;"></i>
                                    </span>
                                    <input id="mobile_number" type="text"
                                           class="form-control border-start-0 ps-0 @error('mobile_number') is-invalid @enderror"
                                           placeholder="e.g. 0821234567"
                                           wire:model.defer="mobile_number">
                                    @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-color:#cbd5e1;">
                                        <i class="bi bi-envelope" style="color:#94a3b8;font-size:0.85rem;"></i>
                                    </span>
                                    <input id="email" type="email"
                                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                           placeholder="you@example.com"
                                           wire:model.defer="email">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Language & Interests --}}
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
                                <label for="language_id" class="form-label">Language</label>
                                <select id="language_id"
                                        class="form-select @error('language_id') is-invalid @enderror"
                                        wire:model.defer="language_id">
                                    <option value="">— Select a language —</option>
                                    @foreach ($this->languages as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                                @error('language_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label d-block">Interests</label>
                                <div class="d-flex flex-wrap gap-2 @error('interests') is-invalid @enderror">
                                    @foreach ($this->interestOptions as $interest)
                                        <div class="interest-pill">
                                            <input
                                                id="interest-{{ $interest->id }}"
                                                type="checkbox"
                                                value="{{ $interest->id }}"
                                                wire:model.defer="interests"
                                            >
                                            <label for="interest-{{ $interest->id }}">
                                                {{ $interest->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('interests') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                @error('interests.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end align-items-center gap-3 py-4">
            <a href="{{ route('people.index') }}" class="btn btn-outline-secondary px-4">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary px-5 d-inline-flex align-items-center gap-2">
                <i class="bi bi-check2-circle"></i>
                {{ $personId ? 'Update Person' : 'Save Person' }}
            </button>
        </div>

    </form>
</div>
</div>
