<?php

namespace App\Livewire\People;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Edit Person')]
class EditPerson extends PersonFormComponent
{
    public string $heading = 'Edit Person';

    public string $icon = 'pencil-square';

    public string $pageDescription = 'Review and update an existing person record without leaving the Livewire workflow.';

    public string $submitLabel = 'Update Person';

    public function mount(int|string $person): void
    {
        $record = $this->personService->find((int) $person, ['interests', 'language']);

        $this->form->fillFromPerson($record);
    }

    public function save()
    {
        $this->personService->update($this->form->payload(), (int) $this->form->personId);

        return $this->redirectToIndex('Person updated successfully.');
    }

    public function render()
    {
        return view('livewire.people.create-edit-form');
    }
}
