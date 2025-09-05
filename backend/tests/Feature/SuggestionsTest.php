<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SuggestionsTest extends TestCase
{
    /** @test */
    public function it_validates_input(): void
    {
        $res = $this->postJson('/api/suggestions', []);
        $res->assertStatus(422);
        $res->assertJsonValidationErrors(['model', 'prompt']);
    }

    /** @test */
    public function it_returns_3_to_5_suggestions_from_openai(): void
    {
        // Fake upstream
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => [
                        'role' => 'assistant',
                        'content' => json_encode([
                            'suggestions' => [
                                'Explainer on local election rules',
                                'Interview: first-time voters share concerns',
                                'Data: turnout trends over the decade',
                                'Follow the money: campaign finance map',
                            ],
                        ]),
                    ],
                ]],
            ], 200),
        ]);

        $payload = [
            'model'  => 'gpt-4o-mini',
            'prompt' => 'local election'
        ];

        $res = $this->postJson('/api/suggestions', $payload);
        $res->assertOk()
            ->assertJsonCount(4, 'suggestions')
            ->assertJsonPath('prompt', 'local election');
    }
}
