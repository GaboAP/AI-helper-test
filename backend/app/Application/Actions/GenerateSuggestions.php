<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\OpenAI\OpenAIClientInterface;
use Illuminate\Support\Str;

class GenerateSuggestions
{
    public function __construct(private OpenAIClientInterface $client) {}

    /** @return array<int,string> exactly 3–5 suggestions */
    public function handle(string $model, string $systemRole, string $prompt): array
    {
        // Instruct model: return strict JSON so parsing is deterministic
        $instruction = <<<TXT
        Topic: {$prompt}
        Return a strict JSON object with a single key "suggestions" mapped to an array
        of 3-5 concise, specific story ideas (each 8-18 words). No extra keys, no prose.
        TXT;

        $json = $this->client->complete(
            model: $model,
            systemRole: $systemRole,
            userPrompt: $instruction
        );

        $decoded = json_decode($json, true);
        $list = is_array($decoded) ? ($decoded['suggestions'] ?? []) : [];

        // Normalize: keep non-empty strings only
        $list = array_values(array_filter(
            $list,
            fn($s) => is_string($s) && Str::of($s)->trim()->isNotEmpty()
        ));

        // Guardrails: ensure 3-5
        if (count($list) < 3) {
            $fallbacks = [
                "Explainer: what {$prompt} means for local readers",
                "Interview: expert puts {$prompt} in context",
                "Data snapshot: key numbers behind {$prompt}",
                "Timeline: how we got to the current {$prompt} moment",
                "Voices: lived experiences around {$prompt}",
            ];
            // add until at least 3
            foreach ($fallbacks as $f) {
                if (count($list) >= 3) break;
                $list[] = $f;
            }
        }

        return array_slice($list, 0, 5);
    }
}
