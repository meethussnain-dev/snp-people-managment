<div>
    <div class="container">
        @if ($notification)
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ $notification }}
                <button type="button" class="btn-close" aria-label="Close" wire:click="dismissNotification"></button>
            </div>
        @endif

        <x-headers.page-header
            title="People"
            description="Manage captured people and keep their profile data current."
            icon="people"
        >
            <a href="{{ route('people.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4">
                <i class="bi bi-plus-lg"></i> Add Person
            </a>
        </x-headers.page-header>

        <div class="card">
            <div class="card-header">
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <x-form.search-input
                            name="search"
                            label="Search"
                            wire-model="search"
                            placeholder="Name, surname, email, mobile or ID..."
                        />
                    </div>
                    <div class="col-md-4">
                        <x-form.select-input
                            name="perPage"
                            label="Rows per page"
                            wire-model="perPage"
                            :options="[10 => '10', 25 => '25', 50 => '50']"
                            wire-modifier="live"
                        />
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table pms-table mb-0">
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
                                    <x-people.interests-badges :interests="$person->interests" />
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('people.edit', $person->id) }}"
                                           class="btn btn-icon btn-outline-primary"
                                           title="Edit Person">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-icon btn-outline-danger"
                                                title="Delete Person"
                                                wire:click="confirmDeletePerson({{ $person->id }}, @js($person->name . ' ' . $person->surname))">
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
                        Showing {{ $this->people->firstItem() }}-{{ $this->people->lastItem() }} of {{ $this->people->total() }} people
                    </div>
                    {{ $this->people->links() }}
                </div>
            @endif
        </div>

        <div class="pb-5"></div>
    </div>

    @if ($pendingDeleteId)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-modal="true" wire:keydown.escape.window="cancelDeletePerson">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
                    <div class="modal-header" style="padding:1.1rem 1.25rem;border-bottom:1px solid #e2e8f0;">
                        <div>
                            <h5 class="modal-title mb-1" style="font-weight:700;color:#0f172a;">Delete Person</h5>
                            <div style="font-size:0.82rem;color:#64748b;">This action cannot be undone.</div>
                        </div>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="cancelDeletePerson"></button>
                    </div>

                    <div class="modal-body" style="padding:1.25rem;">
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:44px;height:44px;border-radius:12px;background:#fef2f2;color:#dc2626;">
                                <i class="bi bi-exclamation-triangle fs-5"></i>
                            </div>
                            <div>
                                <div style="font-weight:600;color:#0f172a;">{{ $pendingDeleteName }}</div>
                                <p class="mb-0 mt-2" style="color:#475569;font-size:0.9rem;line-height:1.6;">
                                    This person record will be deleted permanently. Do you wish to continue?
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" style="padding:1rem 1.25rem;border-top:1px solid #e2e8f0;">
                        <button type="button" class="btn btn-outline-secondary px-4" wire:click="cancelDeletePerson">
                            No, keep it
                        </button>
                        <button type="button" class="btn btn-danger px-4 d-inline-flex align-items-center gap-2" wire:click="deletePerson">
                            <i class="bi bi-trash3"></i>
                            Yes, delete permanently
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-backdrop fade show"></div>
    @endif
</div>
