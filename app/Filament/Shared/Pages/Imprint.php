<?php

namespace App\Filament\Shared\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Imprint extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.imprint';

    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string|Htmlable
    {
        return __('Imprint');
    }
}
