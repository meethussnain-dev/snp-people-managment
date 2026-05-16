<?php

namespace App\Livewire\People;

use App\Services\PersonService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * @var string
     */
    protected $paginationTheme = 'bootstrap';

    /**
     * @var PersonService
     */
    protected $personService;

    /**
     * @var array<string, array<string, string>>
     */
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    /**
     * @var string
     */
    public $search = '';

    /**
     * @var int
     */
    public $perPage = 10;

    public function boot(PersonService $personService)
    {
        $this->personService = $personService;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deletePerson(int $personId)
    {
        $this->personService->delete($personId);

        session()->flash('status', 'Person deleted successfully.');
    }

    public function getPeopleProperty()
    {
        return $this->personService->paginate($this->perPage, $this->search);
    }

    public function render()
    {
        return view('livewire.people.index');
    }
}