<footer class="border-t border-white/10 bg-[#090C14] py-12 text-xs mono text-[#7C8AA0] relative z-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-6 border-b border-white/10">
            <!-- Brand Mark -->
            <div class="flex items-center gap-3.5">
                <div class="brand-badge w-8 h-8">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2DE3C8" stroke-width="2.2"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
                </div>
                <div>
                    <span class="font-extrabold text-sm text-[#EAF1F7] tracking-tight uppercase font-sans">Milo</span>
                    <span class="text-[#4B5568] pl-2">|</span>
                    <span class="text-[#7C8AA0] pl-2">AI Agent to handle emails</span>
                </div>
            </div>

            <!-- Links -->
            <div class="flex flex-wrap items-center gap-6 text-[#7C8AA0] text-xs">
                <a href="#interactive-workbench" class="hover:text-[var(--cyan)] transition-colors">01. Workbench</a>
                <a href="#triage-system" class="hover:text-[var(--cyan)] transition-colors">02. Urgency Radar</a>
                <a href="#thread-synthesis" class="hover:text-[var(--cyan)] transition-colors">03. Claude Synthesis</a>
                <a href="#human-gateway" class="hover:text-[var(--cyan)] transition-colors">04. Safeguards</a>
                <a href="#faq" class="hover:text-[var(--cyan)] transition-colors">05. FAQ</a>
                <a href="{{ route('dev.login') }}" class="text-[var(--violet)] hover:text-white font-bold transition-colors">⚡ Sandbox Demo</a>
            </div>
        </div>

        <div class="footnote">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            <span><b>Zero autonomous liability.</b> Milo is engineered to prepare triage recommendations, thread summaries, and drafts while strictly requiring human verification before outbound dispatch.</span>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-[#4B5568] text-[11px] pt-2">
            <div class="flex items-center gap-2">
                <span class="dot"></span>
                <span>© {{ date('Y') }} Milo AI. Engineered on Laravel 12 & Anthropic Claude 3.5 Sonnet.</span>
            </div>
            <div class="flex items-center gap-4">
                <span>OAuth 2.0 Encrypted at Rest (AES-256)</span>
                <span>•</span>
                <a href="#early-access" class="text-[var(--cyan)] hover:text-white font-bold transition-colors">Priority Access</a>
            </div>
        </div>

    </div>
</footer>
