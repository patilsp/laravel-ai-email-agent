<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Milo — AI Agent to handle emails. The colorful, hyper-intelligent workspace that prioritizes urgency, drafts contextual replies, and keeps you in complete control.">
    <meta name="theme-color" content="#090C14">

    <!-- Open Graph / Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Milo — AI Agent to handle emails">
    <meta property="og:description" content="Milo is your intelligent AI email agent: instant urgency triage, thread briefings, and human-verified draft dispatch.">
    
    <title>Milo — AI Agent to handle emails</title>

    <!-- Google Fonts: Plus Jakarta Sans, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#090C14] text-[#B7C2D6] antialiased selection:bg-[#2DE3C8] selection:text-[#090C14] font-sans overflow-x-hidden min-h-screen flex flex-col relative">

    <!-- 3D Infinite Perspective Floor & Glow Orbs (Vaultline Theme) -->
    <div class="floor-wrap">
        <div class="glow-orb orb1"></div>
        <div class="glow-orb orb2"></div>
        <div class="glow-orb orb3"></div>
        <div class="floor"></div>
    </div>
    <div id="particles"></div>

    <!-- Global Toast Notification -->
    <div class="toast" id="toast">Copied to clipboard</div>

    <!-- Top Global System Ticker -->
    <div class="relative z-50 w-full bg-[#0D1220]/90 backdrop-blur-md text-[#7C8AA0] text-[11px] font-mono-tech border-b border-white/10 py-2 px-4 sm:px-8 flex items-center justify-between tracking-tight">
        <div class="flex items-center gap-3">
            <span class="sso-pill py-0.5 px-2.5">
                <span class="dot"></span>
                MILO ACTIVE · CLAUDE 3.5 SONNET
            </span>
            <span class="text-white/20 hidden sm:inline">|</span>
            <span class="hidden sm:inline text-[#B7C2D6]">AUTONOMOUS EMAIL INTELLIGENCE</span>
            <span class="hidden md:inline text-white/20">|</span>
            <span class="hidden md:inline text-[var(--amber)]">SAFEGUARD: HUMAN APPROVAL REQUIRED</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden lg:inline text-[#7C8AA0]">GMAIL API v1 ENCRYPTED</span>
            @auth
                <a href="{{ route('dashboard') }}" class="app-launch py-1 px-3 text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7M3 12h18"/></svg>
                    Open Workspace
                </a>
            @else
                <a href="{{ route('dev.login') }}" class="app-launch violet py-1 px-3 text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Sandbox Demo
                </a>
            @endauth
        </div>
    </div>

    <!-- Navigation -->
    @include('marketing.partials.navbar')

    <!-- Main Dynamic Content -->
    <main id="main-content" class="flex-grow relative z-10">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('marketing.partials.footer')

</body>
</html>
