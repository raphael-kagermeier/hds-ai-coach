<?php

namespace App\Services;

use App\Models\Generation;

class GenerateMentorsCheck
{
    public ?string $imageReviewText;

    public ?string $coachingCheckText;

    public function __construct(public Generation $generation) {}

    public static function generate(Generation $generation)
    {
        return (new self($generation))->generateMentorCheck();
    }

    public function generateMentorCheck()
    {
        $this->imageReviewText = $this->handleImageReview();
        $this->coachingCheckText = $this->handleCoachingCheck();

        return $this;
    }

    public function handleImageReview(): ?string
    {
        if (empty($this->generation->images)) {
            return null;
        }

        $imageReview = app(ImageReviewGeneration::class)->generate(
            $this->generation->base64_images,
            $this->generation->lesson->content ?? '',
            $this->generation->lesson->base64_images
        );

        $this->generation->update([
            'image_review' => $imageReview,
            'generated_text' => $imageReview,
            'final_text' => $imageReview,
            'status' => 'processing',
        ]);

        return $imageReview;
    }

    public function handleCoachingCheck(): ?string
    {
        if (empty($this->generation->image_review)) {
            return null;
        }

        $lesson = $this->generation->lesson;
        $course = $lesson->course;

        // Get all lesson contents from the course
        $courseLessonsContent = $course->lessons()
            ->orderBy('order_column')
            ->get()
            ->map(fn($lesson) => $lesson->formatted_content)
            ->toArray();

        $coachingCheck = app(CoachingResponseGeneration::class)->generate(
            $this->generation->image_review,
            $lesson->name,
            $lesson->formatted_content,
            $courseLessonsContent,
            $course->name,
            $this->generation->name
        );

        $this->generation->update([
            'generated_text' => $coachingCheck,
            'final_text' => $coachingCheck,
            'status' => 'completed',
        ]);

        return $coachingCheck;
    }
}
