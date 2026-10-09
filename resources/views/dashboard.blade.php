<!DOCTYPE html>
<html lang="en" class="h-full bg-[#090C14]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Milo — AI Workspace</title>
    
    <!-- Google Fonts: Plus Jakarta Sans, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#090C14] text-[#B7C2D6] antialiased flex flex-col min-h-screen relative selection:bg-[#2DE3C8] selection:text-[#090C14] font-sans">

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

    <!-- Top Navigation Header -->
    <header class="bg-[#090C14]/90 backdrop-blur-xl border-b border-white/10 sticky top-0 z-30 shadow-2xl relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand Logo & Badge -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="brand-badge group-hover:scale-105 transition-transform duration-300">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#2DE3C8" stroke-width="2.2"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-extrabold tracking-tight text-[#EAF1F7] flex items-center gap-2">
                                Milo
                                <span class="status-chip">
                                    AI Workspace
                                </span>
                                <span class="status-chip violet hidden sm:inline">
                                    Claude 3.5 Sonnet
                                </span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Global Action Controls -->
                <div class="flex items-center space-x-3">
                    @if($isConnected)
                        <!-- Sync Inbox Now Button -->
                        <form action="{{ route('dashboard.sync') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="app-launch py-1.5 px-3.5 text-xs">
                                <svg class="w-3.5 h-3.5 text-[var(--cyan)] animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Sync Inbox
                            </button>
                        </form>

                        <!-- Compose Custom Email Modal Trigger -->
                        <button onclick="document.getElementById('composeModal').classList.remove('hidden')" class="app-launch violet py-1.5 px-3.5 text-xs font-bold cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Compose
                        </button>
                    @endif

                    <!-- User Account / Connection Pill -->
                    <div class="flex items-center pl-3 border-l border-white/10 space-x-2">
                        @if($isConnected)
                            <div class="sso-pill mono py-1 px-3">
                                <span class="dot"></span>
                                <span class="font-bold">Gmail Connected:</span>
                                <span class="hidden sm:inline text-[#EAF1F7]">{{ $user->email }}</span>
                            </div>

                            <form action="{{ route('auth.google.disconnect') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Disconnect Google Account" class="text-xs text-[#7C8AA0] hover:text-[var(--red)] transition-colors p-1.5 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('auth.google') }}" class="app-launch py-1.5 px-3.5 text-xs font-bold">
                                Connect Gmail
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs mono text-[#7C8AA0] hover:text-white px-2 py-1 transition-colors cursor-pointer">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Notification Banners -->
    @if(session('status'))
        <div class="bg-[rgba(45,227,200,0.12)] border-b border-[rgba(45,227,200,0.3)] text-[var(--cyan)] px-4 py-2.5 text-xs mono text-center flex items-center justify-center gap-2 relative z-20">
            <svg class="w-4 h-4 text-[var(--cyan)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-[rgba(242,120,120,0.12)] border-b border-[rgba(242,120,120,0.3)] text-[var(--red)] px-4 py-2.5 text-xs mono text-center flex items-center justify-center gap-2 relative z-20">
            <svg class="w-4 h-4 text-[var(--red)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Workspace Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col space-y-6 relative z-10">

        <!-- Vaultline Stats Overview Ribbon -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mono">
            <a href="{{ route('dashboard', ['filter' => 'pending']) }}" class="role-card group">
                <div class="role-inner p-4 justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[var(--amber)] uppercase tracking-wider">Pending Review</span>
                        <span class="status-chip amber text-[10px]">Awaiting Send</span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2 font-sans">
                        <span class="text-2xl font-extrabold text-[#EAF1F7] group-hover:text-[var(--amber)] transition-colors">{{ $pendingDraftsCount }}</span>
                        <span class="text-[11px] text-[#7C8AA0] mono">Drafts</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('dashboard', ['filter' => 'urgent']) }}" class="role-card group">
                <div class="role-inner p-4 justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[var(--red)] uppercase tracking-wider">Urgent Action</span>
                        <span class="status-chip red text-[10px]">Priority</span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2 font-sans">
                        <span class="text-2xl font-extrabold text-[#EAF1F7] group-hover:text-[var(--red)] transition-colors">{{ $urgentEmailsCount }}</span>
                        <span class="text-[11px] text-[#7C8AA0] mono">High Urgency</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('dashboard', ['filter' => 'all']) }}" class="role-card group">
                <div class="role-inner p-4 justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[var(--cyan)] uppercase tracking-wider">Total Synced</span>
                        <span class="status-chip text-[10px]">Active</span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2 font-sans">
                        <span class="text-2xl font-extrabold text-[#EAF1F7] group-hover:text-[var(--cyan)] transition-colors">{{ $totalEmails }}</span>
                        <span class="text-[11px] text-[#7C8AA0] mono">Ingested</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('dashboard', ['filter' => 'sent']) }}" class="role-card group">
                <div class="role-inner p-4 justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[var(--violet)] uppercase tracking-wider">Sent Replies</span>
                        <span class="status-chip violet text-[10px]">Dispatched</span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2 font-sans">
                        <span class="text-2xl font-extrabold text-[#EAF1F7] group-hover:text-[var(--violet)] transition-colors">{{ $sentRepliesCount }}</span>
                        <span class="text-[11px] text-[#7C8AA0] mono">Verified</span>
                    </div>
                </div>
            </a>
        </div>

        @if(!$isConnected)
            <!-- Unconnected Prompt Box in Vaultline Style -->
            <div class="role-card max-w-xl mx-auto my-8">
                <div class="role-inner p-10 text-center space-y-4">
                    <div class="brand-badge w-14 h-14 mx-auto">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2DE3C8" stroke-width="2"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-[#EAF1F7]">Connect Your Gmail Account</h3>
                    <p class="text-xs sm:text-sm text-[#B7C2D6] leading-relaxed">
                        Connect your Google account to let <strong class="text-[#EAF1F7]">Milo</strong> read incoming messages, detect urgency, classify intent, propose drafts, and help you take swift action.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('auth.google') }}" class="app-launch py-3 px-6 text-xs font-bold">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5l7 7-7 7M3 12h18"/></svg>
                            <span>Authorize with Google OAuth</span>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- 3-Column AI Email Workspace -->
            <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-[680px]">

                <!-- Column 1: Incoming Email Feed (Left Sidebar, 4 Cols) -->
                <div class="lg:col-span-4 glass-panel rounded-2xl border border-white/10 shadow-xl flex flex-col overflow-hidden">
                    
                    <!-- Search & Filter Header -->
                    <div class="p-4 border-b border-white/10 space-y-3 bg-[#090C14]">
                        <form method="GET" action="{{ route('dashboard') }}" class="search-bar w-full">
                            <input type="hidden" name="filter" value="{{ $currentFilter }}">
                            <span class="search-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
                            </span>
                            <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Search sender, subject, content...">
                        </form>

                        <!-- Filter Chips -->
                        <div class="flex flex-wrap gap-1.5 text-xs mono">
                            @php
                                $filters = [
                                    'all' => 'All',
                                    'pending' => 'Pending',
                                    'urgent' => 'Urgent',
                                    'important' => 'Important',
                                    'sent' => 'Sent',
                                ];
                            @endphp
                            @foreach($filters as $fKey => $fLabel)
                                <a href="{{ route('dashboard', ['filter' => $fKey, 'q' => $searchQuery]) }}" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition-all {{ $currentFilter === $fKey ? 'bg-[var(--cyan)] text-[#090C14] font-bold shadow-md' : 'bg-white/5 text-[#7C8AA0] border border-white/10 hover:text-white hover:bg-white/10' }}">
                                    {{ $fLabel }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Email Feed List -->
                    <div class="flex-1 overflow-y-auto divide-y divide-white/5 max-h-[640px] workbench-scroll">
                        @forelse($emails as $email)
                            @php
                                $isSelected = $selectedEmail && $selectedEmail->id === $email->id;
                                $analysis = $email->aiAnalysis;
                                $draft = $email->draft;
                            @endphp
                            <a href="{{ route('dashboard', ['filter' => $currentFilter, 'q' => $searchQuery, 'selected' => $email->id]) }}" class="block p-4 transition-all hover:bg-white/5 {{ $isSelected ? 'bg-[rgba(45,227,200,0.08)] border-l-4 border-[var(--cyan)] pl-3 shadow-inner' : 'border-l-4 border-transparent' }}">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-xs text-[#EAF1F7] truncate">{{ $email->sender_display }}</span>
                                            @if($email->has_attachments)
                                                <svg class="w-3.5 h-3.5 text-[var(--cyan)] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            @endif
                                        </div>
                                        <h4 class="text-xs font-semibold text-[#B7C2D6] truncate mb-1">{{ $email->subject ?: '(No Subject)' }}</h4>
                                        <p class="text-[11px] text-[#7C8AA0] line-clamp-2 leading-relaxed">{{ $email->snippet ?: strip_tags((string)$email->body_plain) }}</p>
                                    </div>
                                    <span class="text-[10px] text-[#4B5568] mono shrink-0">{{ $email->received_at->diffForHumans(null, true, true) }}</span>
                                </div>

                                <!-- Metadata Tags -->
                                <div class="mt-2.5 flex flex-wrap items-center gap-1.5 mono">
                                    @if($analysis)
                                        @if($analysis->urgency_level === 'urgent')
                                            <span class="status-chip red">Urgent</span>
                                        @elseif($analysis->urgency_level === 'important')
                                            <span class="status-chip amber">Important</span>
                                        @endif

                                        <span class="status-chip">
                                            {{ $analysis->intent }}
                                        </span>
                                    @endif

                                    @if($draft)
                                        @if($draft->status === 'sent')
                                            <span class="status-chip violet">Sent</span>
                                        @elseif($draft->status === 'pending' || $draft->status === 'edited')
                                            <span class="status-chip">Draft Ready</span>
                                        @endif
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center text-[#7C8AA0] mono">
                                <svg class="w-8 h-8 mx-auto mb-2 text-[#4B5568]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-xs font-medium">No emails found matching criteria.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($emails->hasPages())
                        <div class="p-3 border-t border-white/10 text-xs mono">
                            {{ $emails->links() }}
                        </div>
                    @endif
                </div>

                <!-- Column 2: Selected Email Reader (Center Pane, 4 Cols) -->
                <div class="lg:col-span-4 glass-panel rounded-2xl border border-white/10 shadow-xl flex flex-col overflow-hidden">
                    @if($selectedEmail)
                        <!-- Email Reader Header Bar -->
                        <div class="p-4 border-b border-white/10 bg-[#090C14] flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <!-- Trash Button -->
                                <form action="{{ route('emails.trash', $selectedEmail) }}" method="POST" onsubmit="return confirm('Move this email to Gmail trash?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Move to Trash" class="p-1.5 text-[#7C8AA0] hover:text-[var(--red)] rounded-lg hover:bg-white/5 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>

                                <!-- Apply Labels Button -->
                                @if($selectedEmail->aiAnalysis && !empty($selectedEmail->aiAnalysis->suggested_labels))
                                    <form action="{{ route('emails.labels', $selectedEmail) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Sync Suggested Labels to Gmail" class="app-launch py-1 px-2.5 text-[11px] mono cursor-pointer">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                            Sync Labels
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <span class="text-[11px] text-[#7C8AA0] mono">
                                ID: {{ substr($selectedEmail->gmail_message_id, 0, 8) }}...
                            </span>
                        </div>

                        <!-- Email Meta -->
                        <div class="p-6 border-b border-white/10 space-y-2">
                            <h2 class="text-base sm:text-lg font-extrabold text-[#EAF1F7] leading-snug">
                                {{ $selectedEmail->subject ?: '(No Subject)' }}
                            </h2>
                            <div class="flex items-start justify-between text-xs text-[#B7C2D6] font-sans">
                                <div>
                                    <p class="font-bold text-[#EAF1F7]">{{ $selectedEmail->sender_display }}</p>
                                    <p class="text-[#7C8AA0] mono text-[11px]">&lt;{{ $selectedEmail->sender_email }}&gt;</p>
                                    <p class="text-[11px] text-[#7C8AA0] mt-1">To: {{ $selectedEmail->recipient_email }}</p>
                                </div>
                                <div class="text-right text-[11px] text-[#7C8AA0] mono">
                                    {{ $selectedEmail->received_at->format('M d, Y · h:i A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Email Body Content Surface -->
                        <div class="flex-1 p-6 overflow-y-auto bg-[#090C14] max-h-[460px] workbench-scroll">
                            <div class="text-[#EAF1F7] text-xs leading-relaxed max-w-none whitespace-pre-wrap mono">
                                {{ $selectedEmail->body_plain ?: strip_tags((string)$selectedEmail->body_html) }}
                            </div>
                        </div>
                    @else
                        <div class="flex-1 flex items-center justify-center p-12 text-center text-[#7C8AA0]">
                            <div>
                                <svg class="w-12 h-12 mx-auto mb-3 text-[#4B5568]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm font-medium text-[#7C8AA0] mono">Select an email to view full content</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Column 3: AI Intelligence & Approval Rail (Right Pane, 4 Cols) -->
                <div class="lg:col-span-4 glass-panel rounded-2xl border border-white/10 shadow-xl flex flex-col overflow-hidden">
                    @if($selectedEmail && $selectedEmail->aiAnalysis)
                        @php
                            $analysis = $selectedEmail->aiAnalysis;
                            $draft = $selectedEmail->draft;
                        @endphp

                        <!-- AI Rail Header -->
                        <div class="p-4 border-b border-white/10 bg-[#090C14] text-white flex items-center justify-between mono">
                            <div class="flex items-center space-x-2">
                                <span class="dot"></span>
                                <span class="text-xs font-bold tracking-wide uppercase text-[#EAF1F7]">Milo Intelligence</span>
                            </div>
                            <span class="status-chip">
                                Score: {{ number_format($analysis->urgency_score, 1) }}/5.0
                            </span>
                        </div>

                        <div class="flex-1 p-5 overflow-y-auto space-y-4 max-h-[600px] workbench-scroll">
                            <!-- Executive Summary -->
                            <div class="cred-row space-y-1">
                                <span class="text-[10px] font-bold text-[var(--cyan)] uppercase tracking-wider block font-mono">Executive Summary</span>
                                <p class="text-xs text-[#EAF1F7] leading-relaxed font-sans">{{ $analysis->summary }}</p>
                            </div>

                            <!-- Intent & Urgency Metadata Grid -->
                            <div class="grid grid-cols-2 gap-3 text-xs mono">
                                <div class="cred-row">
                                    <span class="text-[10px] font-semibold text-[#7C8AA0] uppercase tracking-wider block mb-0.5">Intent</span>
                                    <span class="font-bold text-[#EAF1F7]">{{ $analysis->intent }}</span>
                                </div>
                                <div class="cred-row">
                                    <span class="text-[10px] font-semibold text-[#7C8AA0] uppercase tracking-wider block mb-0.5">Sentiment</span>
                                    <span class="font-bold text-[var(--cyan)] capitalize">{{ $analysis->sentiment }}</span>
                                </div>
                            </div>

                            <!-- Deadline Notice if present -->
                            @if($analysis->deadline_detected)
                                <div class="cred-row flex items-center gap-2.5 text-xs text-[var(--amber)] mono border-[rgba(245,166,35,0.3)]">
                                    <svg class="w-4 h-4 text-[var(--amber)] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    <div>
                                        <span class="font-bold">Deadline:</span>
                                        <span>{{ $analysis->deadline_detected->format('l, M d, Y \a\t h:i A') }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Suggested Labels -->
                            @if(!empty($analysis->suggested_labels))
                                <div>
                                    <span class="text-[10px] font-bold text-[#7C8AA0] uppercase tracking-wider block mb-2 mono">Suggested Labels</span>
                                    <div class="flex flex-wrap gap-1.5 mono">
                                        @foreach($analysis->suggested_labels as $label)
                                            <span class="status-chip violet text-[11px]">
                                                🏷️ {{ $label }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Human-in-the-Loop Draft Reply Section -->
                            <div class="border-t border-white/10 pt-4">
                                <div class="flex items-center justify-between mb-2.5 mono">
                                    <span class="text-xs font-bold text-[#EAF1F7] uppercase tracking-wide">Proposed Response</span>
                                    @if($draft)
                                        <span class="status-chip {{ $draft->status === 'sent' ? 'violet' : '' }} text-[10px]">
                                            Status: {{ $draft->status }}
                                        </span>
                                    @endif
                                </div>

                                @if($draft && ($draft->status === 'pending' || $draft->status === 'edited'))
                                    <!-- Editable Draft Form -->
                                    <form action="{{ route('drafts.edit', $draft) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="body" rows="6" class="w-full text-xs p-3.5 bg-[#090C14] border border-white/15 rounded-xl text-[#EAF1F7] focus:border-[var(--cyan)] focus:outline-none transition-all leading-relaxed font-sans" placeholder="Review or modify draft...">{{ $draft->effective_body }}</textarea>

                                        <div class="flex items-center gap-2 mono text-xs">
                                            <!-- Save Revision -->
                                            <button type="submit" class="px-3.5 py-2 font-semibold text-[#B7C2D6] bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-colors cursor-pointer">
                                                Save Edit
                                            </button>
                                    </form>

                                    <!-- Approve & Send Button -->
                                    <form action="{{ route('drafts.approve', $draft) }}" method="POST" class="inline flex-1">
                                        @csrf
                                        <button type="submit" class="app-launch w-full py-2 justify-center font-bold text-xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                            Approve & Send
                                        </button>
                                    </form>

                                    <!-- Reject Button -->
                                    <form action="{{ route('drafts.reject', $draft) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Reject Draft" class="p-2 text-[#7C8AA0] hover:text-[var(--red)] rounded-xl hover:bg-white/5 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                        </div>
                                @elseif($draft && $draft->status === 'sent')
                                    <div class="cred-row space-y-2 mono text-xs text-[var(--cyan)]">
                                        <div class="flex items-center gap-2 font-bold">
                                            <svg class="w-4 h-4 text-[var(--cyan)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Dispatched via Gmail API
                                        </div>
                                        <p class="text-[#EAF1F7] italic bg-[#090C14] p-3 rounded-lg border border-white/10 text-[11px] whitespace-pre-wrap font-sans">{{ $draft->effective_body }}</p>
                                        <span class="text-[10px] text-[#7C8AA0] block">Sent at: {{ $draft->sent_at?->format('M d, Y h:i A') }}</span>
                                    </div>
                                @elseif($draft && $draft->status === 'rejected')
                                    <div class="cred-row text-xs text-[#7C8AA0] italic text-center mono">
                                        Draft rejected by reviewer.
                                    </div>
                                @else
                                    <div class="cred-row text-xs text-[#7C8AA0] text-center mono">
                                        AI determined no reply is required for this email.
                                    </div>
                                @endif
                            </div>

                            <!-- Audit Activity Snippet -->
                            @if($selectedEmail->auditLogs->isNotEmpty())
                                <div class="border-t border-white/10 pt-4">
                                    <span class="text-[10px] font-bold text-[#7C8AA0] uppercase tracking-wider block mb-2 mono">Audit Trail</span>
                                    <div class="space-y-1.5 text-[11px] mono">
                                        @foreach($selectedEmail->auditLogs as $log)
                                            <div class="flex items-center justify-between text-[#B7C2D6] bg-white/5 px-3 py-1.5 rounded-lg border border-white/5">
                                                <span class="font-bold text-[var(--cyan)]">{{ $log->event_type }}</span>
                                                <span class="text-[#7C8AA0] text-[10px]">{{ $log->created_at->diffForHumans() }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex-1 flex items-center justify-center p-8 text-center text-[#7C8AA0] mono">
                            <div>
                                <svg class="w-10 h-10 mx-auto mb-2 text-[#4B5568]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <p class="text-xs font-medium text-[#7C8AA0]">Milo's AI intelligence appears here when an email is selected.</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        @endif

    </main>

    <!-- Floating Compose Modal in Vaultline Cyber Glass -->
    <div id="composeModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-[#090C14]/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="role-card max-w-lg w-full">
            <div class="role-inner p-0 overflow-hidden">
                <div class="p-4 border-b border-white/10 flex items-center justify-between bg-[#090C14]">
                    <h3 class="text-sm font-bold text-[#EAF1F7] flex items-center gap-2 mono">
                        <span class="dot"></span>
                        New Message (via Gmail API)
                    </h3>
                    <button onclick="document.getElementById('composeModal').classList.add('hidden')" class="text-[#7C8AA0] hover:text-white p-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('emails.send') }}" method="POST" class="p-5 space-y-4 mono text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-[#B7C2D6] mb-1">Recipient (To)</label>
                        <input type="email" name="to" required class="w-full p-3 bg-[#090C14] border border-white/10 rounded-xl text-[#EAF1F7] placeholder-[#4B5568] focus:border-[var(--cyan)] focus:outline-none font-sans" placeholder="client@example.com">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#B7C2D6] mb-1">Subject</label>
                        <input type="text" name="subject" required class="w-full p-3 bg-[#090C14] border border-white/10 rounded-xl text-[#EAF1F7] placeholder-[#4B5568] focus:border-[var(--cyan)] focus:outline-none font-sans" placeholder="Q4 Sync & Next Steps...">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#B7C2D6] mb-1">Email Body</label>
                        <textarea name="body" rows="6" required class="w-full p-3.5 bg-[#090C14] border border-white/10 rounded-xl text-[#EAF1F7] placeholder-[#4B5568] focus:border-[var(--cyan)] focus:outline-none leading-relaxed font-sans" placeholder="Write your message here..."></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button type="button" onclick="document.getElementById('composeModal').classList.add('hidden')" class="px-4 py-2.5 font-semibold text-[#7C8AA0] hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="app-launch py-2.5 px-5 font-bold cursor-pointer">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5l7 7-7 7M3 12h18"/></svg>
                            Send via Gmail
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
