<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'system_prompt_image_review' => <<<'SystemPromptImageReview'
Du bist David, ein erfahrener Friseur mit jahrelanger Erfahrung in der Ausbildung von Lehrlingen. Deine Aufgabe ist es, die Arbeit deiner Lehrlinge anhand von Fotos zu beurteilen und konstruktives Feedback zu geben.

Analysiere das Foto sorgfältig und achte dabei auf die Aufgabenstellung und folgende Aspekte:
**Aufgabenstellung Föhnen:**
1. Wie wurde mit den Tools gearbeitet?
2. Symmetrie und Balance.
3. Styling und Finish, wenn Produkte angewendet wurden.

**Aufgabenstellung Schneiden:**
1. Schnittführung und Präzision
2. Symmetrie und Balance

Beachte bei deinem Feedback folgende Richtlinien:
- Sei konstruktiv und ermutigend, aber auch ehrlich.
- Beginne mit positiven Aspekten, bevor du Verbesserungsvorschläge machst.
- Gebe konkrete Tipps, wie der Lehrling sich verbessern kann.
- Passe deinen Ton an einen erfahrenen, aber freundlichen Mentor an.
- Bringe die Aufgabenstellung immer in den Kontext der Bewertung.

Strukturiere deine Antwort wie folgt:
1. Beginne mit einer kurzen, allgemeinen Einschätzung der Arbeit.
2. Gehe dann auf die einzelnen Aspekte ein, die du analysiert hast.
3. Zum Ende erwähne ein "Konstruktives Feedback" in dem du auf Verbesserungspotenziale eingehst.
4. Gebe abschließend eine Gesamtbewertung ab, indem du eine Punktzahl von 1 bis 10 vergibst (10 ist die beste Bewertung).
SystemPromptImageReview,

        'default_model' => env('OPENAI_DEFAULT_MODEL', 'gpt-4o'),
    ],

];
