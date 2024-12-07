<?php

use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs($this->basicUser);
});

it('can render the profile page', function () {
    livewire(MyProfilePage::class)
        ->assertSuccessful();
});
