<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Collection;

class Home extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.home';

    protected static ?string $slug = '';

    public ?Collection $quote = null;

    protected static bool $shouldRegisterNavigation = false;

    protected ?string $heading = '';

    public function getQuote(): \Illuminate\Support\Collection
    {
        $quote = Inspiring::quotes()->random();
        return str($quote)->explode('-');
    }

    public function mount(){
        $this->quote = $this->getQuote();
    }
}
