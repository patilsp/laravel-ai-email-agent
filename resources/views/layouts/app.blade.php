<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Email AI Agent — An intelligent workspace that prepares your next move. Powered by Anthropic Claude with strict semi-autonomous human approval.">
    <meta name="theme-color" content="#0d0f12">

    <!-- Open Graph / Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Email AI Agent — Email comes in. Clarity comes out.">
    <meta property="og:description" content="An AI email workspace that prioritizes urgency, drafts thoughtful responses, and keeps you in complete control.">
    
    <title>Email AI Agent — Intelligent Workspace for Gmail</title>

    <!-- Editorial & Monospaced Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,300;1,6..72,400;1,6..72,500;1,6..72,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fbfaf7] text-[#121417] antialiased selection:bg-[#121417] selection:text-[#fbfaf7] font-sans overflow-x-hidden min-h-screen flex flex-col">
    
    <!-- Top Global System Ticker -->
    <div class="w-full bg-[#121417] text-[#9ca3af] text-[11px] font-mono border-b border-stone-800 py-1.5 px-4 sm:px-8 flex items-center justify-between tracking-tight">
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1.5 text-emerald-400 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                SYSTEM ACTIVE
            </span>
            <span class="text-stone-700">|</span>
            <span class="hidden sm:inline text-stone-400">ENGINE: ANTHROPIC CLAUDE 3.5 SONNET</span>
            <span class="hidden md:inline text-stone-700">|</span>
            <span class="hidden md:inline text-stone-400">MODE: SEMI-AUTONOMOUS (HUMAN GATEWAY)</span>
        </div>
        <div class="flex items-center gap-4 text-stone-400">
            <span class="hidden lg:inline">GMAIL API v1 PROTOCOL</span>
            <a href="#early-access" class="text-stone-300 hover:text-white transition-colors underline underline-offset-2">PRIVATE PREVIEW →</a>
        </div>
    </div>

    <!-- Navigation -->
    @include('marketing.partials.navbar')

    <!-- Main Content -->
    <main id="main-content" class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('marketing.partials.footer')

</body>
</html>
