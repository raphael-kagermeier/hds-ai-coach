<?php

use Filament\Facades\Filament;
use Filament\Pages\Auth\Login;

use function Pest\Livewire\livewire;

test('an unauthenticated user can access the login page', function () {
    auth()->logout();

    $this->get(Filament::getLoginUrl())
        ->assertOk();
});

test('an unauthenticated user can not access the app panel', function () {
    auth()->logout();

    $this->get('app')
        ->assertRedirect(Filament::getLoginUrl());
});

test('an unauthenticated user can login', function () {
    auth()->logout();

    livewire(Login::class)
        ->fillForm([
            'email' => $this->basicUser->email,
            'password' => 'password',
        ])
        ->assertActionExists('authenticate')
        ->call('authenticate')
        ->assertHasNoFormErrors();
});

test('an authenticated user can logout', function () {
    $this->actingAs($this->basicUser);
    $this->assertAuthenticated();

    $this->post(Filament::getLogoutUrl());

    $this->get(Filament::getLoginUrl())->assertRedirect();
});
