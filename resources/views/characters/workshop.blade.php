<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>Мастерская персонажа — DandAI</title>
    <style>
        :root {
            --background: #090d17;
            --surface: #111725;
            --text: #edf0f8;
            --muted: #9da8be;
            --accent: #c8ceff;
            --border: rgba(190, 204, 242, .13);
            --error: #ffb2bd;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background: radial-gradient(ellipse at 70% 0%, #222b494d, transparent 55%), var(--background);
            font-family: "Segoe UI", system-ui, sans-serif;
            line-height: 1.6;
        }
        a { color: inherit; }
        button, textarea { font: inherit; }
        button, a, summary { -webkit-tap-highlight-color: transparent; }
        ::selection { background: #818cba66; }
        :focus-visible { outline: 2px solid var(--accent); outline-offset: 4px; }
        .shell { width: min(1320px, calc(100% - 56px)); margin: 0 auto; }
        .topbar { display: flex; align-items: center; justify-content: space-between; min-height: 78px; gap: 20px; border-bottom: 1px solid var(--border); }
        .brand { display: inline-flex; align-items: center; gap: 12px; text-decoration: none; font-size: 18px; font-weight: 650; letter-spacing: .04em; }
        .brand-mark { width: 27px; height: 27px; border-radius: 50%; background: #d6def3; box-shadow: inset -10px -3px 0 #111725; transform: rotate(-25deg); }
        .back { color: var(--muted); font-size: 13px; text-decoration: none; }
        .back:hover { color: var(--text); }
        .page-heading { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 25px 0 23px; }
        .eyebrow { margin: 0 0 5px; color: var(--accent); font-size: 10px; letter-spacing: .2em; text-transform: uppercase; }
        h1 { margin: 0; font-family: Georgia, "Times New Roman", serif; font-size: clamp(27px, 3vw, 36px); font-weight: 400; letter-spacing: -.03em; line-height: 1.2; }
        .heading-note { max-width: 260px; margin: 0; color: var(--muted); font-size: 12px; text-align: right; }
        .notice { margin: 0 0 18px; padding: 12px 16px; border: 1px solid #b5dccc30; border-radius: 11px; color: #c3e1d4; background: #a2cdbd0a; font-size: 13px; overflow-wrap: anywhere; }
        .workspace { display: grid; grid-template-columns: 300px minmax(0, 1fr); gap: 24px; align-items: start; padding-bottom: 28px; }
        .workspace > * { min-width: 0; }
        .panel { border: 1px solid var(--border); border-radius: 20px; background: linear-gradient(150deg, #151d2d, var(--surface)); box-shadow: 0 20px 55px #00000020; }
        .summary-panel { max-height: max(500px, calc(100dvh - 215px)); overflow: auto; scrollbar-width: thin; scrollbar-color: #42506d transparent; }
        .summary-toggle { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 17px 22px; cursor: pointer; list-style: none; color: #dce3f5; font-size: 13px; font-weight: 600; }
        .summary-toggle::-webkit-details-marker { display: none; }
        .summary-toggle::after { content: "+"; color: var(--muted); font-size: 18px; font-weight: 400; }
        .summary-panel[open] > .summary-toggle::after { content: "−"; }
        .summary-caption { color: var(--muted); font-size: 10px; font-weight: 400; letter-spacing: .06em; }
        .identity { position: relative; padding: 24px 22px 22px; overflow: hidden; border-top: 1px solid var(--border); background: radial-gradient(ellipse at 90% 10%, #7a90c522, transparent 70%); }
        .identity::before { content: ""; position: absolute; top: -39px; right: -34px; width: 145px; height: 145px; border: 1px solid #c8ceff10; border-radius: 50%; pointer-events: none; }
        .identity-top { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 17px; }
        .portrait { display: grid; place-items: center; width: 56px; height: 56px; flex-shrink: 0; border: 1px solid #bdc9ee33; border-radius: 16px; color: #d6def7; background: linear-gradient(140deg, #8a9dcd24, #c8ceff05); font-family: Georgia, "Times New Roman", serif; font-size: 29px; }
        .badge { padding: 4px 9px; border: 1px solid #b9c6f030; border-radius: 6px; color: #c8d3ec; background: #b9c6f009; font-size: 10px; }
        .badge--active { color: #b9d9ca; border-color: #b5dccc30; }
        .badge--archived { color: #aab3c4; }
        .character-name { margin: 0 0 6px; font-family: Georgia, "Times New Roman", serif; font-size: 26px; font-weight: 400; letter-spacing: -.02em; line-height: 1.25; overflow-wrap: anywhere; }
        .character-number { margin: 0; color: var(--muted); font-size: 10px; letter-spacing: .1em; }
        .summary-section { padding: 20px 22px; border-top: 1px solid var(--border); }
        .section-label { margin: 0 0 14px; color: #aebbd5; font-size: 10px; font-weight: 600; letter-spacing: .13em; text-transform: uppercase; }
        .facts { display: grid; gap: 12px; margin: 0; }
        .facts > div { display: flex; justify-content: space-between; align-items: baseline; gap: 18px; }
        .facts dt { color: var(--muted); font-size: 12px; }
        .facts dd { max-width: 65%; margin: 0; color: #dce3f5; font-size: 12px; text-align: right; overflow-wrap: anywhere; }
        .unfilled { color: var(--muted) !important; font-style: italic; }
        .ability-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 7px; margin: 0; }
        .ability { min-width: 0; padding: 10px 5px 8px; border: 1px solid var(--border); border-radius: 9px; background: #090f1b66; text-align: center; }
        .ability dt { color: var(--muted); font-size: 9px; overflow-wrap: anywhere; }
        .ability dd { margin: 3px 0 0; color: #dce3f5; font-size: 19px; font-family: Georgia, "Times New Roman", serif; overflow-wrap: anywhere; }
        .health { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 15px; font-size: 12px; }
        .health-label { color: var(--muted); }
        .health-value { color: #d9c1cf; font-variant-numeric: tabular-nums; }
        .health-value small { color: var(--muted); font-size: 11px; }
        .section-empty { margin: 0; color: var(--muted); font-size: 12px; line-height: 1.75; }
        .saved-section > summary { display: flex; align-items: center; justify-content: space-between; gap: 10px; cursor: pointer; list-style: none; color: #dce3f5; font-size: 12px; }
        .saved-section > summary::-webkit-details-marker { display: none; }
        .saved-section > summary::after { content: "+"; color: var(--muted); font-size: 17px; }
        .saved-section[open] > summary::after { content: "−"; }
        .saved-section .section-empty, .saved-section .saved-data, .backstory { margin-top: 13px; }
        .saved-indicator { margin-left: auto; color: #bdcba7; font-size: 10px; }
        .saved-indicator.is-empty { color: var(--muted); }
        .backstory { margin-bottom: 0; color: #b8c3d9; font-size: 12px; line-height: 1.8; white-space: pre-wrap; overflow-wrap: anywhere; }
        .saved-data { display: grid; gap: 12px; margin-bottom: 0; }
        .saved-data dt { color: #d1daed; font-size: 11px; overflow-wrap: anywhere; }
        .saved-data dd { margin: 3px 0 0; color: var(--muted); font-size: 12px; white-space: pre-wrap; overflow-wrap: anywhere; }
        .summary-footnote { margin: 0; padding: 17px 22px; border-top: 1px solid var(--border); color: var(--muted); font-size: 10px; line-height: 1.8; }
        .chat-panel { display: flex; flex-direction: column; height: max(500px, calc(100dvh - 215px)); max-height: 1100px; overflow: hidden; background: linear-gradient(160deg, #121a2a, #0e1522 70%); }
        .chat-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px 15px; padding: 21px 26px 18px; border-bottom: 1px solid var(--border); }
        .chat-heading { display: flex; align-items: center; gap: 12px; }
        .chat-symbol { display: grid; place-items: center; width: 36px; height: 36px; border: 1px solid #c8ceff26; border-radius: 50%; color: var(--accent); background: #c8ceff08; font-size: 22px; }
        .chat-heading h2 { margin: 0; font-size: 15px; font-weight: 600; }
        .chat-heading p { margin: 1px 0 0; color: var(--muted); font-size: 11px; }
        .connection-state { padding: 4px 9px; border: 1px solid #c8ceff21; border-radius: 20px; color: #aeb9cf; font-size: 10px; }
        .chat-scroll { flex: 1; min-height: 0; overflow-y: auto; padding: 25px 28px; scrollbar-width: thin; scrollbar-color: #42506d transparent; overscroll-behavior: contain; }
        .empty-chat { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100%; padding: 15px 0; text-align: center; }
        .moon-orbit { position: relative; display: grid; place-items: center; width: 106px; height: 106px; margin-bottom: 21px; border: 1px solid #c8ceff1c; border-radius: 50%; }
        .moon-orbit::before { content: ""; position: absolute; inset: 9px; border: 1px dashed #c8ceff19; border-radius: 50%; }
        .moon-orbit::after { content: "✦"; position: absolute; top: 4px; right: 9px; color: var(--accent); font-size: 13px; }
        .moon { width: 57px; height: 57px; border-radius: 50%; background: radial-gradient(ellipse at 33% 30%, #65738b40 0 10%, transparent 12%), radial-gradient(ellipse at 64% 67%, #65738b30 0 13%, transparent 15%), linear-gradient(135deg, #eef0e9, #b5c4dd 58%, #6e819e); box-shadow: inset -9px -5px 15px #22334c70, 0 0 40px #b4caff26; }
        .empty-chat h3 { margin: 0 0 12px; font-family: Georgia, "Times New Roman", serif; font-size: clamp(25px, 3vw, 32px); font-weight: 400; letter-spacing: -.025em; line-height: 1.2; }
        .empty-chat p { max-width: 430px; margin: 0; color: var(--muted); font-size: 13px; line-height: 1.8; }
        .prompt-options { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 22px; }
        .prompt-option { padding: 8px 12px; border: 1px solid #c8ceff26; border-radius: 9px; background: #c8ceff04; color: #c7d2e9; font-size: 11px; cursor: pointer; transition: background .2s, border-color .2s; }
        .prompt-option:hover { background: #c8ceff0e; border-color: #c8ceff60; }
        .message { display: flex; align-items: flex-start; gap: 11px; max-width: 90%; margin-bottom: 23px; }
        .message:last-child { margin-bottom: 0; }
        .message--user { margin-left: auto; flex-direction: row-reverse; }
        .message-avatar { display: grid; place-items: center; width: 29px; height: 29px; flex: 0 0 29px; margin-top: 3px; border: 1px solid #c8ceff22; border-radius: 9px; color: #bfcce7; background: #c8ceff08; font-size: 11px; }
        .message--assistant .message-avatar { color: var(--accent); font-size: 18px; }
        .message-body { min-width: 0; }
        .message-meta { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin: 0 2px 7px; color: var(--muted); font-size: 10px; }
        .message-meta strong { color: #c9d4e9; font-weight: 500; }
        .message--user .message-meta { justify-content: flex-end; }
        .message-meta time { color: var(--muted); font-size: 9px; }
        .message-text { margin: 0; padding: 14px 17px; border: 1px solid #b9c6f019; border-radius: 3px 15px 15px 15px; background: #161f31; color: #d9e1f2; font-size: 13px; line-height: 1.85; white-space: pre-wrap; overflow-wrap: anywhere; }
        .message--user .message-text { border-color: #c8ceff28; border-radius: 15px 3px 15px 15px; background: linear-gradient(125deg, #26324b, #202a40); }
        .composer { flex-shrink: 0; padding: 18px 24px 17px; border-top: 1px solid var(--border); background: #101726; }
        .composer-label { display: block; margin-bottom: 9px; color: #b9c5db; font-size: 11px; }
        .input-shell { padding: 12px 14px 10px; border: 1px solid #36415a; border-radius: 13px; background: #0b111d; transition: border-color .2s, box-shadow .2s; }
        .input-shell:focus-within { border-color: #a7b2e0; box-shadow: 0 0 0 3px #a7b2e014; }
        .input-shell.has-error { border-color: var(--error); }
        textarea { display: block; width: 100%; min-height: 66px; max-height: 170px; padding: 0; border: 0; outline: none; resize: vertical; color: var(--text); background: transparent; font-size: 13px; line-height: 1.75; }
        textarea::placeholder { color: #828fa8; }
        .composer-actions { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-top: 9px; }
        .input-hint { color: var(--muted); font-size: 10px; }
        .input-hint span { white-space: nowrap; }
        .input-hint .shortcut { display: block; margin-top: 3px; font-size: 9px; color: #8795af; }
        .send-button { display: inline-flex; align-items: center; justify-content: center; gap: 10px; min-height: 40px; padding: 9px 16px; border: 1px solid #e2e5ff; border-radius: 9px; color: #172139; background: linear-gradient(135deg, #e0e4ff, #b7c3e9); font-size: 12px; font-weight: 650; cursor: pointer; transition: filter .2s; }
        .send-button:hover:not(:disabled) { filter: brightness(1.08); }
        .send-button:disabled { opacity: .45; cursor: not-allowed; }
        .composer-note { margin: 10px 1px 0; color: var(--muted); font-size: 10px; line-height: 1.7; }
        .error { margin: 9px 0 0; color: var(--error); font-size: 12px; }
        .build-form { display: grid; gap: 9px; margin-top: 15px; }
        .build-form label { color: var(--muted); font-size: 11px; }
        .build-form select, .build-form input { width: 100%; min-width: 0; padding: 9px; border: 1px solid #3b4662; border-radius: 8px; background: #0b111d; color: var(--text); font: inherit; font-size: 12px; }
        .build-form button, .build-remove { padding: 8px 11px; border: 1px solid #c8ceff30; border-radius: 8px; background: #c8ceff0c; color: var(--accent); font: inherit; font-size: 11px; cursor: pointer; }
        .build-form button:disabled { opacity: .45; cursor: not-allowed; }
        .build-links { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; font-size: 11px; color: var(--accent); }
        .class-selection { margin-top: 15px; padding-top: 14px; border-top: 1px solid var(--border); }
        .class-selection p { font-size: 12px; overflow-wrap: anywhere; }
        .build-remove { margin-top: 8px; color: #dfbac9; }
        @media (min-width: 761px) and (max-height: 800px) {
            .chat-scroll { padding-top: 16px; padding-bottom: 16px; }
            .empty-chat { padding: 0; }
            .moon-orbit { display: none; }
            .empty-chat h3 { font-size: 26px; margin-bottom: 8px; }
            .empty-chat p { font-size: 12px; }
            .prompt-options { margin-top: 14px; }
        }
        @media (max-width: 1000px) {
            .workspace { grid-template-columns: 265px minmax(0, 1fr); gap: 18px; }
            .shell { width: calc(100% - 36px); }
            .chat-header, .chat-scroll { padding-left: 20px; padding-right: 20px; }
            .composer { padding-left: 18px; padding-right: 18px; }
            .message { max-width: 100%; }
        }
        @media (max-width: 760px) {
            .workspace { grid-template-columns: 1fr; gap: 16px; }
            .summary-panel { max-height: none; }
            .summary-panel[open] { max-height: 65dvh; }
            .chat-panel { height: 76dvh; min-height: 560px; max-height: none; }
            .heading-note { display: none; }
            .page-heading { padding: 22px 0; }
            .summary-caption { margin-left: auto; }
            .chat-scroll { padding: 22px 16px; }
            .identity-top { justify-content: flex-start; }
            .ability-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (max-width: 420px) {
            .shell { width: calc(100% - 24px); }
            .topbar { min-height: 68px; gap: 10px; }
            .brand { font-size: 16px; }
            .back { font-size: 11px; }
            .chat-header { padding: 16px; }
            .message { gap: 7px; }
            .message-avatar { display: none; }
            .message-text { padding: 12px 14px; }
            .composer { padding: 15px 13px; }
            .input-hint .shortcut { display: none; }
            .prompt-option { padding: 8px 10px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition: none !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body>
    @php
        $characterName = trim((string) $character->name);
        $displayName = $characterName !== '' ? $characterName : 'Безымянный герой';
        $description = trim((string) $character->description);
        $status = match ($character->status) {
            'active' => ['label' => 'Активен', 'class' => 'badge--active'],
            'archived' => ['label' => 'В архиве', 'class' => 'badge--archived'],
            default => ['label' => 'Черновик', 'class' => ''],
        };
        $systemLabel = match ($character->system) {
            'dnd5e' => 'D&D 5e',
            'custom' => 'Свои правила',
            default => $character->system ?: 'Не выбрана',
        };
        $fieldLabels = [
            'strength' => 'Сила', 'str' => 'Сила', 'dexterity' => 'Ловкость', 'dex' => 'Ловкость',
            'constitution' => 'Телосложение', 'con' => 'Телосложение', 'intelligence' => 'Интеллект', 'int' => 'Интеллект',
            'wisdom' => 'Мудрость', 'wis' => 'Мудрость', 'charisma' => 'Харизма', 'cha' => 'Харизма',
            'cp' => 'Медные', 'sp' => 'Серебряные', 'ep' => 'Электрумовые', 'gp' => 'Золотые', 'pp' => 'Платиновые',
            'name' => 'Название', 'description' => 'Описание', 'quantity' => 'Количество',
            'current' => 'Сейчас', 'max' => 'Максимум', 'type' => 'Тип', 'value' => 'Значение', 'score' => 'Значение',
        ];
        $fieldLabel = static fn ($key) => $fieldLabels[strtolower((string) $key)] ?? str_replace('_', ' ', (string) $key);
        $formatValue = static function ($value) use (&$formatValue, $fieldLabel): string {
            if ($value === null) { return 'Не указано'; }
            if (is_bool($value)) { return $value ? 'Да' : 'Нет'; }
            if (! is_array($value)) { return (string) $value; }
            $parts = [];
            foreach ($value as $key => $item) {
                $parts[] = (is_int($key) ? '' : $fieldLabel($key).': ').$formatValue($item);
            }
            return $parts === [] ? 'Не задано' : implode(' · ', $parts);
        };
        $abilityScores = is_array($character->ability_scores) ? $character->ability_scores : [];
        if ($abilityScores === [] && $character->system === 'dnd5e') {
            $abilityScores = array_fill_keys(['strength', 'dexterity', 'constitution', 'intelligence', 'wisdom', 'charisma'], null);
        }
        $savedSections = [
            ['label' => 'Предметы', 'data' => $character->items, 'empty' => 'Снаряжение ещё не добавлено.'],
            ['label' => 'Валюта', 'data' => $character->currency, 'empty' => 'Начальные средства ещё не заданы.'],
            ['label' => 'Ресурсы', 'data' => $character->resources, 'empty' => 'Ресурсы и их запасы ещё не заданы.'],
            ['label' => 'Дополнительно', 'data' => $character->metadata, 'empty' => 'Дополнительных сведений пока нет.'],
        ];
    @endphp

    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('home') }}"><span class="brand-mark" aria-hidden="true"></span>DandAI</a>
            <a class="back" href="{{ route('character.index') }}"><span aria-hidden="true">←</span> Мои персонажи</a>
        </header>
        <main>
            <div class="page-heading">
                <div>
                    <p class="eyebrow">От замысла к легенде</p>
                    <h1>Мастерская персонажа</h1>
                </div>
                <p class="heading-note">Дай идее форму.<br>История начинается с разговора.</p>
            </div>

            @if (session('success'))
                <div class="notice" role="status">{{ session('success') }}</div>
            @endif

            <div class="workspace">
                <aside aria-label="Сохранённые данные персонажа">
                    <details class="panel summary-panel" id="character-summary" open>
                        <summary class="summary-toggle"><span>Лист персонажа</span><span class="summary-caption">Сводка</span></summary>
                        <div class="identity">
                            <div class="identity-top">
                                <span class="portrait" aria-hidden="true">{{ $characterName !== '' ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($characterName, 0, 1)) : '✧' }}</span>
                                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </div>
                            <h2 class="character-name">{{ $displayName }}</h2>
                            <p class="character-number">ПЕРСОНАЖ · {{ str_pad((string) $character->id, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        <section class="summary-section" aria-labelledby="identity-title">
                            <h3 class="section-label" id="identity-title">Происхождение и путь</h3>
                            <dl class="facts">
                                <div><dt>Система</dt><dd>{{ $systemLabel }}</dd></div>
                                @if ($character->ruleset_version)
                                    <div><dt>Правила</dt><dd>{{ $character->ruleset_version }}</dd></div>
                                @endif
                                <div>
                                    <dt>Раса</dt>

                                    <dd @class(['unfilled' => $character->raceVersion === null])>
                                        @if ($character->raceVersion)
                                            {{ $character->raceVersion->race->name }}
                                            · версия {{ $character->raceVersion->version }}
                                        @else
                                            Не определена
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt>Классы</dt>
                                    <dd @class(['unfilled' => $character->classes->isEmpty()])>
                                        @forelse($character->classes as $selection)
                                            <div>{{ $selection->classVersion->gameClass->name }} · ур. {{ $selection->level }} · в. {{ $selection->classVersion->version }}</div>
                                        @empty
                                            Не определены
                                        @endforelse
                                    </dd>
                                </div>
                            </dl>
                            @include('characters.partials.build')
                        </section>

                        <section class="summary-section" aria-labelledby="abilities-title">
                            <h3 class="section-label" id="abilities-title">Характеристики</h3>
                            @if ($abilityScores !== [])
                                <dl class="ability-grid">
                                    @foreach ($abilityScores as $ability => $score)
                                        <div class="ability">
                                            <dt>{{ $fieldLabel($ability) }}</dt>
                                            <dd>{{ $score === null ? '—' : $formatValue($score) }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @else
                                <p class="section-empty">Характеристики ещё не заданы.</p>
                            @endif
                            <div class="health">
                                <span class="health-label">Здоровье</span>
                                @if ($character->hp_current !== null || $character->hp_max !== null)
                                    <span class="health-value">{{ $character->hp_current ?? '—' }} <small>/ {{ $character->hp_max ?? '—' }} HP</small></span>
                                @else
                                    <span class="unfilled">Не задано</span>
                                @endif
                            </div>
                        </section>

                        <details class="summary-section saved-section" @if ($description !== '') open @endif>
                            <summary>Предыстория <span class="saved-indicator {{ $description === '' ? 'is-empty' : '' }}">{{ $description !== '' ? 'Добавлена' : 'Пока пусто' }}</span></summary>
                            @if ($description !== '')
                                <p class="backstory">{{ $description }}</p>
                            @else
                                <p class="section-empty">Первые строки о герое ещё впереди.</p>
                            @endif
                        </details>

                        @foreach ($savedSections as $section)
                            @php
                                $hasData = $section['data'] !== null && $section['data'] !== [];
                            @endphp
                            <details class="summary-section saved-section" @if ($hasData) open @endif>
                                <summary>{{ $section['label'] }} <span class="saved-indicator {{ $hasData ? '' : 'is-empty' }}">{{ $hasData ? 'Добавлено' : 'Пока пусто' }}</span></summary>
                                @if ($hasData)
                                    <dl class="saved-data">
                                        @foreach (is_array($section['data']) ? $section['data'] : [$section['data']] as $key => $value)
                                            <div>
                                                <dt>{{ is_int($key) ? 'Запись '.($key + 1) : $fieldLabel($key) }}</dt>
                                                <dd>{{ $formatValue($value) }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                @else
                                    <p class="section-empty">{{ $section['empty'] }}</p>
                                @endif
                            </details>
                        @endforeach
                        <p class="summary-footnote">Здесь отображаются сохранённые данные. Идеи из переписки пока не меняют лист персонажа.</p>
                    </details>
                </aside>

                <section class="panel chat-panel" aria-labelledby="chat-title">
                    <header class="chat-header">
                        <div class="chat-heading">
                            <span class="chat-symbol" aria-hidden="true">✧</span>
                            <div><h2 id="chat-title">Обсуждение персонажа</h2><p>Пространство для твоих идей</p></div>
                        </div>
                        <span class="connection-state">ИИ ещё не подключён</span>
                    </header>

                    <div class="chat-scroll" id="chat-history" tabindex="0" role="region" aria-label="История переписки">
                        @forelse ($messages as $chatMessage)
                            <article class="message {{ $chatMessage->role === 'user' ? 'message--user' : 'message--assistant' }}">
                                <span class="message-avatar" aria-hidden="true">{{ $chatMessage->role === 'user' ? 'Ты' : '✧' }}</span>
                                <div class="message-body">
                                    <div class="message-meta">
                                        <strong>{{ $chatMessage->role === 'user' ? 'Ты' : 'Помощник мастерской' }}</strong>
                                        @if ($chatMessage->created_at)
                                            <time datetime="{{ $chatMessage->created_at->toIso8601String() }}">{{ $chatMessage->created_at->format('d.m · H:i') }}</time>
                                        @endif
                                    </div>
                                    <p class="message-text">{{ $chatMessage->content }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="empty-chat">
                                <div class="moon-orbit" aria-hidden="true"><div class="moon"></div></div>
                                <h3>Кем станет твой герой?</h3>
                                <p>Начни с образа, необычной способности или истории,<br>которую хочется прожить. Запиши первую мысль.</p>
                                <div class="prompt-options" aria-label="Идеи для начала сообщения">
                                    <button class="prompt-option" type="button" data-prompt="Я представляю своего персонажа так: ">Образ героя</button>
                                    <button class="prompt-option" type="button" data-prompt="Хочу придумать для персонажа особую способность: ">Особая способность</button>
                                    <button class="prompt-option" type="button" data-prompt="В прошлом моего персонажа произошло следующее: ">История из прошлого</button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <form class="composer" id="message-form" method="POST" action="{{ route('character.messages.store', $character) }}">
                        @csrf
                        <label class="composer-label" for="content">Твоё сообщение</label>
                        <div class="input-shell {{ $errors->has('content') ? 'has-error' : '' }}">
                            <textarea id="content" name="content" rows="2" maxlength="5000" required aria-describedby="content-error composer-note" aria-invalid="{{ $errors->has('content') ? 'true' : 'false' }}" placeholder="Расскажи о герое, его мире или своей идее…">{{ old('content') }}</textarea>
                            <div class="composer-actions">
                                <div class="input-hint"><span id="character-count">0 / 5000</span><span class="shortcut">Ctrl / ⌘ + Enter — отправить</span></div>
                                <button class="send-button" id="send-button" type="submit"><span id="send-label">Отправить</span><span aria-hidden="true">↑</span></button>
                            </div>
                        </div>
                        <div id="content-error">
                            @error('content')
                                <p class="error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="composer-note" id="composer-note">Сообщения сохраняются. Ответы помощника появятся после подключения ИИ.</p>
                    </form>
                </section>
            </div>
        </main>
    </div>
    <script>
        const composer = document.getElementById('message-form');
        const messageInput = document.getElementById('content');
        const sendButton = document.getElementById('send-button');
        const sendLabel = document.getElementById('send-label');
        const chatHistory = document.getElementById('chat-history');
        const summary = document.getElementById('character-summary');
        const mobileLayout = window.matchMedia('(max-width: 760px)');
        let isSubmitting = false;

        function updateComposer() {
            document.getElementById('character-count').textContent = `${messageInput.value.length} / 5000`;
            sendButton.disabled = isSubmitting || messageInput.value.trim() === '';
        }

        function updateSummary() {
            summary.open = !mobileLayout.matches || summary.querySelector('[role="alert"]') !== null;
        }

        messageInput.addEventListener('input', updateComposer);
        messageInput.addEventListener('keydown', event => {
            if (event.key === 'Enter' && (event.ctrlKey || event.metaKey) && !event.isComposing) {
                event.preventDefault();
                if (!sendButton.disabled) { composer.requestSubmit(); }
            }
        });

        document.querySelectorAll('[data-prompt]').forEach(button => {
            button.addEventListener('click', () => {
                const existing = messageInput.value.trim();
                const next = existing ? `${existing}\n\n${button.dataset.prompt}` : button.dataset.prompt;
                if (next.length <= messageInput.maxLength) {
                    messageInput.value = next;
                    updateComposer();
                }
                messageInput.focus();
            });
        });

        composer.addEventListener('submit', event => {
            if (isSubmitting || messageInput.value.trim() === '') {
                event.preventDefault();
                return;
            }
            isSubmitting = true;
            sendLabel.textContent = 'Сохранение…';
            updateComposer();
        });

        window.addEventListener('pageshow', () => {
            isSubmitting = false;
            sendLabel.textContent = 'Отправить';
            updateComposer();
        });

        mobileLayout.addEventListener('change', updateSummary);
        updateSummary();
        updateComposer();
        if (chatHistory.querySelector('.message')) {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }
    </script>
</body>
</html>
