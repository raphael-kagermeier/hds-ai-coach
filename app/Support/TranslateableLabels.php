<?php

namespace App\Support;

use Filament\Actions\StaticAction;
use Filament\Forms\Components\Field;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;

class TranslateableLabels
{
    public static function make(): void
    {
        Column::configureUsing(function (Column $column) {
            $column->translateLabel();
        });

        BaseFilter::configureUsing(function (BaseFilter $component) {
            $component->translateLabel();
        });

        StaticAction::configureUsing(function (StaticAction $component) {
            $component->translateLabel();
        });

        Field::configureUsing(function (Field $component) {
            $component->translateLabel();
        });
    }
}
