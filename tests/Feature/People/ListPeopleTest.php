<?php

use App\Livewire\People\ListPeople;
use App\Models\Interest;
use App\Models\Language;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createListPeopleContext(): array
{
    $user = User::factory()->create();
    $language = Language::factory()->create(['name' => 'English']);
    $secondaryLanguage = Language::factory()->create(['name' => 'Afrikaans']);
    $interests = Interest::factory()->count(3)->create();

    return [
        'user' => $user,
        'language' => $language,
        'secondaryLanguage' => $secondaryLanguage,
        'interests' => $interests,
    ];
}

function createListedPerson(array $context, array $attributes = [], ?array $interestIds = null): Person
{
    $person = Person::factory()->create(array_merge([
        'created_by' => $context['user']->id,
        'language_id' => $context['language']->id,
        'name' => 'Listed',
        'surname' => 'Person',
        'sa_id_number' => fake()->unique()->numerify('#############'),
        'mobile_number' => fake()->numerify('08########'),
        'email' => fake()->unique()->safeEmail(),
        'birth_date' => '1990-01-01',
    ], $attributes));

    $person->interests()->sync($interestIds ?? [$context['interests']->first()->id]);

    return $person->load(['language', 'interests']);
}

test('guest is redirected to login from people index', function () {
    $response = $this->get(route('people.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated user can view the people list page with table search actions and logout control', function () {
    $context = createListPeopleContext();
    $person = createListedPerson($context, [
        'name' => 'John',
        'surname' => 'Doe',
        'email' => 'john.doe@example.com',
        'mobile_number' => '0821234567',
        'sa_id_number' => '8001015009087',
    ]);

    $response = $this->actingAs($context['user'])->get(route('people.index'));

    $response
        ->assertOk()
        ->assertSee('People')
        ->assertSee('Manage captured people and keep their profile data current.')
        ->assertSee('Add Person')
        ->assertSee('Search')
        ->assertSee('Rows per page')
        ->assertSee('Name')
        ->assertSee('SA ID Number')
        ->assertSee('Mobile')
        ->assertSee('Email')
        ->assertSee('Birth Date')
        ->assertSee('Language')
        ->assertSee('Interests')
        ->assertSee('Actions')
        ->assertSee('John Doe')
        ->assertSee('john.doe@example.com')
        ->assertSee('0821234567')
        ->assertSee('8001015009087')
        ->assertSee('Edit Person')
        ->assertSee('Delete Person')
        ->assertSee('Logout')
        ->assertSee(route('people.create'), false)
        ->assertSee(route('people.edit', $person), false)
        ->assertSee(route('logout'), false);
});

test('people index shows an empty state when no people exist', function () {
    $context = createListPeopleContext();

    $response = $this->actingAs($context['user'])->get(route('people.index'));

    $response
        ->assertOk()
        ->assertSee('No people found')
        ->assertSee('Try adjusting your search or add a new person.');
});

test('people list can search by name surname email mobile and south african id number', function () {
    $context = createListPeopleContext();

    createListedPerson($context, [
        'name' => 'Alpha',
        'surname' => 'Person',
        'email' => 'alpha@example.com',
        'mobile_number' => '0821111111',
        'sa_id_number' => '8001015009087',
    ]);

    createListedPerson($context, [
        'name' => 'Beta',
        'surname' => 'Record',
        'email' => 'beta@example.com',
        'mobile_number' => '0822222222',
        'sa_id_number' => '8201015009088',
    ], [$context['interests']->last()->id]);

    Livewire::actingAs($context['user'])
        ->test(ListPeople::class)
        ->set('search', 'Alpha')
        ->assertSee('Alpha Person')
        ->assertDontSee('Beta Record')
        ->set('search', 'Record')
        ->assertSee('Beta Record')
        ->assertDontSee('Alpha Person')
        ->set('search', 'beta@example.com')
        ->assertSee('Beta Record')
        ->set('search', '0821111111')
        ->assertSee('Alpha Person')
        ->set('search', '8201015009088')
        ->assertSee('Beta Record');
});

test('people list paginates results and can change rows per page', function () {
    $context = createListPeopleContext();

    foreach (range(1, 12) as $number) {
        createListedPerson($context, [
            'name' => 'Person',
            'surname' => (string) $number,
            'email' => "person{$number}@example.com",
            'sa_id_number' => str_pad((string) (8001015009000 + $number), 13, '0', STR_PAD_LEFT),
        ]);
    }

    $response = $this->actingAs($context['user'])->get(route('people.index'));

    $response
        ->assertOk()
        ->assertSee('Showing 1-10 of 12 people')
        ->assertSee('Next');

    Livewire::actingAs($context['user'])
        ->test(ListPeople::class)
        ->set('perPage', 25)
        ->assertSet('perPage', 25)
        ->assertSee('Person 1')
        ->assertSee('Person 12')
        ->assertDontSee('Showing 1-10 of 12 people');
});

test('people list shows flashed status notifications and allows dismissing them', function () {
    $context = createListPeopleContext();

    session()->flash('status', 'Person updated successfully.');

    Livewire::actingAs($context['user'])
        ->test(ListPeople::class)
        ->assertSet('notification', 'Person updated successfully.')
        ->assertSee('Person updated successfully.')
        ->call('dismissNotification')
        ->assertSet('notification', '')
        ->assertDontSee('Person updated successfully.');
});

test('authenticated user can logout from the application shell', function () {
    $context = createListPeopleContext();

    $response = $this->actingAs($context['user'])->post(route('logout'));

    $response->assertRedirect('/');
    $this->assertGuest();
});