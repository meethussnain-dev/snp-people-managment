<?php

use App\Livewire\People\ListPeople;
use App\Models\Interest;
use App\Models\Language;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createDeletePersonContext(): array
{
    $user = User::factory()->create();
    $language = Language::factory()->create(['name' => 'English']);
    $interests = Interest::factory()->count(2)->create();

    return [
        'user' => $user,
        'language' => $language,
        'interests' => $interests,
    ];
}

function createDeletablePerson(array $context, array $attributes = [], ?array $interestIds = null): Person
{
    $person = Person::factory()->create(array_merge([
        'created_by' => $context['user']->id,
        'language_id' => $context['language']->id,
        'name' => 'Delete',
        'surname' => 'Target',
        'email' => fake()->unique()->safeEmail(),
    ], $attributes));

    $person->interests()->sync($interestIds ?? [$context['interests']->first()->id]);

    return $person->load(['language', 'interests']);
}

test('guest is redirected to login from people delete flow', function () {
    $person = Person::factory()->create();

    $response = $this->get(route('people.index'));

    $response->assertRedirect(route('login'));
    expect(Person::query()->whereKey($person->id)->exists())->toBeTrue();
});

test('people list can open and cancel the delete confirmation modal', function () {
    $context = createDeletePersonContext();
    $person = createDeletablePerson($context, [
        'name' => 'Delete',
        'surname' => 'Me',
    ]);

    Livewire::actingAs($context['user'])
        ->test(ListPeople::class)
        ->call('confirmDeletePerson', $person->id, 'Delete Me')
        ->assertSet('pendingDeleteId', $person->id)
        ->assertSet('pendingDeleteName', 'Delete Me')
        ->assertSee('Delete Person')
        ->assertSee('This person record will be deleted permanently. Do you wish to continue?')
        ->call('cancelDeletePerson')
        ->assertSet('pendingDeleteId', null)
        ->assertSet('pendingDeleteName', '')
        ->assertDontSee('Yes, delete permanently');

    expect(Person::query()->whereKey($person->id)->exists())->toBeTrue();
});

test('people list can delete a person and show a success notification', function () {
    $context = createDeletePersonContext();
    $person = createDeletablePerson($context, [
        'name' => 'Remove',
        'surname' => 'Target',
        'email' => 'remove.target@example.com',
    ]);

    Livewire::actingAs($context['user'])
        ->test(ListPeople::class)
        ->call('confirmDeletePerson', $person->id, 'Remove Target')
        ->call('deletePerson')
        ->assertSet('pendingDeleteId', null)
        ->assertSet('pendingDeleteName', '')
        ->assertSet('notification', 'Person deleted successfully.')
        ->assertSee('Person deleted successfully.')
        ->assertDontSee('Remove Target');

    expect(Person::query()->whereKey($person->id)->exists())->toBeFalse();
});

test('delete person does nothing when no delete target is selected', function () {
    $context = createDeletePersonContext();
    $person = createDeletablePerson($context);

    Livewire::actingAs($context['user'])
        ->test(ListPeople::class)
        ->call('deletePerson')
        ->assertSet('notification', '')
        ->assertSet('pendingDeleteId', null);

    expect(Person::query()->whereKey($person->id)->exists())->toBeTrue();
});