<?php

namespace App\Services;

use OpenAI\Client;

class ImageReviewGeneration
{
    protected Client $client;

    protected string $systemPrompt;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->systemPrompt = config('services.openai.system_prompt_image_review');
    }

    private function prepareImages(array $images): array
    {
        return collect($images)
            ->filter()
            ->map(fn($base64_image) => [
                'type' => 'image_url',
                'image_url' => [
                    'url' => $base64_image,
                ],
            ])
            ->values()
            ->all();
    }

    public function generate(array $userImages, string $lessonContent, array $lessonImages = []): string
    {
        // Convert user images to base64
        $userImageContents = $this->prepareImages($userImages);

        // Convert lesson images to base64 if they exist
        $lessonImageContents = $lessonImages ? $this->prepareImages($lessonImages) : [];

        $userContent = [
            [
                'type' => 'text',
                'text' => 'Das sind meine Bilder',
            ],
            ...$userImageContents,
        ];

        $associatedContent = [
            [
                'type' => 'text',
                'text' => match (true) {
                    ! empty($lessonImageContents) && $lessonContent => "Das sind die Bilder aus der Lektion (Abenteuer) welche als vorlage für die Bewertung dienen. Folgendes sind außerdem meine Notizen aus der Lektion: {$lessonContent}",
                    ! empty($lessonImageContents) => 'Das sind die Bilder aus der Lektion (Abenteuer) welche als vorlage für die Bewertung dienen.',
                    $lessonContent => "Folgendes sind meine Notizen aus der Lektion: {$lessonContent}",
                    default => '',
                },
            ],
            ...$lessonImageContents,
        ];

        $assistantContent = [
            [
                'type' => 'text',
                'text' => 'Ok Verstehe. Nun teile bitte deine Arbeit im chat',
            ],
        ];

        $response = $this->client->chat()->create([
            'model' => config('services.openai.default_model'),
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $associatedContent,
                ],
                [
                    'role' => 'assistant',
                    'content' => $assistantContent,
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
