<?php

beforeEach(function () {
    $this->actingAs($this->basicUser);
});

it('can render imprint page', function () {
    $this->get(route('filament.guest.pages.imprint'))->assertOk();
});

it('can render terms page', function () {
    $this->get(route('filament.guest.pages.terms'))->assertOk();
});

it('can render data policy page', function () {
    $this->get(route('filament.guest.pages.data-policy'))->assertOk();
});
