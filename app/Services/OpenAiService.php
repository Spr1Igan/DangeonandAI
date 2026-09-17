<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenAiService
{
    public function createResponse(
        array $input,
        string $instructions,
        array $tools = [],
    ): array {
        $key = config('services.openai.key');
        $model = config('services.openai.model');

        if (! is_string($key) || trim($key) === '') {
            throw new RuntimeException('Не указан OPENAI_API_KEY.');
        }

        if (! is_string($model) || trim($model) === '') {
            throw new RuntimeException('Не указана модель OpenAI.');
        }

        $payload = [
            'model' => $model,
            'instructions' => $instructions,
            'input' => $input,
            'max_output_tokens' => 4000,
            'store' => false,
        ];

        if ($tools !== []) {
            $payload['tools'] = $tools;
            $payload['parallel_tool_calls'] = false;
        }

        $response = Http::withToken($key)
            ->acceptJson()
            ->asJson()
            ->connectTimeout(10)
            ->timeout((int) config('services.openai.timeout', 150))
            ->post('https://api.openai.com/v1/responses', $payload);

        $response->throw();

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('OpenAI вернул некорректный ответ.');
        }

        if (($data['status'] ?? null) !== 'completed') {
            throw new RuntimeException(
                'OpenAI не завершил ответ. Статус: '
                . ($data['status'] ?? 'неизвестен')
            );
        }

        return $data;
    }

    public function extractText(array $response): string
    {
        $parts = [];

        foreach ($response['output'] ?? [] as $item) {
            if (($item['type'] ?? null) !== 'message') {
                continue;
            }

            foreach ($item['content'] ?? [] as $content) {
                if (($content['type'] ?? null) === 'output_text') {
                    $parts[] = $content['text'];
                }
            }
        }

        $text = trim(implode("\n\n", $parts));

        if ($text === '') {
            throw new RuntimeException(
                'В ответе OpenAI нет текстового сообщения.'
            );
        }

        return $text;
    }
}