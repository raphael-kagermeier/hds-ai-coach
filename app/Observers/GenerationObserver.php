<?php

namespace App\Observers;

use App\Models\Generation;
use App\Services\GenerateMentorsCheck;
use Illuminate\Support\Facades\Log;

class GenerationObserver
{
    public function created(Generation $generation): void
    {
        try {
            GenerateMentorsCheck::generate($generation);
        } catch (\Exception $e) {
            // Update status to failed and log the error
            $generation->update(['status' => 'failed']);
            Log::error('Image review generation failed', [
                'generation_id' => $generation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
