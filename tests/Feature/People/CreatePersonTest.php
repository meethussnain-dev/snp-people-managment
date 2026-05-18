<?php

use App\Livewire\People\CreatePerson;
use App\Jobs\SendPersonCapturedEmailJob;
use App\Models\Interest;
use App\Models\Language;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function validCreatePersonPayload(Language $language, array $interestIds, array $overrides = []): array
{
    return array_merge([
        'form.name' => 'Jane',
        'form.surname' => 'Doe',
        'form.sa_id_number' => '8001015009087',
        'form.mobile_number' => '0821234567',
        'form.email' => 'jane.doe@example.com',
        'form.birth_date' => '2000-01-01',
        'form.language_id' => (string) $language->id,
        'form.interests' => array_map('strval', $interestIds),
    ], $overrides);
}

function fillCreatePersonForm($component, array $payload)
{
    foreach ($payload as $field => $value) {
        $component->set($field, $value);
    }

    return $component;
}

function createPersonTestContext(): array
{
    return [
        'user' => User::factory()->create(),
        'language' => Language::factory()->create(),
        'interests' => Interest::factory()->count(2)->create(),
    ];
}

test('guest is redirected to login from create person page', function () {
    $response = $this->get(route('people.create'));

    $response->assertRedirect(route('login'));
});

test('authenticated user can view the create person page and its navigation actions', function () {
    $context = createPersonTestContext();

    $response = $this->actingAs($context['user'])->get(route('people.create'));

    $response
        ->assertOk()
        ->assertSee('Create Person')
        ->assertSee('Capture a new person record with validated identity, language, and interest details.')
        ->assertSee('Personal Information')
        ->assertSee('Contact Information')
        ->assertSeeText('Language & Interests')
        ->assertSee('Back to People')
        ->assertSee('Cancel')
        ->assertSee('Save Person')
        ->assertSee(route('people.index'), false);
});

test('authenticated user can create a person with the minimum valid payload and queue the email job', function () {
    $context = createPersonTestContext();

    Queue::fake();

    $payload = validCreatePersonPayload($context['language'], [$context['interests']->first()->id], [
        'form.name' => 'Jo',
        'form.surname' => 'Li',
        'form.mobile_number' => '0123456789',
        'form.email' => 'jo.li@example.com',
    ]);

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('people.index'));

    $person = Person::query()->where('email', 'jo.li@example.com')->firstOrFail();

    expect($person->created_by)->toBe($context['user']->id)
        ->and($person->language_id)->toBe($context['language']->id)
        ->and($person->interests->pluck('id')->all())->toBe([$context['interests']->first()->id]);

    Queue::assertPushed(SendPersonCapturedEmailJob::class, fn (SendPersonCapturedEmailJob $job) => $job->person->is($person));
});

test('create person form shows required field errors when submitted empty', function () {
    $context = createPersonTestContext();

    Livewire::actingAs($context['user'])
        ->test(CreatePerson::class)
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

test('create person form validates minimum lengths for id number and mobile number', function () {
    $context = createPersonTestContext();

    $payload = validCreatePersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.sa_id_number' => '123456789012',
        'form.mobile_number' => '082123456',
    ]);

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.sa_id_number' => 'min',
            'form.mobile_number' => 'min',
        ]);
});

test('create person form validates maximum lengths for names', function () {
    $context = createPersonTestContext();

    $payload = validCreatePersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.name' => str_repeat('A', 256),
        'form.surname' => str_repeat('B', 256),
    ]);

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.name' => 'max',
            'form.surname' => 'max',
        ]);
});

test('create person form validates email format', function () {
    $context = createPersonTestContext();

    $payload = validCreatePersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.email' => 'not-an-email',
    ]);

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.email' => 'email',
        ]);
});

test('create person form validates birth date is a valid past date', function () {
    $context = createPersonTestContext();

    $payload = validCreatePersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.birth_date' => now()->format('Y-m-d'),
    ]);

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.birth_date' => 'before',
        ]);

    $payload['form.birth_date'] = 'not-a-date';

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.birth_date' => 'date',
        ]);
});

test('create person form validates unique email and id number', function () {
    $context = createPersonTestContext();

    Person::factory()->create([
        'email' => 'existing@example.com',
        'sa_id_number' => '8001015009087',
    ]);

    $payload = validCreatePersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.email' => 'existing@example.com',
    ]);

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.email' => 'unique',
            'form.sa_id_number' => 'unique',
        ]);
});

test('create person form validates selected language and interests exist', function () {
    $context = createPersonTestContext();

    $payload = validCreatePersonPayload($context['language'], [999999], [
        'form.language_id' => '999999',
        'form.interests' => ['999999'],
    ]);

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.language_id' => 'exists',
            'form.interests.0' => 'exists',
        ]);
});

test('create person form requires at least one interest selection', function () {
    $context = createPersonTestContext();

    $payload = validCreatePersonPayload($context['language'], $context['interests']->pluck('id')->all(), [
        'form.interests' => [],
    ]);

    fillCreatePersonForm(
        Livewire::actingAs($context['user'])->test(CreatePerson::class),
        $payload
    )
        ->call('save')
        ->assertHasErrors([
            'form.interests' => 'required',
        ]);
});