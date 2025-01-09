<?php

namespace App\Observers;

use App\Models\Generation;
use App\Services\ImageReviewGeneration;
use Illuminate\Support\Facades\Log;

class GenerationObserver
{
    public function __construct(
        protected ImageReviewGeneration $imageReviewService
    ) {}

    public function created(Generation $generation): void
    {
        if (empty($generation->images)) {
            return;
        }

        // Get the lesson content as context for the prompt
        $lessonContent = $generation->lesson->content ?? '';

        $generatedText = $this->imageReviewService->generate($generation->image_paths, $lessonContent, $generation->lesson->image_paths);

        try {

            // Update the generation with the AI response
            $generation->update([
                'generated_text' => $generatedText,
                'final_text' => $generatedText,
                'status' => 'completed',
            ]);
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
