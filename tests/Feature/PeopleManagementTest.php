<?php

namespace Tests\Feature;

use App\Livewire\People\Form;
use App\Jobs\SendPersonCapturedEmailJob;
use App\Models\Interest;
use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class PeopleManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_guest_is_redirected_to_login_from_people_index()
    {
        $response = $this->get(route('people.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * @return void
     */
    public function test_authenticated_user_can_create_a_person_and_queue_email_job()
    {
        Queue::fake();
        $this->seed();

        $user = User::where('email', 'admin@snp.test')->firstOrFail();
        $languageId = Language::query()->value('id');
        $interestIds = Interest::query()->limit(2)->pluck('id')->map(function ($id) {
            return (string) $id;
        })->all();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('name', 'John')
            ->set('surname', 'Doe')
            ->set('sa_id_number', '8001015009087')
            ->set('mobile_number', '0821234567')
            ->set('email', 'john.doe@example.com')
            ->set('birth_date', '1980-01-01')
            ->set('language_id', (string) $languageId)
            ->set('interests', $interestIds)
            ->call('submit')
            ->assertRedirect(route('people.index'));

        $this->assertDatabaseHas('people', [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        Queue::assertPushed(SendPersonCapturedEmailJob::class);
    }

    /**
     * @return void
     */
    public function test_person_form_validates_required_fields()
    {
        $this->seed();
        $user = User::where('email', 'admin@snp.test')->firstOrFail();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->call('submit')
            ->assertHasErrors([
                'name' => 'required',
                'surname' => 'required',
                'sa_id_number' => 'required',
                'mobile_number' => 'required',
                'email' => 'required',
                'birth_date' => 'required',
                'language_id' => 'required',
                'interests' => 'required',
            ]);
    }
}
