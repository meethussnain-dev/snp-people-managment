<?php

namespace App\Livewire\People;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Create Person')]
class CreatePerson extends PersonFormComponent
{
    public string $heading = 'Create Person';

    public string $pageDescription = 'Capture a new person record with validated identity, language, and interest details.';

    public string $submitLabel = 'Save Person';

    public function save()
    {
        $this->personService->create($this->form->payloadWithCreator());

        return $this->redirectToIndex('Person created successfully and email sent.');
    }

    public function render()
    {
        return view('livewire.people.create-edit-form');
    }
}
