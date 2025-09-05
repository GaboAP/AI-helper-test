<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OpenAISuggestionsRequest;
use App\Application\Actions\GenerateSuggestions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SuggestionsController extends Controller
{
    public function __construct(private GenerateSuggestions $action) {}

    public function __invoke(OpenAISuggestionsRequest $request): JsonResponse
    {
        $model  = (string) $request->validated('model');
        $prompt = trim((string) $request->validated('prompt'));

        $systemRole = Cache::rememberForever('openai:system_role', function () {
            $path = storage_path('app/assets/default_role.txt');
            return is_file($path) ? (string) file_get_contents($path) : 'You are a helpful assistant for journalists.';
        });

        $ideas = $this->action->handle(model: $model, systemRole: $systemRole, prompt: $prompt);

        return response()->json([
            'prompt'      => $prompt,
            'suggestions' => $ideas, // array of strings, length 3–5
        ], 200);
    }
}
