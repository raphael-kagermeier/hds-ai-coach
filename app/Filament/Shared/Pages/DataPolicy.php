<?php

namespace App\Filament\Shared\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class DataPolicy extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.data-policy'; // TODO: check data policy

    public function getTitle(): string|Htmlable
    {
        return __('Data Policy');
    }
}
