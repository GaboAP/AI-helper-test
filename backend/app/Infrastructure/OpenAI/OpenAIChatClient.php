<?php

declare(strict_types=1);

namespace App\Infrastructure\OpenAI;

use App\Domain\OpenAI\OpenAIClientInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OpenAIChatClient implements OpenAIClientInterface
{
    public function complete(string $model, string $systemRole, string $userPrompt): string
    {
        $cfg = (array) config('openai');
        $apiKey  = (string) ($cfg['api_key'] ?? '');
        $baseUrl = (string) ($cfg['base_url'] ?? 'https://api.openai.com/v1');
        $timeout = (int) ($cfg['defaults']['timeout_seconds'] ?? 20);

        // If no API key, do a static stub so the endpoint still works
        if ($apiKey === '') {
            return json_encode([
                'suggestions' => [
                    "Stub: explainer on {$userPrompt}",
                    "Stub: interview with local voices about {$userPrompt}",
                    "Stub: 5 data points to watch in {$userPrompt}",
                ],
            ], JSON_UNESCAPED_UNICODE);
        }

        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemRole],
                ['role' => 'user',   'content' => $userPrompt],
            ],
            'response_format' => ['type' => 'json_object'],
        ];

        try {
            $resp = Http::withToken($apiKey)
                ->timeout($timeout)
                ->baseUrl($baseUrl)
                ->acceptJson()
                ->post('/chat/completions', $payload)
                ->throw();

            // OpenAI-style response: choices[0].message.content
            $content = $resp->json('choices.0.message.content', '');
            return is_string($content) ? $content : json_encode(['suggestions' => []]);
        } catch (RequestException $e) {
            // Map common upstream failures to a deterministic JSON (so FE isn’t surprised)
            $status = $e->response?->status() ?? 500;
            $msg = $e->response?->json('error.message') ?? $e->getMessage();

            // Return empty suggestions but the caller action will fill with fallbacks
            return json_encode([
                'error' => ['status' => $status, 'message' => $msg],
                'suggestions' => [],
            ]);
        }
    }
}
