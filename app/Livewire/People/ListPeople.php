<?php

namespace App\Livewire\People;

use App\Services\Person\PersonService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('People')]
class ListPeople extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    protected PersonService $personService;

    #[Url(except: '')]
    public string $search = '';

    public int $perPage = 10;

    public string $notification = '';

    public ?int $pendingDeleteId = null;

    public string $pendingDeleteName = '';

    public function boot(PersonService $personService): void
    {
        $this->personService = $personService;
    }

    public function mount(): void
    {
        if (session()->has('status')) {
            $this->notification = session()->pull('status');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function confirmDeletePerson(int $personId, string $personName): void
    {
        $this->pendingDeleteId = $personId;
        $this->pendingDeleteName = $personName;
    }

    public function cancelDeletePerson(): void
    {
        $this->pendingDeleteId = null;
        $this->pendingDeleteName = '';
    }

    public function dismissNotification(): void
    {
        $this->notification = '';
    }

    public function deletePerson(): void
    {
        if (! $this->pendingDeleteId) {
            return;
        }

        $this->personService->delete($this->pendingDeleteId);

        $this->cancelDeletePerson();
        $this->notification = 'Person deleted successfully.';
    }

    #[Computed]
    public function people()
    {
        return $this->personService->personList($this->perPage, $this->search);
    }

    public function render()
    {
        return view('livewire.people.list-people');
    }
}
