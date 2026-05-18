<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can view the login page', function () {
    $response = $this->get(route('login'));

    $response
        ->assertOk()
        ->assertSee('Sign In')
        ->assertSee('Sign in to manage captured people');
});

test('user can log in with valid credentials', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(RouteServiceProvider::HOME);

    $this->assertAuthenticatedAs($user);
});

test('user cannot log in with invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->from(route('login'))->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('authenticated user is redirected away from the login page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('login'));

    $response->assertRedirect(RouteServiceProvider::HOME);
});