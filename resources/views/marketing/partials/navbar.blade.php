<header class="sticky top-0 z-40 w-full bg-[#090C14]/85 backdrop-blur-xl border-b border-white/10 transition-all" id="main-header">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="h-18 flex items-center justify-between">
            
            <!-- Vaultline Brand Logo Mark -->
            <a href="/" class="flex items-center gap-3.5 group focus:outline-none" aria-label="Milo — AI Agent to handle emails">
                <div class="brand-badge group-hover:scale-105 transition-transform duration-300">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2DE3C8" stroke-width="2.2">
                        <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                </div>
                
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-base tracking-tight text-[#EAF1F7] group-hover:text-[var(--cyan)] transition-colors">
                            Milo
                        </span>
                        <span class="status-chip">
                            AI Agent
                        </span>
                    </div>
                    <span class="text-[10px] text-[#7C8AA0] mono tracking-wider uppercase">
                        AI Agent to handle emails
                    </span>
                </div>
            </a>

            <!-- Vaultline Nav Links -->
            <nav class="hidden lg:flex items-center gap-8 text-xs mono uppercase tracking-wider" aria-label="Main Navigation">
                <a href="#interactive-workbench" class="text-[#B7C2D6] hover:text-[var(--cyan)] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-[var(--cyan)] font-bold">01/</span>
                    <span class="group-hover:underline underline-offset-4 decoration-[var(--cyan)]">Live Workbench</span>
                </a>
                <a href="#triage-system" class="text-[#B7C2D6] hover:text-[var(--cyan)] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-[var(--cyan)] font-bold">02/</span>
                    <span class="group-hover:underline underline-offset-4 decoration-[var(--cyan)]">Urgency Radar</span>
                </a>
                <a href="#thread-synthesis" class="text-[#B7C2D6] hover:text-[var(--cyan)] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-[var(--cyan)] font-bold">03/</span>
                    <span class="group-hover:underline underline-offset-4 decoration-[var(--cyan)]">Claude Synthesis</span>
                </a>
                <a href="#human-gateway" class="text-[#B7C2D6] hover:text-[var(--cyan)] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-[var(--cyan)] font-bold">04/</span>
                    <span class="group-hover:underline underline-offset-4 decoration-[var(--cyan)]">Human Gateway</span>
                </a>
                <a href="#faq" class="text-[#B7C2D6] hover:text-[var(--cyan)] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-[var(--cyan)] font-bold">05/</span>
                    <span class="group-hover:underline underline-offset-4 decoration-[var(--cyan)]">FAQ</span>
                </a>
            </nav>

            <!-- Vaultline Actions Bar -->
            <div class="hidden sm:flex items-center gap-3 mono text-xs">
                <a href="{{ route('dev.login') }}" class="app-launch violet">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Sandbox</span>
                </a>
                <a href="#early-access" class="app-launch">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5l7 7-7 7M3 12h18"/></svg>
                    <span>Priority Access</span>
                </a>
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex lg:hidden">
                <button type="button" id="mobile-menu-btn" aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle Navigation" class="p-2.5 text-[#B7C2D6] hover:text-[#EAF1F7] rounded-lg bg-white/5 border border-white/10">
                    <svg id="hamburger-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg id="close-icon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>

        <!-- Mobile Drawer -->
        <div id="mobile-menu" class="hidden lg:hidden py-4 border-t border-white/10 flex-col gap-2 mono text-xs">
            <a href="#interactive-workbench" class="mobile-nav-link px-3 py-2 text-[#B7C2D6] hover:text-[var(--cyan)] hover:bg-white/5 rounded-lg">01/ Live Workbench</a>
            <a href="#triage-system" class="mobile-nav-link px-3 py-2 text-[#B7C2D6] hover:text-[var(--cyan)] hover:bg-white/5 rounded-lg">02/ Urgency Radar</a>
            <a href="#thread-synthesis" class="mobile-nav-link px-3 py-2 text-[#B7C2D6] hover:text-[var(--cyan)] hover:bg-white/5 rounded-lg">03/ Claude Synthesis</a>
            <a href="#human-gateway" class="mobile-nav-link px-3 py-2 text-[#B7C2D6] hover:text-[var(--cyan)] hover:bg-white/5 rounded-lg">04/ Human Gateway</a>
            <a href="#faq" class="mobile-nav-link px-3 py-2 text-[#B7C2D6] hover:text-[var(--cyan)] hover:bg-white/5 rounded-lg">05/ FAQ</a>
            <div class="pt-3 mt-2 border-t border-white/10 flex flex-col gap-2">
                <a href="{{ route('dev.login') }}" class="mobile-nav-link flex items-center justify-center gap-2 w-full py-2.5 font-semibold text-amber-300 bg-amber-950/40 border border-amber-500/30 rounded-lg">
                    ⚡ Sandbox Access
                </a>
                <a href="#early-access" class="mobile-nav-link flex items-center justify-center gap-2 w-full py-2.5 font-bold text-[#090C14] bg-[var(--cyan)] rounded-lg shadow-lg">
                    Priority Enrollment ↗
                </a>
            </div>
        </div>
    </div>
</header>
