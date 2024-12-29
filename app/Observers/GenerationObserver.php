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
        $lessonContent = $generation->lesson?->content ?? '';

        // Generate the prompt
        $prompt = "Please review these images in the context of the following lesson:\n\n{$lessonContent}\n\nProvide a detailed analysis of the images, focusing on the hairdressing techniques shown and how they relate to the lesson content.";

        try {
            $generatedText = $this->imageReviewService->generate($generation->image_paths, $prompt);

            // Update the generation with the AI response
            $generation->update([
                'generated_text' => $generatedText,
                'final_text' => $generatedText,
                'status' => 'completed'
            ]);
        } catch (\Exception $e) {
            // Update status to failed and log the error
            $generation->update(['status' => 'failed']);
            Log::error('Image review generation failed', [
                'generation_id' => $generation->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
