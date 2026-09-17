<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use JsonException;

class LibraryEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => [$this->isMethod('POST') ? 'required' : 'sometimes', 'string', 'max:120'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'rules_text' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'mechanics_json' => ['sometimes', 'nullable', 'string', 'max:50000'],
            'metadata_json' => ['sometimes', 'nullable', 'string', 'max:50000'],
        ];
    }

    public function entryData(): array
    {
        $data = $this->safe()->only(['name', 'description', 'rules_text']);

        foreach (['mechanics', 'metadata'] as $field) {
            $key = $field.'_json';
            if (! $this->exists($key)) {
                continue;
            }
            $text = $this->validated($key);
            if ($text === null || trim($text) === '') {
                $data[$field] = null;

                continue;
            }

            try {
                $decoded = json_decode($text, true, 64, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                throw ValidationException::withMessages([$key => 'Проверь синтаксис JSON.']);
            }

            if (! is_array($decoded)) {
                throw ValidationException::withMessages([
                    $key => 'Нужен JSON-объект или массив. Для очистки оставь поле пустым.',
                ]);
            }
            $data[$field] = $decoded;
        }

        return $data;
    }
}
