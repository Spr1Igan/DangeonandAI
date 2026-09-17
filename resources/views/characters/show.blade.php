<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>Мои персонажи — DandAI</title>

    <style>
        :root {
            --background: #090d17;
            --surface: #111725;
            --text: #edf0f8;
            --muted: #9da8be;
            --accent: #c8ceff;
            --border: rgba(190, 204, 242, .13);
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
        a, summary { -webkit-tap-highlight-color: transparent; }
        ::selection { background: #818cba66; }
        :focus-visible { outline: 2px solid var(--accent); outline-offset: 5px; }
        .shell { width: min(1120px, calc(100% - 48px)); margin: 0 auto; }
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

        .back { color: var(--muted); font-size: 14px; text-decoration: none; }
        .back:hover { color: var(--text); }
        .hero {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 310px;
            align-items: center;
            gap: 36px;
            padding: 54px 0 44px;
        }

        .eyebrow {
            margin: 0 0 16px;
            color: var(--accent);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .22em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0 0 18px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(38px, 5.5vw, 58px);
            font-weight: 400;
            letter-spacing: -.04em;
            line-height: 1.12;
        }

        .intro { max-width: 510px; margin: 0; color: var(--muted); font-size: 15px; }
        .primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            min-height: 48px;
            padding: 12px 20px;
            border: 1px solid #e2e5ff;
            border-radius: 11px;
            background: linear-gradient(135deg, #e0e4ff, #b7c3e9);
            color: #172139;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 5px 22px #bac8ef14;
            transition: filter .2s, transform .2s;
        }

        .primary:hover { filter: brightness(1.07); transform: translateY(-1px); }
        .hero .primary { margin-top: 26px; }
        .plus { font-size: 22px; font-weight: 400; line-height: 1; }
        .celestial {
            position: relative;
            display: grid;
            place-items: center;
            width: 310px;
            height: 310px;
            background:
                radial-gradient(circle at 17% 26%, #c7d6f0 0 1px, transparent 2px),
                radial-gradient(circle at 84% 22%, #c7d6f0 0 1px, transparent 2px),
                radial-gradient(circle at 90% 68%, #aebcdd 0 1px, transparent 2px),
                radial-gradient(circle at 15% 74%, #aebcdd 0 1px, transparent 2px),
                radial-gradient(ellipse at center, #33446644, transparent 70%);
        }

        .orbit { position: absolute; inset: 22px; border: 1px solid #d0ddff20; border-radius: 50%; }
        .orbit::before {
            content: "";
            position: absolute;
            inset: 18px;
            border: 1px dashed #d0ddff20;
            border-radius: 50%;
        }

        .orbit::after {
            content: "✦";
            position: absolute;
            top: 32px;
            right: 24px;
            color: var(--accent);
            font-size: 17px;
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
            box-shadow: inset -21px -12px 25px #22334c70, inset 3px 3px 10px #ffffff66, 0 0 65px #b4caff24;
        }

        .sky-caption {
            position: absolute;
            bottom: 0;
            color: var(--muted);
            font-size: 9px;
            letter-spacing: .19em;
            text-transform: uppercase;
        }

        .notice {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            padding: 15px 18px;
            border: 1px solid #b5dccc30;
            border-radius: 12px;
            background: #a2cdbd0a;
            color: #c3e1d4;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .notice svg { flex: 0 0 20px; }
        .collection { padding-bottom: 50px; }
        .collection-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px 20px;
            padding: 0 0 20px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 26px;
        }

        .collection-title { display: flex; align-items: center; gap: 12px; }
        .collection-title h2 { margin: 0; font-size: 19px; font-weight: 600; letter-spacing: -.02em; }
        .count {
            min-width: 29px;
            padding: 2px 9px;
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--accent);
            background: #c8ceff08;
            font-size: 12px;
            text-align: center;
        }

        .sort-note { margin: 0; color: var(--muted); font-size: 12px; }
        .character-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; align-items: start; }
        .character-card {
            position: relative;
            min-width: 0;
            overflow: hidden;
            padding: 25px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: linear-gradient(150deg, #151d2d, var(--surface));
            box-shadow: 0 16px 45px #00000017;
            transition: border-color .2s;
        }

        .character-card:hover { border-color: #c8ceff40; }
        .character-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 25px;
            right: 25px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #c8ceff70, transparent);
        }

        .card-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 23px; }
        .sigil {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            border: 1px solid #bdc9ee26;
            border-radius: 50%;
            color: #d6def7;
            background: radial-gradient(circle at 30% 25%, #8a9dcd22, #c8ceff03);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 24px;
        }

        .badge { padding: 4px 9px; border: 1px solid #b9c6f027; border-radius: 6px; color: #c8d3ec; background: #b9c6f009; font-size: 11px; }
        .badge--active { border-color: #b5dccc30; background: #a2cdbd0a; color: #b9d9ca; }
        .badge--archived { border-color: #b3b9c622; background: #b3b9c609; color: #aab3c4; }
        .card-number { margin: 0 0 7px; color: var(--muted); font-size: 10px; letter-spacing: .15em; text-transform: uppercase; }
        .character-name {
            margin: 0 0 12px;
            color: var(--text);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 27px;
            font-weight: 400;
            letter-spacing: -.02em;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .description {
            min-height: 92px;
            margin: 0;
            color: #aeb9cf;
            font-size: 13px;
            line-height: 1.75;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .description--empty { color: var(--muted); font-style: italic; }
        .story { margin-top: 16px; border-top: 1px solid var(--border); padding-top: 13px; }
        .story summary { width: fit-content; color: var(--accent); font-size: 12px; cursor: pointer; }
        .story summary:hover { color: var(--text); }
        .full-story { max-height: 360px; overflow-y: auto; margin: 14px 0 0; padding-right: 8px; color: #bac5da; font-size: 13px; line-height: 1.8; white-space: pre-wrap; overflow-wrap: anywhere; scrollbar-color: #495570 var(--surface); }
        .card-meta { display: flex; justify-content: space-between; align-items: flex-end; gap: 12px; margin: 22px 0 0; padding-top: 17px; border-top: 1px solid var(--border); }
        .card-meta > div { min-width: 0; }
        .card-meta > div:last-child { text-align: right; flex-shrink: 0; }
        .card-meta dt { color: var(--muted); font-size: 10px; }
        .card-meta dd { margin: 4px 0 0; color: #d1daed; font-size: 12px; overflow-wrap: anywhere; }
        .workshop-link {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 64px;
            margin-top: 22px;
            padding: 12px 14px;
            border: 1px solid #b9c6f03d;
            border-radius: 12px;
            background: linear-gradient(125deg, #c8ceff18, #b7c3e908);
            color: #e0e5ff;
            text-decoration: none;
            box-shadow: inset 0 1px 0 #eef0ff09;
            transition: border-color .2s, background .2s, box-shadow .2s, transform .2s;
        }

        .workshop-link:hover {
            border-color: #c8ceff80;
            background: linear-gradient(125deg, #c8ceff28, #b7c3e912);
            box-shadow: 0 5px 22px #bac8ef0d, inset 0 1px 0 #eef0ff12;
            transform: translateY(-1px);
        }

        .workshop-link:focus-visible { border-color: var(--accent); }
        .workshop-icon {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border: 1px solid #c8ceff20;
            border-radius: 10px;
            background: #c8ceff0c;
            color: var(--accent);
        }

        .workshop-copy { min-width: 0; }
        .workshop-title { display: block; font-size: 13px; font-weight: 600; }
        .workshop-hint { display: block; margin-top: 1px; color: #aeb9cf; font-size: 10px; }
        .workshop-arrow { margin-left: auto; padding-left: 4px; color: var(--accent); font-size: 20px; }
        .empty-state {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 40px;
            padding: 46px;
            border: 1px dashed #b9c6f033;
            border-radius: 22px;
            background: radial-gradient(ellipse at 10% 50%, #33446630, transparent 60%), #11172588;
        }

        .empty-symbol {
            position: relative;
            display: grid;
            place-items: center;
            width: 110px;
            height: 110px;
            flex: 0 0 110px;
            border: 1px solid #bdc9ee30;
            border-radius: 50%;
            color: #c8ceff;
            font-size: 40px;
        }

        .empty-symbol::after { content: ""; position: absolute; inset: 10px; border: 1px dashed #bdc9ee20; border-radius: 50%; }
        .empty-state h3 { margin: 0 0 10px; font-family: Georgia, "Times New Roman", serif; font-size: 30px; font-weight: 400; line-height: 1.2; }
        .empty-state p { max-width: 470px; margin: 0 0 22px; color: var(--muted); font-size: 14px; }
        .text-link { display: inline-flex; align-items: center; gap: 10px; min-height: 44px; color: var(--accent); font-size: 14px; text-underline-offset: 5px; }
        .pagination-bar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; margin-top: 30px; }
        .page-info { margin: 0; color: var(--muted); font-size: 12px; }
        .pagination { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; }
        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            min-height: 40px;
            padding: 6px 12px;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: #c5cee2;
            background: #111725;
            font-size: 13px;
            text-decoration: none;
        }

        a.page-link:hover { border-color: #c8ceff55; background: #c8ceff0c; }
        .page-link[aria-current="page"] { border-color: #c8ceff66; background: #c8ceff1a; color: #edf0ff; }
        .page-link[aria-disabled="true"] { color: #727e95; background: transparent; }
        .ellipsis { padding: 0 3px; color: var(--muted); }
        .footer { display: flex; justify-content: space-between; gap: 20px; padding: 22px 0 30px; border-top: 1px solid var(--border); color: var(--muted); font-size: 11px; }
        .footer p { margin: 0; }
        .footer-mark { color: var(--accent); letter-spacing: .16em; white-space: nowrap; }

        @media (max-width: 1000px) {
            .character-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .hero { grid-template-columns: minmax(0, 1fr) 250px; gap: 20px; }
            .celestial { width: 250px; height: 280px; }
            .orbit { inset: 27px 12px; }
        }

        @media (max-width: 680px) {
            .hero { grid-template-columns: 1fr; padding: 38px 0 34px; }
            .celestial { display: none; }
            .character-grid { grid-template-columns: 1fr; }
            .empty-state { padding: 30px 25px; gap: 24px; flex-direction: column; text-align: center; }
            .empty-symbol { width: 84px; height: 84px; flex-basis: 84px; font-size: 32px; }
            .pagination-bar { align-items: flex-start; flex-direction: column; }
            .description { min-height: 0; }
        }

        @media (max-width: 480px) {
            .shell { width: calc(100% - 28px); }
            .topbar { min-height: 76px; gap: 12px; }
            .brand { font-size: 16px; }
            .back { font-size: 12px; }
            .hero .primary { width: 100%; }
            .character-card { padding: 22px; }
            .footer { flex-direction: column; gap: 8px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition: none !important; }
        }
    </style>
</head>

<body>
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true"></span>
                DandAI
            </a>
            <nav style="display:flex; flex-wrap:wrap; gap:14px" aria-label="Библиотеки и главная">
                <a class="back" href="{{ route('race.index') }}">Расы</a>
                <a class="back" href="{{ route('class.index') }}">Классы</a>
                <a class="back" href="{{ route('home') }}"><span aria-hidden="true">←</span> На главную</a>
            </nav>
        </header>

        <main>
            <section class="hero" aria-labelledby="page-title">
                <div>
                    <p class="eyebrow">Мастерская персонажей · Твои истории</p>
                    <h1 id="page-title">Здесь рождаются<br>твои легенды.</h1>
                    <p class="intro">Первые наброски и герои с прошлым. Собери свои идеи в одном месте — у каждой из них может быть большая история.</p>
                    <a class="primary" href="{{ route('character.create') }}">
                        <span class="plus" aria-hidden="true">+</span> Создать персонажа
                    </a>
                </div>
                <div class="celestial" aria-hidden="true">
                    <div class="orbit"></div>
                    <div class="moon"></div>
                    <span class="sky-caption">Каждый герой — новая история</span>
                </div>
            </section>

            @if (session('success'))
                <div class="notice" role="status">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" />
                        <path d="m8 12 3 3 5-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <section class="collection" aria-labelledby="collection-title">
                <div class="collection-heading">
                    <div class="collection-title">
                        <h2 id="collection-title">Мои персонажи</h2>
                        <span class="count" aria-label="Всего персонажей: {{ $characters->total() }}">{{ $characters->total() }}</span>
                    </div>
                    @if ($characters->total() > 0)
                        <p class="sort-note">Сначала недавно созданные</p>
                    @endif
                </div>

                <div class="character-grid">
                    @forelse ($characters as $character)
                        @php
                            $characterName = trim((string) $character->name);
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
                        @endphp

                        <article class="character-card" aria-labelledby="character-{{ $character->id }}-name">
                            <div class="card-top">
                                <span class="sigil" aria-hidden="true">{{ $characterName !== '' ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($characterName, 0, 1)) : '✧' }}</span>
                                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </div>
                            <p class="card-number">Персонаж · {{ str_pad((string) $character->id, 3, '0', STR_PAD_LEFT) }}</p>
                            <h3 class="character-name" id="character-{{ $character->id }}-name">{{ $characterName !== '' ? $characterName : 'Безымянный герой' }}</h3>
                            <p class="description {{ $description === '' ? 'description--empty' : '' }}">{{ $description !== '' ? \Illuminate\Support\Str::limit($description, 160) : 'Пока только чистый лист. Его история ещё ждёт первых слов.' }}</p>

                            @if (\Illuminate\Support\Str::length($description) > 160)
                                <details class="story">
                                    <summary>Полная предыстория</summary>
                                    <p class="full-story" tabindex="0" role="region" aria-label="Предыстория персонажа {{ $characterName !== '' ? $characterName : 'Безымянный герой' }}">{{ $description }}</p>
                                </details>
                            @endif

                            <dl class="card-meta">
                                <div>
                                    <dt>Система</dt>
                                    <dd>{{ $systemLabel }}</dd>
                                </div>
                                <div>
                                    <dt>Изменён</dt>
                                    <dd>
                                        @if ($character->updated_at)
                                            <time datetime="{{ $character->updated_at->toIso8601String() }}">{{ $character->updated_at->format('d.m.Y') }}</time>
                                        @else
                                            —
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                            <a
                                class="workshop-link"
                                href="{{ route('character.workshop', $character) }}"
                                aria-label="Открыть чат мастерской: {{ $characterName !== '' ? $characterName : 'Безымянный герой' }}"
                            >
                                <span class="workshop-icon" aria-hidden="true">
                                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none">
                                        <path d="M20 11.5a7.5 7.5 0 0 1-7.5 7.5H5l-3 3V11.5A7.5 7.5 0 0 1 9.5 4h3a7.5 7.5 0 0 1 7.5 7.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                        <path d="M7 10h8M7 14h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                </span>
                                <span class="workshop-copy">
                                    <span class="workshop-title">Открыть чат</span>
                                    <span class="workshop-hint">Мастерская персонажа</span>
                                </span>
                                <span class="workshop-arrow" aria-hidden="true">↗</span>
                            </a>
                        </article>
                    @empty
                        <div class="empty-state">
                            <div class="empty-symbol" aria-hidden="true">✧</div>
                            <div>
                                @if ($characters->total() > 0)
                                    <h3>Эта страница пуста</h3>
                                    <p>Твои персонажи есть на других страницах. Вернись к началу коллекции, чтобы увидеть их.</p>
                                    <a class="text-link" href="{{ $characters->url(1) }}">На первую страницу <span aria-hidden="true">↗</span></a>
                                @else
                                    <h3>Первая история — за тобой</h3>
                                    <p>Начни с имени, необычной идеи или пары строк о прошлом. Этого достаточно, чтобы появился первый герой.</p>
                                    <a class="text-link" href="{{ route('character.create') }}">Создать первого персонажа <span aria-hidden="true">↗</span></a>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                @if ($characters->hasPages())
                    <div class="pagination-bar">
                        <p class="page-info">
                            @if ($characters->count() > 0)
                                Показаны {{ $characters->firstItem() }}–{{ $characters->lastItem() }} из {{ $characters->total() }}
                            @else
                                Всего персонажей: {{ $characters->total() }}
                            @endif
                        </p>
                        <nav class="pagination" aria-label="Страницы персонажей">
                            @if ($characters->onFirstPage())
                                <span class="page-link" aria-disabled="true" aria-label="Предыдущая страница">←</span>
                            @else
                                <a class="page-link" href="{{ $characters->previousPageUrl() }}" rel="prev" aria-label="Предыдущая страница">←</a>
                            @endif

                            @php
                                $currentPage = min($characters->currentPage(), $characters->lastPage());
                                $startPage = max(1, $currentPage - 1);
                                $endPage = min($characters->lastPage(), $currentPage + 1);
                            @endphp

                            @if ($startPage > 1)
                                <a class="page-link" href="{{ $characters->url(1) }}" aria-label="Страница 1">1</a>
                                @if ($startPage > 2)
                                    <span class="ellipsis" aria-hidden="true">…</span>
                                @endif
                            @endif

                            @foreach ($characters->getUrlRange($startPage, $endPage) as $page => $url)
                                @if ($page === $characters->currentPage())
                                    <span class="page-link" aria-current="page" aria-label="Страница {{ $page }}">{{ $page }}</span>
                                @else
                                    <a class="page-link" href="{{ $url }}" aria-label="Страница {{ $page }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($endPage < $characters->lastPage())
                                @if ($endPage < $characters->lastPage() - 1)
                                    <span class="ellipsis" aria-hidden="true">…</span>
                                @endif
                                <a class="page-link" href="{{ $characters->url($characters->lastPage()) }}" aria-label="Страница {{ $characters->lastPage() }}">{{ $characters->lastPage() }}</a>
                            @endif

                            @if ($characters->hasMorePages())
                                <a class="page-link" href="{{ $characters->nextPageUrl() }}" rel="next" aria-label="Следующая страница">→</a>
                            @else
                                <span class="page-link" aria-disabled="true" aria-label="Следующая страница">→</span>
                            @endif
                        </nav>
                    </div>
                @endif
            </section>
        </main>

        <footer class="footer">
            <p>Большие приключения начинаются с маленького замысла.</p>
            <span class="footer-mark" aria-hidden="true">☾ &nbsp; DandAI</span>
        </footer>
    </div>
</body>
</html>
