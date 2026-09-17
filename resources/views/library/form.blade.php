@php($editing = isset($version))
<div class="field">
    @if(!$editing)
        <label for="name">Название</label>
        <input id="name" name="name" maxlength="120" value="{{ old('name') }}" required>
    @else
        <p class="hint">Название общее для всех версий: {{ $entry->name }}.</p>
    @endif
</div>
<div class="field">
    <label for="description">{{ $routePrefix === 'race' ? 'Описание и происхождение' : 'Описание и путь класса' }}</label>
    <textarea id="description" name="description" rows="5" maxlength="5000">{{ old('description', $version->description ?? '') }}</textarea>
</div>
<div class="field">
    <label for="rules_text">Способности, особенности и правила</label>
    <textarea id="rules_text" name="rules_text" rows="9" maxlength="10000">{{ old('rules_text', $version->rules_text ?? '') }}</textarea>
    <p class="hint">Опиши возможности, ограничения и условия применения. Для класса можно указать развитие по уровням. Перед утверждением нужны описание и правила.</p>
</div>
<details @if($errors->has('mechanics_json') || $errors->has('metadata_json')) open @endif>
    <summary>Дополнительные структурированные данные</summary>
    <p class="hint">Необязательно. Здесь можно сохранить свои механики в JSON. Их автоматический расчёт пока не подключён.</p>
    @foreach(['mechanics' => 'Механики', 'metadata' => 'Дополнительные сведения'] as $field => $label)
        <div class="field" style="margin-top:16px">
            <label for="{{ $field }}_json">{{ $label }} · JSON</label>
            <textarea id="{{ $field }}_json" name="{{ $field }}_json" rows="5" maxlength="50000" spellcheck="false">{{ old($field.'_json', isset($version) && $version->$field !== null ? json_encode($version->$field, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
            <p class="hint">Объект или массив. Пустое поле очищает эти данные.</p>
        </div>
    @endforeach
</details>
<div class="actions">
    <button type="submit">{{ $editing ? 'Сохранить черновик' : 'Создать черновик' }}</button>
    <a class="button secondary" href="{{ $editing ? route($routePrefix.'.show', $entry) : route($routePrefix.'.index') }}">Отмена</a>
</div>

