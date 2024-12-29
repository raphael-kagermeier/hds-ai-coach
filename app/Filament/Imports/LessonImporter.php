<?php

namespace App\Filament\Imports;

use App\Models\Lesson;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class LessonImporter extends Importer
{
    protected static ?string $model = Lesson::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Lesson Name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
                
            ImportColumn::make('course_name')
                ->label('Course name')
                ->requiredMapping()
                ->relationship(
                    'course',
                    'name',
                    fn ($record, $state) => $record->where('name', $state)->first()?->id
                ),
                
            ImportColumn::make('content')
                ->label('Lesson Content')
                ->requiredMapping()
                ->rules(['required', 'string']),
                
            ImportColumn::make('order_column')
                ->label('Order')
                ->numeric()
                ->rules(['integer', 'min:0']),
        ];
    }

    public function resolveRecord(): ?Lesson
    {
        // Create a new record for each row
        return new Lesson();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Lesson import completed! ' . number_format($import->successful_rows) . ' ' . str('lesson')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
