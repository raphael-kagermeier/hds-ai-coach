<?php

namespace App\Services;

use OpenAI\Client;
use Illuminate\Support\Str;

class CoachingResponseGeneration
{
    protected Client $client;

    protected string $systemPrompt;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->systemPrompt = config('services.openai.system_prompt_paraphrase');
    }

    public function generate(string $imageReview, string $current_lesson_title, string $currentLessonContent, array $courseLessonsContent, string $courseName, string $talentName): string
    {
        $systemPrompt = Str::replace(
            [
                ':talent_name',
                ':current_lesson_title',
                ':current_lesson_content',
                ':lesson_contents',
                ':course_name'
            ],
            [
                $talentName,
                $current_lesson_title,
                $currentLessonContent,
                implode("\n\n", $courseLessonsContent),
                $courseName
            ],
            $this->systemPrompt
        );

        $userContent = [
            [
                'type' => 'text',
                'text' => $imageReview,
            ],
        ];

        $response = $this->client->chat()->create([
            'model' => config('services.openai.default_model'),
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $userContent,
                ],
            ],
            'max_tokens' => 1000,
        ]);
        return $response->choices[0]->message->content;
    }
}
