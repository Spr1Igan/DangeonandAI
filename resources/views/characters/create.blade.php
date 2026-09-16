<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>Новый персонаж — DandAI</title>

    <style>
        :root {
            --background: #090d17;
            --surface: #111725;
            --field: #0b111d;
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
            background:
                radial-gradient(ellipse at 85% 10%, #222b493d, transparent 50%),
                radial-gradient(ellipse at 0% 90%, #28315326, transparent 45%),
                var(--background);
            font-family: "Segoe UI", system-ui, sans-serif;
            line-height: 1.6;
        }

        a { color: inherit; }
        button, input, textarea { font: inherit; }
        button, a, input, textarea { -webkit-tap-highlight-color: transparent; }
        ::selection { background: #818cba66; }

        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 5px;
        }

        .shell {
            width: min(1120px, calc(100% - 48px));
            margin: 0 auto;
        }

        .topbar {
            min-height: 94px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-size: 18px;
            font-weight: 650;
            letter-spacing: .04em;
        }

        .brand-mark {
            width: 29px;
            height: 29px;
            border-radius: 50%;
            background: #d6def3;
            box-shadow: inset -10px -3px 0 #111725;
            transform: rotate(-25deg);
        }

        .back {
            color: var(--muted);
            font-size: 14px;
            text-decoration: none;
            transition: color .2s;
        }

        .back:hover { color: var(--text); }

        .hero {
            margin: 58px 0 32px;
            max-width: 690px;
        }

        .eyebrow {
            margin: 0 0 14px;
            color: var(--accent);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .22em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0 0 16px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(36px, 5.5vw, 58px);
            font-weight: 400;
            letter-spacing: -.04em;
            line-height: 1.12;
        }

        .hero p:last-child {
            margin: 0;
            max-width: 570px;
            color: var(--muted);
            font-size: 16px;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(0, 1fr);
            gap: 26px;
            align-items: start;
            padding-bottom: 56px;
        }

        .panel {
            min-width: 0;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: linear-gradient(150deg, #151d2d, var(--surface));
            box-shadow: 0 24px 70px #00000024;
        }

        .form-panel { padding: 32px; }

        .section-title {
            margin: 0;
            font-size: 19px;
            font-weight: 600;
            letter-spacing: -.02em;
        }

        .section-description {
            margin: 7px 0 28px;
            color: var(--muted);
            font-size: 14px;
        }

        .field + .field { margin-top: 25px; }

        .label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 9px;
        }

        label { font-size: 14px; font-weight: 600; }

        .optional, .counter {
            color: var(--muted);
            font-size: 12px;
            font-weight: 400;
        }

        input, textarea {
            display: block;
            width: 100%;
            border: 1px solid #323c51;
            border-radius: 12px;
            padding: 13px 15px;
            color: var(--text);
            background: var(--field);
            transition: border-color .2s, box-shadow .2s;
        }

        textarea {
            min-height: 190px;
            resize: vertical;
            line-height: 1.75;
        }

        input::placeholder, textarea::placeholder { color: #7f8ba3; }

        input:focus, textarea:focus {
            outline: none;
            border-color: #a7b2e0;
            box-shadow: 0 0 0 3px #a7b2e014;
        }

        [aria-invalid="true"] { border-color: var(--error); }

        .field-footer {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-top: 8px;
        }

        .hint {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
        }

        .counter { white-space: nowrap; }

        .error {
            margin: 8px 0 0;
            color: var(--error);
            font-size: 13px;
        }

        .inspiration { margin-top: 24px; }

        .inspiration-label {
            margin: 0 0 10px;
            color: var(--muted);
            font-size: 12px;
        }

        .ideas {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .idea {
            padding: 7px 11px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #ffffff03;
            color: #c5cee2;
            font-size: 12px;
            cursor: pointer;
            transition: background .2s, border-color .2s;
        }

        .idea:hover {
            background: #c8ceff0c;
            border-color: #c8ceff55;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 32px;
            padding-top: 25px;
            border-top: 1px solid var(--border);
        }

        .primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            min-height: 48px;
            padding: 12px 20px;
            border: 1px solid #e2e5ff;
            border-radius: 11px;
            background: linear-gradient(135deg, #e0e4ff, #b7c3e9);
            color: #172139;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 5px 22px #bac8ef14;
            transition: filter .2s, transform .2s;
        }

        .primary:hover:not(:disabled) {
            filter: brightness(1.07);
            transform: translateY(-1px);
        }

        .primary:disabled { opacity: .5; cursor: not-allowed; }

        .cancel {
            color: var(--muted);
            font-size: 14px;
            text-decoration: none;
        }

        .cancel:hover { color: var(--text); }

        .preview { overflow: hidden; }

        .sky {
            position: relative;
            height: 275px;
            display: grid;
            place-items: center;
            overflow: hidden;
            border-bottom: 1px solid var(--border);
            background:
                radial-gradient(circle at 21% 27%, #c7d6f0 0 1px, transparent 2px),
                radial-gradient(circle at 78% 20%, #c7d6f0 0 1px, transparent 2px),
                radial-gradient(circle at 86% 66%, #aebcdd 0 1px, transparent 2px),
                radial-gradient(circle at 14% 73%, #aebcdd 0 1px, transparent 2px),
                radial-gradient(ellipse at center, #33446666, transparent 70%),
                #101726;
        }

        .orbit {
            position: absolute;
            width: 245px;
            height: 245px;
            border: 1px solid #d0ddff14;
            border-radius: 50%;
        }

        .orbit::after {
            content: "";
            position: absolute;
            inset: 18px;
            border: 1px dashed #d0ddff16;
            border-radius: 50%;
        }

        .moon {
            width: 146px;
            height: 146px;
            border-radius: 50%;
            background:
                radial-gradient(ellipse at 33% 30%, #65738b40 0 10%, transparent 12%),
                radial-gradient(ellipse at 64% 67%, #65738b30 0 13%, transparent 15%),
                radial-gradient(circle at 29% 68%, #65738b33 0 6%, transparent 8%),
                radial-gradient(circle at 62% 29%, #ffffff55 0 4%, transparent 6%),
                linear-gradient(135deg, #eef0e9, #b5c4dd 58%, #6e819e);
            box-shadow:
                inset -21px -12px 25px #22334c70,
                inset 3px 3px 10px #ffffff66,
                0 0 65px #b4caff24;
        }

        .sky-caption {
            position: absolute;
            bottom: 16px;
            color: #b4bfd4;
            font-size: 10px;
            letter-spacing: .22em;
            text-transform: uppercase;
        }

        .preview-content { padding: 27px; }

        .preview-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .preview-label {
            color: var(--muted);
            font-size: 10px;
            letter-spacing: .17em;
            text-transform: uppercase;
        }

        .badge {
            padding: 4px 9px;
            border: 1px solid #b9c6f027;
            border-radius: 6px;
            color: #c8d3ec;
            background: #b9c6f009;
            font-size: 11px;
        }

        .preview-name {
            margin: 0 0 12px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 29px;
            font-weight: 400;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .preview-description {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5;
            overflow: hidden;
            margin: 0;
            color: #aeb9cf;
            font-size: 14px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .note {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 20px 5px 0;
            color: var(--muted);
            font-size: 13px;
        }

        .note-symbol { color: var(--accent); font-size: 19px; }
        .note p { margin: 0; }
        .note strong { color: #d4dcef; font-weight: 500; }

        @media (max-width: 800px) {
            .layout { grid-template-columns: 1fr; }
            .hero { margin-top: 38px; }
            .sky { height: 230px; }
        }

        @media (max-width: 480px) {
            .shell { width: calc(100% - 28px); }
            .topbar { min-height: 76px; gap: 12px; }
            .brand { font-size: 16px; }
            .back { font-size: 12px; }
            .form-panel { padding: 23px 20px; }
            .actions { flex-direction: column; gap: 15px; }
            .primary { width: 100%; }
            .hero p:last-child { font-size: 14px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition: none !important; }
        }
    </style>
</head>

<body>
    @php
        $canSave = \Illuminate\Support\Facades\Route::has('character.store');
    @endphp

    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true"></span>
                DandAI
            </a>

            <a class="back" href="{{ route('character.index') }}">
                <span aria-hidden="true">←</span> Мои персонажи
            </a>
        </header>

        <main>
            <section class="hero" aria-labelledby="page-title">
                <p class="eyebrow">Мастерская персонажей · Первый набросок</p>
                <h1 id="page-title">У каждой легенды<br>есть начало.</h1>
                <p>
                    Дай герою имя или начни с одной мысли.
                    Остальное обретёт форму по ходу создания.
                </p>
            </section>

            <div class="layout">
                <section class="panel form-panel" aria-labelledby="form-title">
                    <h2 class="section-title" id="form-title">Замысел персонажа</h2>
                    <p class="section-description">
                        Каким будет тот, чью историю ты расскажешь?
                    </p>

                    <form
                        id="character-form"
                        method="POST"
                        action="{{ $canSave ? route('character.store') : '#' }}"
                        data-can-save="{{ $canSave ? 'true' : 'false' }}"
                    >
                        @csrf

                        <div class="field">
                            <div class="label-row">
                                <label for="name">Имя персонажа</label>
                                <span class="optional">Необязательно</span>
                            </div>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                maxlength="120"
                                value="{{ old('name') }}"
                                placeholder="Как его будут помнить?"
                                aria-describedby="name-hint name-error"
                                aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                            >

                            <div class="field-footer">
                                <p class="hint" id="name-hint">
                                    Имя можно выбрать позже.
                                </p>
                                <span class="counter" id="name-count">0 / 120</span>
                            </div>

                            <div id="name-error">
                                @error('name')
                                    <p class="error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="field">
                            <div class="label-row">
                                <label for="description">Идея и предыстория</label>
                                <span class="optional">Необязательно</span>
                            </div>

                            <textarea
                                id="description"
                                name="description"
                                maxlength="5000"
                                placeholder="Кто он? Чего ищет? Чего боится? Это может быть короткий образ, необычная способность или история, которую хочется прожить."
                                aria-describedby="description-hint description-error"
                                aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}"
                            >{{ old('description') }}</textarea>

                            <div class="field-footer">
                                <p class="hint" id="description-hint">
                                    Нескольких предложений достаточно.
                                </p>
                                <span class="counter" id="description-count">
                                    0 / 5000
                                </span>
                            </div>

                            <div id="description-error">
                                @error('description')
                                    <p class="error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="inspiration">
                            <p class="inspiration-label">Искра для вдохновения</p>

                            <div class="ideas">
                                <button
                                    type="button"
                                    class="idea"
                                    data-idea="Охотник за реликвиями, который однажды нашёл предмет, помнящий его будущее."
                                >
                                    Охотник за реликвиями
                                </button>

                                <button
                                    type="button"
                                    class="idea"
                                    data-idea="Маг, потерявший собственные воспоминания, но сохранивший обещание, которое обязан выполнить."
                                >
                                    Маг без прошлого
                                </button>

                                <button
                                    type="button"
                                    class="idea"
                                    data-idea="Изгнанный наследник, который хочет выбрать собственную судьбу, а не вернуть утраченный трон."
                                >
                                    Изгнанный наследник
                                </button>
                            </div>
                        </div>

                        <div class="actions">
                            <button
                                type="submit"
                                class="primary"
                                @disabled(!$canSave)
                            >
                                Создать черновик
                                <span aria-hidden="true">↗</span>
                            </button>

                            <a class="cancel" href="{{ route('character.index') }}">
                                Отмена
                            </a>
                        </div>
                    </form>
                </section>

                <aside aria-label="Предпросмотр персонажа">
                    <div class="panel preview">
                        <div class="sky" aria-hidden="true">
                            <div class="orbit"></div>
                            <div class="moon"></div>
                            <span class="sky-caption">История ещё не написана</span>
                        </div>

                        <div class="preview-content">
                            <div class="preview-top">
                                <span class="preview-label">Твой персонаж</span>
                                <span class="badge">Черновик</span>
                            </div>

                            <h2 class="preview-name" id="preview-name">
                                {{ old('name') ?: 'Безымянный герой' }}
                            </h2>

                            <p class="preview-description" id="preview-description">{{ old('description') ?: 'Пока здесь тишина. Добавь первую мысль о персонаже — и она станет началом его истории.' }}</p>
                        </div>
                    </div>

                    <div class="note">
                        <span class="note-symbol" aria-hidden="true">✧</span>
                        <p>
                            <strong>Пока достаточно идеи.</strong><br>
                            Происхождение, класс и способности можно
                            определить во время дальнейшей работы над персонажем.
                        </p>
                    </div>
                </aside>
            </div>
        </main>
    </div>

    <script>
        const form = document.getElementById('character-form');
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');

        function updatePreview() {
            document.getElementById('preview-name').textContent =
                nameInput.value.trim() || 'Безымянный герой';

            document.getElementById('preview-description').textContent =
                descriptionInput.value.trim() ||
                'Пока здесь тишина. Добавь первую мысль о персонаже — и она станет началом его истории.';

            document.getElementById('name-count').textContent =
                `${nameInput.value.length} / 120`;

            document.getElementById('description-count').textContent =
                `${descriptionInput.value.length} / 5000`;
        }

        nameInput.addEventListener('input', updatePreview);
        descriptionInput.addEventListener('input', updatePreview);

        document.querySelectorAll('[data-idea]').forEach(button => {
            button.addEventListener('click', () => {
                const existing = descriptionInput.value.trim();
                const addition = button.dataset.idea;

                const combined = existing
                    ? `${existing}\n\n${addition}`
                    : addition;

                if (combined.length <= descriptionInput.maxLength) {
                    descriptionInput.value = combined;
                    updatePreview();
                }

                descriptionInput.focus();
            });
        });

        form.addEventListener('submit', event => {
            if (form.dataset.canSave !== 'true') {
                event.preventDefault();
            }
        });

        updatePreview();
    </script>
</body>
</html>
