<?php

use App\Livewire\People\EditPerson;
use App\Jobs\SendPersonCapturedEmailJob;
use App\Models\Interest;
use App\Models\Language;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function validEditPersonPayload(Language $language, array $interestIds, array $overrides = []): array
{
    return array_merge([
        'form.name' => 'Updated',
        'form.surname' => 'Person',
        'form.sa_id_number' => '8201015009088',
        'form.mobile_number' => '0829876543',
        'form.email' => 'updated.person@example.com',
        'form.birth_date' => '1995-05-15',
        'form.language_id' => (string) $language->id,
        'form.interests' => array_map('strval', $interestIds),
    ], $overrides);
}

function fillEditPersonForm($component, array $payload)
{
    foreach ($payload as $field => $value) {
        $component->set($field, $value);
    }

    return $component;
}

function createEditPersonTestContext(): array
{
    $user = User::factory()->create();
    $language = Language::factory()->create(['name' => 'English']);
    $alternateLanguage = Language::factory()->create(['name' => 'Afrikaans']);
    $interests = Interest::factory()->count(3)->create();

    $person = Person::factory()->create([
        'created_by' => $user->id,
        'language_id' => $language->id,
        'name' => 'Original',
        'surname' => 'Person',
        'sa_id_number' => '8001015009087',
        'mobile_number' => '0821234567',
        'email' => 'original.person@example.com',
        'birth_date' => '1990-01-01',
    ]);

    $person->interests()->sync($interests->take(2)->pluck('id')->all());

    return [
        'user' => $user,
        'language' => $language,
        'alternateLanguage' => $alternateLanguage,
        'interests' => $interests,
        'person' => $person->load(['language', 'interests']),
    ];
}

test('guest is redirected to login from edit person page', function () {
    $person = Person::factory()->create();

    $response = $this->get(route('people.edit', $person));

    $response->assertRedirect(route('login'));
});

test('authenticated user can view the edit person page and see mounted values', function () {
    $context = createEditPersonTestContext();

    $response = $this->actingAs($context['user'])->get(route('people.edit', $context['person']));

    $response
        ->assertOk()
        ->assertSee('Edit Person')
        ->assertSee('Review and update an existing person record without leaving the Livewire workflow.')
        ->assertSee('Back to People')
        ->assertSee('Cancel')
        ->assertSee('Update Person')
        ->assertSee(route('people.index'), false);

    Livewire::actingAs($context['user'])
        ->test(EditPerson::class, ['person' => $context['person']->id])
        ->assertSet('form.name', 'Original')
        ->assertSet('form.surname', 'Person')
        ->assertSet('form.sa_id_number', '8001015009087')
        ->assertSet('form.mobile_number', '0821234567')
        ->assertSet('form.email', 'original.person@example.com')
        ->assertSet('form.birth_date', '1990-01-01')
        ->assertSet('form.language_id', (string) $context['language']->id)
        ->assertSet('form.interests', $context['interests']->take(2)->pluck('id')->map(fn ($id) => (string) $id)->all());
});

test('edit person page returns 404 for a missing record', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('people.edit', 999999));

    $response->assertNotFound();
});

test('authenticated user can update an existing person and is redirected to index without queueing the capture job', function () {
    $context = createEditPersonTestContext();

    Queue::fake();

    $payload = validEditPersonPayload(
        $context['alternateLanguage'],
        [$context['interests']->last()->id],
        [
            'form.name' => 'Edited',
            'form.surname' => 'Record',
            'form.sa_id_number' => '8201015009088',
            'form.mobile_number' => '0831112222',
            'form.email' => 'edited.record@example.com',
            'form.birth_date' => '1992-02-02',
        ]
    );

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('people.index'));

    $updatedPerson = $context['person']->fresh('interests');

    expect($updatedPerson->name)->toBe('Edited')
        ->and($updatedPerson->surname)->toBe('Record')
        ->and($updatedPerson->sa_id_number)->toBe('8201015009088')
        ->and($updatedPerson->mobile_number)->toBe('0831112222')
        ->and($updatedPerson->email)->toBe('edited.record@example.com')
        ->and($updatedPerson->language_id)->toBe($context['alternateLanguage']->id)
        ->and($updatedPerson->interests->pluck('id')->all())->toBe([$context['interests']->last()->id]);

    Queue::assertNotPushed(SendPersonCapturedEmailJob::class);
});

test('edit person form shows required field errors when submitted empty', function () {
    $context = createEditPersonTestContext();

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        [
            'form.name' => '',
            'form.surname' => '',
            'form.sa_id_number' => '',
            'form.mobile_number' => '',
            'form.email' => '',
            'form.birth_date' => '',
            'form.language_id' => '',
            'form.interests' => [],
        ]
    )
        ->call('save')
        ->assertHasErrors([
            'form.name' => 'required',
            'form.surname' => 'required',
            'form.sa_id_number' => 'required',
            'form.mobile_number' => 'required',
            'form.email' => 'required',
            'form.birth_date' => 'required',
            'form.language_id' => 'required',
            'form.interests' => 'required',
        ]);
});

test('edit person form allows keeping the current unique email and id number', function () {
    $context = createEditPersonTestContext();

    $payload = validEditPersonPayload(
        $context['language'],
        $context['interests']->take(2)->pluck('id')->all(),
        [
            'form.name' => 'Original Updated',
            'form.surname' => 'Person Updated',
            'form.sa_id_number' => '8001015009087',
            'form.email' => 'original.person@example.com',
        ]
    );

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasNoErrors();
});

test('edit person form validates unique email and id number against other records', function () {
    $context = createEditPersonTestContext();

    Person::factory()->create([
        'email' => 'existing@example.com',
        'sa_id_number' => '8201015009088',
    ]);

    $payload = validEditPersonPayload(
        $context['alternateLanguage'],
        [$context['interests']->last()->id],
        [
            'form.email' => 'existing@example.com',
            'form.sa_id_number' => '8201015009088',
        ]
    );

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.email' => 'unique',
            'form.sa_id_number' => 'unique',
        ]);
});

test('edit person form validates minimum lengths for id number and mobile number', function () {
    $context = createEditPersonTestContext();

    $payload = validEditPersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.sa_id_number' => '123456789012',
        'form.mobile_number' => '082123456',
    ]);

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.sa_id_number' => 'min',
            'form.mobile_number' => 'min',
        ]);
});

test('edit person form validates maximum lengths for names', function () {
    $context = createEditPersonTestContext();

    $payload = validEditPersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.name' => str_repeat('A', 256),
        'form.surname' => str_repeat('B', 256),
    ]);

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.name' => 'max',
            'form.surname' => 'max',
        ]);
});

test('edit person form validates email format', function () {
    $context = createEditPersonTestContext();

    $payload = validEditPersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.email' => 'not-an-email',
    ]);

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.email' => 'email',
        ]);
});

test('edit person form validates birth date is a valid past date', function () {
    $context = createEditPersonTestContext();

    $payload = validEditPersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.birth_date' => now()->format('Y-m-d'),
    ]);

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.birth_date' => 'before',
        ]);

    $payload['form.birth_date'] = 'not-a-date';

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.birth_date' => 'date',
        ]);
});

test('edit person form validates selected language and interests exist', function () {
    $context = createEditPersonTestContext();

    $payload = validEditPersonPayload($context['language'], [999999], [
        'form.language_id' => '999999',
        'form.interests' => ['999999'],
    ]);

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.language_id' => 'exists',
            'form.interests.0' => 'exists',
        ]);
});

test('edit person form requires at least one interest selection', function () {
    $context = createEditPersonTestContext();

    $payload = validEditPersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.interests' => [],
    ]);

    fillEditPersonForm(
        Livewire::actingAs($context['user'])->test(EditPerson::class, ['person' => $context['person']->id]),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.interests' => 'required',
        ]);
});