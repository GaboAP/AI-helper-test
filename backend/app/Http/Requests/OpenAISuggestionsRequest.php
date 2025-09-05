<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpenAISuggestionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $models = array_keys((array) config('openai.models'));

        return [
            'model'  => ['required', 'string', Rule::in($models)],
            'prompt' => ['required', 'string', 'min:2', 'max:200'],
        ];
    }

    public function messages(): array
    {
        $models = implode(', ', array_keys((array) config('openai.models')));
        return [
            'model.required' => 'model is required.',
            'model.string'   => 'model must be a string.',
            'model.in'       => "model must be one of the following: {$models}.",
            'prompt.required'=> 'prompt is required.',
            'prompt.string'  => 'prompt must be a string.',
            'prompt.min'     => 'prompt must be at least 2 characters.',
            'prompt.max'     => 'prompt must be at most 200 characters.',
        ];
    }
}
