<?php

namespace App\Livewire\People;

use App\Repositories\Contracts\InterestCatalogRepositoryInterface;
use App\Repositories\Contracts\LanguageRepositoryInterface;
use App\Services\PersonService;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    /**
     * @var PersonService
     */
    protected $personService;

    /**
     * @var LanguageRepositoryInterface
     */
    protected $languageRepository;

    /**
    * @var InterestCatalogRepositoryInterface
     */
    protected $interestRepository;

    /**
     * @var int|null
     */
    public $personId;

    /**
     * @var string
     */
    public $heading = 'Add Person';

    public $name = '';

    public $surname = '';

    public $sa_id_number = '';

    public $mobile_number = '';

    public $email = '';

    public $birth_date = '';

    public $language_id = '';

    public $interests = [];

    public function boot(
        PersonService $personService,
        LanguageRepositoryInterface $languageRepository,
        InterestCatalogRepositoryInterface $interestRepository
    ) {
        $this->personService = $personService;
        $this->languageRepository = $languageRepository;
        $this->interestRepository = $interestRepository;
    }

    public function mount($person = null): void
    {
        if (! $person) {
            return;
        }

        $person = $this->personService->findForEdit((int) $person);

        $this->personId = $person->id;
        $this->heading = 'Edit Person';
        $this->name = $person->name;
        $this->surname = $person->surname;
        $this->sa_id_number = $person->sa_id_number;
        $this->mobile_number = $person->mobile_number;
        $this->email = $person->email;
        $this->birth_date = $person->birth_date->format('Y-m-d');
        $this->language_id = (string) $person->language_id;
        $this->interests = $person->interests->pluck('id')->map(function ($id) {
            return (string) $id;
        })->all();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'sa_id_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('people', 'sa_id_number')->ignore($this->personId),
            ],
            'mobile_number' => ['required', 'string', 'min:10', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('people', 'email')->ignore($this->personId)],
            'birth_date' => ['required', 'date', 'before:today'],
            'language_id' => ['required', 'exists:languages,id'],
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['exists:interests,id'],
        ];
    }

    public function getLanguagesProperty()
    {
        return $this->languageRepository->all(['id', 'name'], [], 'name');
    }

    public function getInterestOptionsProperty()
    {
        return $this->interestRepository->all(['id', 'name'], [], 'name');
    }

    public function submit()
    {
        $validated = $this->validate();

        if ($this->personId) {
            $this->personService->update($this->personId, $validated);
            session()->flash('status', 'Person updated successfully.');

            return redirect()->route('people.index');
        }

        $payload = Arr::add($validated, 'created_by', auth()->id());
        $this->personService->create($payload);

        session()->flash('status', 'Person created successfully and email sent.');

        return redirect()->route('people.index');
    }

    public function render()
    {
        return view('livewire.people.form');
    }
}