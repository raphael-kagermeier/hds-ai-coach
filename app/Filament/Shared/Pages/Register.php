<?php

namespace App\Filament\Shared\Pages;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getTermsFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getTermsFormComponent(): Component
    {
        return Checkbox::make('terms')
            ->label(new HtmlString("<div class='text-xs text-gray-500 dark:text-gray-400'>" .
                trans('By clicking you accept our <a class="underline" href=":href" >terms and conditions</a>', [
                        'href' => route('filament.guest.pages.terms')]
                ) .
                "</div>"))
            ->markAsRequired(false)
            ->required();
    }
}
