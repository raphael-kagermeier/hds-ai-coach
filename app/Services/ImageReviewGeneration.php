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

    public function generate(array $imagePaths, string $prompt): string
    {
        // Convert images to base64
        $imageContents = array_map(function ($imagePath) {
            return [
                'type' => 'image_url',
                'image_url' => [
                    'url' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents($imagePath))
                ]
            ];
        }, $imagePaths);

        $content = [
            [
                'type' => 'text',
                'text' => $prompt
            ],
            ...$imageContents
        ];

        $response = $this->client->chat()->create([
            'model' => config('services.openai.default_model'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $content
                ]
            ],
            'max_tokens' => 1000
        ]);

        return $response->choices[0]->message->content;
    }
}
