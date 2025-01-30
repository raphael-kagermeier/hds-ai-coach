<?php

namespace App\Notifications;

use Spatie\Health\Notifications\CheckFailedNotification;

class CustomCheckFailedNotification extends CheckFailedNotification
{
    /**
     * @return array<string, string>
     */
    public function transParameters(): array
    {
        return [
            'application_name' => config('app.name') ?? config('app.url') ?? 'Laravel application',
            'env_name' => app()->environment(),
            'url' => url('/'),
        ];
    }
}
