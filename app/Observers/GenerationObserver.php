<?php

namespace App\Observers;

use App\Models\Generation;
use App\Services\GenerateMentorsCheck;

class GenerationObserver
{
    public function created(Generation $generation): void
    {
        try {
            GenerateMentorsCheck::generate($generation);
        } catch (\Exception $e) {
            // Update status to failed and log the error
            $generation->update(['status' => 'failed']);
            throw $e;
        }
    }
}
