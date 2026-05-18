<?php

namespace App\Livewire\Forms;

use App\Http\Requests\PersonSaveRequest;
use App\Models\Person;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class PersonForm extends Form
{
    public ?int $personId = null;

    public string $name = '';

    public string $surname = '';

    public string $sa_id_number = '';

    public string $mobile_number = '';

    public string $email = '';

    public string $birth_date = '';

    public string $language_id = '';

    public array $interests = [];

    protected function rules(): array
    {
        return PersonSaveRequest::rulesFor($this->personId);
    }

    public function fillFromPerson(Person $person): void
    {
        $this->personId = $person->id;
        $this->name = $person->name;
        $this->surname = $person->surname;
        $this->sa_id_number = $person->sa_id_number;
        $this->mobile_number = $person->mobile_number;
        $this->email = $person->email;
        $this->birth_date = $person->birth_date->format('Y-m-d');
        $this->language_id = (string) $person->language_id;
        $this->interests = $person->interests
            ->pluck('id')
            ->map(static fn ($id) => (string) $id)
            ->all();
    }

    public function payload(): array
    {
        return $this->validate();
    }

    public function payloadWithCreator(): array
    {
        return Arr::add($this->payload(), 'created_by', (int) Auth::id());
    }
}
