<?php

declare(strict_types=1);

namespace App\Domain\OpenAI;

interface OpenAIClientInterface
{
    /** Returns the provider message content as a JSON string. */
    public function complete(string $model, string $systemRole, string $userPrompt): string;
}
