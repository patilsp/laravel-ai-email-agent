<section class="hero relative pt-8 pb-20 sm:pt-14 sm:pb-28 overflow-hidden" aria-labelledby="hero-heading">
    
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            
            <!-- Left Column: Vaultline Typography & Quick Search -->
            <div class="lg:col-span-5 space-y-6 text-left">
                
                <!-- Vaultline Eyebrow -->
                <div class="eyebrow" id="eyebrow">
                    <span class="line"></span>
                    <span>MILO v2.4 · CLAUDE 3.5 SONNET · INBOX TRIAGE</span>
                </div>

                <!-- Main Vaultline Headline -->
                <h1 id="hero-heading" class="text-3xl sm:text-5xl lg:text-[3.5rem] font-extrabold text-[#EAF1F7] tracking-tight leading-[1.12]">
                    One agent, every email. <br>
                    <span class="accent">Tap a card to unlock & send.</span>
                </h1>

                <!-- Subtitle -->
                <p class="hero-sub text-[#B7C2D6] text-sm sm:text-base leading-relaxed max-w-lg" id="hero-sub">
                    A single autonomous intelligence point for founders and busy executives — Milo triages urgent threads, extracts critical deadlines, and prepares context-rich replies for instant 1-click dispatch.
                </p>

                <!-- Vaultline Cyber Search / Filter Bar -->
                <div class="search-bar" id="hero-search-bar">
                    <span class="search-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </span>
                    <input type="text" id="heroQuickSearch" placeholder="Search unread emails, priority scores, or tags..." oninput="document.getElementById('interactive-workbench')?.scrollIntoView({behavior: 'smooth'})">
                </div>

                <!-- Actions Ribbon -->
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a href="#interactive-workbench" class="app-launch py-2.5 px-5 text-xs font-bold">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5l7 7-7 7M3 12h18"/></svg>
                        <span>Explore Live Workbench</span>
                    </a>
                    <a href="{{ route('dev.login') }}" class="app-launch violet py-2.5 px-5 text-xs font-bold">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Launch Sandbox</span>
                    </a>
                </div>

                <!-- Quick Metric Indicators -->
                <div class="pt-5 border-t border-white/10 grid grid-cols-3 gap-3 mono text-xs">
                    <div class="p-2.5 rounded-lg bg-white/5 border border-white/5">
                        <div class="text-[var(--cyan)] font-bold text-base">0.8s</div>
                        <div class="text-[#7C8AA0] text-[10.5px]">Triage Speed</div>
                    </div>
                    <div class="p-2.5 rounded-lg bg-white/5 border border-white/5">
                        <div class="text-[var(--violet)] font-bold text-base">99.4%</div>
                        <div class="text-[#7C8AA0] text-[10.5px]">Intent Accuracy</div>
                    </div>
                    <div class="p-2.5 rounded-lg bg-white/5 border border-white/5">
                        <div class="text-[var(--amber)] font-bold text-base">100%</div>
                        <div class="text-[#7C8AA0] text-[10.5px]">Human Verified</div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Vaultline 3D Card Scene -->
            <div class="lg:col-span-7 relative">
                
                <!-- Floating Luminous Chips -->
                <div class="hidden sm:flex absolute -top-5 -right-3 z-20 items-center gap-2 px-3 py-1.5 rounded-full bg-[#10162A] border border-[var(--cyan)]/40 text-[var(--cyan)] text-[10.5px] mono shadow-xl shadow-cyan-950/40">
                    <span class="dot"></span>
                    <span>⭐ Urgent Escalation (3 PM Due)</span>
                </div>

                <div class="hidden sm:flex absolute -bottom-5 -left-3 z-20 items-center gap-2 px-3 py-1.5 rounded-full bg-[#10162A] border border-[var(--violet)]/40 text-[var(--violet)] text-[10.5px] mono shadow-xl shadow-purple-950/40">
                    <span class="w-2 h-2 rounded-full bg-[var(--violet)]"></span>
                    <span>✓ Ready for 1-Click Send</span>
                </div>

                <!-- 3D Role / Triage Console Box -->
                <div class="role-card" id="heroLiveConsole">
                    <div class="role-inner space-y-4">
                        
                        <!-- Console Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-white/10 text-xs mono">
                            <div class="flex items-center gap-2.5">
                                <div class="brand-badge w-7 h-7">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2DE3C8" stroke-width="2.5"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
                                </div>
                                <span class="text-[#EAF1F7] font-bold">milo.engine / stream-01</span>
                            </div>
                            
                            <div class="sso-pill py-0.5 px-2">
                                <span class="dot"></span>
                                CLAUDE 3.5 ACTIVE
                            </div>
                        </div>

                        <!-- Active Triage Message Card -->
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-sm text-[#EAF1F7]">Sarah Jenkins</span>
                                        <span class="mono text-[11px] text-[#7C8AA0]">&lt;sarah@enterprise.io&gt;</span>
                                    </div>
                                    <div class="text-xs font-semibold text-[var(--cyan)] mt-0.5">
                                        Q4 Production Security Addendum & Board Sign-off
                                    </div>
                                </div>
                                <span class="status-chip red">
                                    Urgent // Due 3 PM
                                </span>
                            </div>

                            <!-- Credential / Email Metadata Row with Laser Scan -->
                            <div class="cred-row font-sans">
                                <div class="cred-line text-xs italic text-[#B7C2D6]">
                                    "Our committee requires the finalized security penetration report by 3:00 PM today to sign off on Friday's deployment."
                                </div>
                                <div class="cred-line text-[11px] mono text-[#7C8AA0] border-t border-white/5 pt-2 mt-2">
                                    <span>INTENT: SOC2 Compliance Sign-off</span>
                                    <span class="text-[var(--cyan)]">Score: 9.8/10</span>
                                </div>
                            </div>

                            <!-- Milo Draft Reply Box -->
                            <div class="p-3.5 rounded-xl bg-gradient-to-br from-[rgba(45,227,200,0.08)] via-[#0C1120] to-[rgba(139,124,245,0.06)] border border-[rgba(45,227,200,0.3)] space-y-2">
                                <div class="flex items-center justify-between mono text-[11px]">
                                    <span class="text-[var(--cyan)] font-bold uppercase flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--cyan)]"></span>
                                        Milo Suggested Reply
                                    </span>
                                    <span class="status-chip amber text-[10px]">
                                        Awaiting Approval
                                    </span>
                                </div>
                                <p class="text-xs text-[#EAF1F7] leading-relaxed font-sans">
                                    "Hi Sarah, thanks for following up. I've attached our finalized SOC2 Type II compliance audit and the signed penetration report. Please let me know if the committee needs any additional clarification ahead of Friday's rollout."
                                </p>
                            </div>

                            <!-- Action Bar -->
                            <div class="pt-1 flex items-center justify-between mono text-xs">
                                <span class="text-[10px] text-[#7C8AA0]">100% Semi-Autonomous Gate</span>
                                <div class="flex items-center gap-2">
                                    <a href="#interactive-workbench" class="app-launch py-1.5 px-3.5 text-xs">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5l7 7-7 7M3 12h18"/></svg>
                                        Approve & Send
                                    </a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
