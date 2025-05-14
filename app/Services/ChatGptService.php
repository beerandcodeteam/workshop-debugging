<?php

namespace App\Services;

use OpenAI\Client;

class ChatGptService
{

    public Client $client;

    public function __construct(
        public string $apiKey = "",
    )
    {
        $this->apiKey = config('openai.api_token');
        $this->client = \OpenAI::client($this->apiKey);
    }

    public function talk(string $prompt): string
    {
        $result = $this->client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $result->choices[0]->message->content;

    }
}
