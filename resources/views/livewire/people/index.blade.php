<div>
<div class="container">

    {{-- Page Header --}}
    <div class="page-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h1><i class="bi bi-people me-2" style="color:#2563eb;"></i>People</h1>
            <p>Manage captured people and keep their profile data current.</p>
        </div>
        <a href="{{ route('people.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4">
            <i class="bi bi-plus-lg"></i> Add Person
        </a>
    </div>

    {{-- Flash message from Livewire --}}
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">

        {{-- Filters --}}
        <div class="card-header">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label mb-1">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-color:#cbd5e1;">
                            <i class="bi bi-search" style="color:#94a3b8;font-size:0.8rem;"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 ps-0"
                               placeholder="Name, surname, email, mobile or ID…"
                               wire:model.debounce.400ms="search">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-1">Rows per page</label>
                    <select class="form-select" wire:model="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table snp-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th style="white-space:nowrap">SA ID Number</th>
                        <th style="white-space:nowrap">Mobile</th>
                        <th>Email</th>
                        <th style="white-space:nowrap">Birth Date</th>
                        <th style="white-space:nowrap">Language</th>
                        <th>Interests</th>
                        <th class="text-end" style="white-space:nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->people as $person)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="color:#0f172a;">{{ $person->name }} {{ $person->surname }}</div>
                            </td>
                            <td style="font-family:monospace;font-size:0.82rem;color:#475569;white-space:nowrap">{{ $person->sa_id_number }}</td>
                            <td style="white-space:nowrap">{{ $person->mobile_number }}</td>
                            <td>
                                <div style="max-width:155px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#475569;" title="{{ $person->email }}">
                                    {{ $person->email }}
                                </div>
                            </td>
                            <td style="white-space:nowrap">{{ $person->birth_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge-lang">{{ $person->language->name }}</span>
                            </td>
                            <td>
                                @php
                                    $visible = $person->interests->take(2);
                                    $overflow = $person->interests->count() - $visible->count();
                                    $allNames = $person->interests->pluck('name')->implode(', ');
                                @endphp
                                @if ($person->interests->isNotEmpty())
                                    <div class="interests-cell">
                                        @foreach ($visible as $interest)
                                            <span class="badge-interest">{{ $interest->name }}</span>
                                        @endforeach
                                        @if ($overflow > 0)
                                            <span class="badge-overflow"
                                                  data-bs-toggle="tooltip"
                                                  data-bs-placement="top"
                                                  title="{{ $allNames }}">+{{ $overflow }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span style="color:#94a3b8;font-size:0.8rem;">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('people.edit', $person->id) }}"
                                       class="btn btn-icon btn-outline-primary"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-icon btn-outline-danger"
                                            title="Delete"
                                            onclick="if (confirm('Permanently delete {{ addslashes($person->name . ' ' . $person->surname) }}?')) { @this.deletePerson({{ $person->id }}) }">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center gap-2" style="color:#94a3b8;">
                                    <i class="bi bi-person-x fs-1"></i>
                                    <div style="font-weight:600;color:#64748b;">No people found</div>
                                    <div style="font-size:0.82rem;">Try adjusting your search or add a new person.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($this->people->hasPages())
            <div class="card-footer px-4 py-3 d-flex align-items-center justify-content-between">
                <div style="font-size:0.8rem;color:#64748b;">
                    Showing {{ $this->people->firstItem() }}–{{ $this->people->lastItem() }} of {{ $this->people->total() }} people
                </div>
                {{ $this->people->links() }}
            </div>
        @endif
    </div>

    <div class="pb-5"></div>
</div>
</div>
