<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>@yield('title') — DandAI</title>
    <style>
        :root { color-scheme: dark; --bg:#090d17; --panel:#121a2b; --text:#edf0f8; --muted:#a4b0c8; --accent:#c8ceff; --line:#c8ceff25; }
        * { box-sizing:border-box; }
        body { margin:0; background:radial-gradient(ellipse at 80% 0%,#303d6544,transparent 55%),var(--bg); color:var(--text); font:15px/1.7 "Segoe UI",system-ui,sans-serif; min-height:100vh; }
        a { color:var(--accent); text-underline-offset:4px; }
        button,input,textarea,select { font:inherit; }
        :focus-visible { outline:2px solid var(--accent); outline-offset:4px; }
        .shell { width:min(1080px,calc(100% - 40px)); margin:auto; }
        .topbar { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; padding:22px 0; border-bottom:1px solid var(--line); }
        .brand { font-size:19px; font-weight:600; letter-spacing:.07em; text-decoration:none; color:var(--text); }
        nav,.actions { display:flex; align-items:center; flex-wrap:wrap; gap:12px; }
        nav a { font-size:13px; text-decoration:none; }
        nav a[aria-current="page"] { color:white; border-bottom:1px solid var(--accent); }
        main { padding:42px 0 64px; }
        .eyebrow { color:var(--accent); text-transform:uppercase; font-size:10px; letter-spacing:.22em; margin:0 0 8px; }
        h1,h2,h3 { overflow-wrap:anywhere; }
        h1 { font:400 clamp(30px,5vw,46px)/1.2 Georgia,serif; margin:0 0 16px; }
        h2 { font:400 25px/1.3 Georgia,serif; margin:0; }
        h3 { font-size:14px; margin:22px 0 8px; }
        .muted { color:var(--muted); }
        .intro { max-width:680px; margin:0 0 28px; color:var(--muted); }
        .heading { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin:0 0 22px; }
        .grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:18px; }
        .card { padding:25px; border:1px solid var(--line); border-radius:18px; background:linear-gradient(140deg,#192338,var(--panel)); min-width:0; margin-bottom:20px; }
        .grid .card { margin:0; }
        .card a { overflow-wrap:anywhere; }
        .badge { display:inline-block; padding:3px 9px; border:1px solid var(--line); border-radius:20px; font-size:11px; color:var(--accent); }
        .button,button { display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:9px 16px; border:1px solid #d1d7ff; border-radius:9px; background:linear-gradient(135deg,#e0e4ff,#b7c3e9); color:#172139; font-size:13px; font-weight:600; text-decoration:none; cursor:pointer; }
        .button.secondary,button.secondary { color:var(--accent); border-color:var(--line); background:#c8ceff08; }
        .actions form { margin:0; }
        .notice,.errors { padding:14px 18px; border:1px solid #b5dccc40; border-radius:12px; margin-bottom:22px; color:#c3e1d4; background:#a2cdbd0a; }
        .errors { color:#ffc3ca; border-color:#ffb2bd40; background:#ffb2bd08; }
        .errors ul { margin:0; padding-left:20px; }
        label { display:block; margin-bottom:8px; font-size:13px; color:#ccd5e9; }
        input,textarea { width:100%; background:#0b111d; color:var(--text); border:1px solid #3b4662; border-radius:10px; padding:12px 14px; }
        textarea { resize:vertical; min-height:120px; }
        .field { margin-bottom:22px; }
        .hint { color:var(--muted); font-size:12px; margin:7px 0 0; }
        .prose { white-space:pre-wrap; overflow-wrap:anywhere; color:#c2cde2; }
        details { margin:20px 0; }
        summary { cursor:pointer; color:var(--accent); font-size:13px; }
        pre { white-space:pre-wrap; overflow-wrap:anywhere; background:#090f1c; padding:16px; border-radius:10px; font-size:12px; }
        .pagination { margin-top:26px; display:flex; justify-content:space-between; gap:16px; align-items:center; font-size:13px; }
        .empty { text-align:center; padding:50px 24px; }
        @media(max-width:640px) { .grid { grid-template-columns:1fr; } .shell { width:calc(100% - 28px); } main { padding-top:28px; } .card { padding:20px; } nav { gap:10px; } }
    </style>
</head>
<body>
<div class="shell">
    <header class="topbar">
        <a class="brand" href="{{ route('home') }}">☾ DandAI</a>
        <nav aria-label="Мастерская и библиотеки">
            <a href="{{ route('character.index') }}">Персонажи</a>
            <a href="{{ route('race.index') }}" @if($routePrefix === 'race') aria-current="page" @endif>Расы</a>
            <a href="{{ route('class.index') }}" @if($routePrefix === 'class') aria-current="page" @endif>Классы</a>
        </nav>
    </header>
    <main>
        @if(session('success')) <div class="notice" role="status">{{ session('success') }}</div> @endif
        @if($errors->any())
            <div class="errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>

