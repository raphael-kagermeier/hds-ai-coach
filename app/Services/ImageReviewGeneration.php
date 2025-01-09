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

    private function convertImagesToBase64Array(array $images): array
    {
        return array_map(function ($imagePath) {
            return [
                'type' => 'image_url',
                'image_url' => [
                    'url' => 'data:image/jpeg;base64,'.base64_encode(file_get_contents($imagePath)),
                ],
            ];
        }, $images);
    }

    public function generate(array $userImages, string $lessonContent, array $lessonImages = []): string
    {
        // Convert user images to base64
        $userImageContents = $this->convertImagesToBase64Array($userImages);

        // Convert lesson images to base64 if they exist
        $lessonImageContents = $lessonImages ? $this->convertImagesToBase64Array($lessonImages) : [];

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
