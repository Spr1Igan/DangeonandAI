<div class="build-links">
    <a href="{{ route('race.index') }}">Библиотека рас ↗</a>
    <a href="{{ route('class.index') }}">Библиотека классов ↗</a>
</div>
@if($errors->any() && ! $errors->has('content'))
    <div class="error" role="alert">
        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
@endif
@if($character->status === 'draft')
    <details class="saved-section" style="margin-top:18px" @if($errors->any() && ! $errors->has('content')) open @endif>
        <summary>Выбрать расу и классы</summary>
        <p class="section-empty" style="margin-top:12px">Доступны твои утверждённые версии. Сначала создай и утверди правила в библиотеке.</p>
        <form class="build-form" method="POST" action="{{ route('character.race.update', $character) }}">
            @csrf
            @method('PATCH')
            <label for="race-version">Раса и версия</label>
            <select id="race-version" name="race_version_id">
                <option value="">Без расы</option>
                @if($character->raceVersion && $character->raceVersion->race->status === 'archived')
                    <option value="{{ $character->race_version_id }}" selected disabled>{{ $character->raceVersion->race->name }} · в архиве</option>
                @endif
                @foreach($races as $race)
                    <optgroup label="{{ $race->name }}">
                        @foreach($race->versions as $raceVersion)
                            <option value="{{ $raceVersion->id }}" @selected((string) old('race_version_id', $character->race_version_id) === (string) $raceVersion->id)>{{ $race->name }} · версия {{ $raceVersion->version }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <button type="submit">Сохранить расу</button>
        </form>

        @foreach($character->classes as $selection)
            @php($availableClass = $gameClasses->firstWhere('id', $selection->game_class_id))
            <div class="class-selection">
                <p>{{ $selection->classVersion->gameClass->name }} · уровень {{ $selection->level }}</p>
                @if($availableClass)
                    <form class="build-form" method="POST" action="{{ route('character.classes.update', $character) }}">
                        @csrf
                        @method('PUT')
                        <label for="class-version-{{ $selection->id }}">Версия класса</label>
                        <select id="class-version-{{ $selection->id }}" name="class_version_id">
                            @foreach($availableClass->versions as $classVersion)
                                <option value="{{ $classVersion->id }}" @selected($selection->class_version_id === $classVersion->id)>Версия {{ $classVersion->version }}</option>
                            @endforeach
                        </select>
                        <label for="class-level-{{ $selection->id }}">Уровень в этом классе</label>
                        <input id="class-level-{{ $selection->id }}" name="level" type="number" min="1" max="65535" value="{{ $selection->level }}" required>
                        <button type="submit">Обновить класс</button>
                    </form>
                @else
                    <p class="section-empty">Класс в архиве. Выбранная версия сохранена.</p>
                @endif
                <form method="POST" action="{{ route('character.classes.destroy', [$character, $selection]) }}">
                    @csrf
                    @method('DELETE')
                    <button class="build-remove" type="submit">Убрать класс</button>
                </form>
            </div>
        @endforeach

        @php($unusedClasses = $gameClasses->whereNotIn('id', $character->classes->pluck('game_class_id')))
        @if($unusedClasses->isNotEmpty())
            <form class="build-form class-selection" method="POST" action="{{ route('character.classes.update', $character) }}">
                @csrf
                @method('PUT')
                <label for="new-class-version">Добавить класс</label>
                <select id="new-class-version" name="class_version_id" required>
                    <option value="">Выбери класс и версию</option>
                    @foreach($unusedClasses as $gameClass)
                        <optgroup label="{{ $gameClass->name }}">
                            @foreach($gameClass->versions as $classVersion)
                                <option value="{{ $classVersion->id }}" @selected((string) old('class_version_id') === (string) $classVersion->id)>{{ $gameClass->name }} · версия {{ $classVersion->version }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <label for="new-class-level">Уровень в этом классе</label>
                <input id="new-class-level" name="level" type="number" min="1" max="65535" value="{{ old('level', 1) }}" required>
                <button type="submit">Добавить класс</button>
            </form>
        @endif
        <p class="section-empty" style="margin-top:14px">Выбор сохраняет версию правил. Бонусы характеристик, здоровье и способности пока не рассчитываются автоматически.</p>
    </details>
@endif
