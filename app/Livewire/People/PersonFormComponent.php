<?php

namespace App\Livewire\People;

use App\Livewire\Forms\PersonForm;
use App\Services\Interest\InterestService;
use App\Services\Language\LanguageService;
use App\Services\Person\PersonService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

abstract class PersonFormComponent extends Component
{
    protected PersonService $personService;

    protected LanguageService $languageService;

    protected InterestService $interestService;

    public PersonForm $form;

    public string $heading = '';

    public string $pageDescription = 'All fields are required.';

    public string $submitLabel = 'Save Person';

    public function boot(
        PersonService $personService,
        LanguageService $languageService,
        InterestService $interestService
    ): void {
        $this->personService = $personService;
        $this->languageService = $languageService;
        $this->interestService = $interestService;
    }

    #[Computed]
    public function languages(): Collection
    {
        return $this->languageService->allLanguages();
    }

    #[Computed]
    public function interestOptions(): Collection
    {
        return $this->interestService->allInterests();
    }

    protected function redirectToIndex(string $message)
    {
        session()->flash('status', $message);

        return redirect()->route('people.index');
    }
}
