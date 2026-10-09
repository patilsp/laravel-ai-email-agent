<section id="interactive-workbench" class="py-16 sm:py-24 border-t border-b border-white/10 relative overflow-hidden" aria-labelledby="workbench-heading">
    
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Vaultline Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-white/10">
            <div class="space-y-3">
                <div class="eyebrow">
                    <span class="line"></span>
                    <span>01/ INTERACTIVE PRODUCT BENCH</span>
                </div>
                <h2 id="workbench-heading" class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-[#EAF1F7] tracking-tight">
                    Watch an inbox <span class="accent">become a clear plan.</span>
                </h2>
            </div>
            <p class="text-sm text-[#B7C2D6] max-w-md leading-relaxed">
                Test drive <strong class="text-[#EAF1F7]">Milo</strong> in real-time. Switch scenarios and tone modes to observe Claude 3.5 Sonnet's cognitive synthesis and instant draft generation.
            </p>
        </div>

        <!-- 3-Column Vaultline Workbench -->
        <div id="demo-workbench-pane" class="glass-panel rounded-2xl border border-white/10 shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-white/10 font-sans relative">
            
            <!-- Column 1: Incoming Stack (3 Cols) -->
            <div class="lg:col-span-3 p-5 space-y-3 bg-[#090C14]/80 mono">
                <div class="flex items-center justify-between text-[11px] text-[#7C8AA0] pb-2 border-b border-white/10">
                    <span class="font-bold text-[#EAF1F7] uppercase tracking-wider">INCOMING STACK</span>
                    <span class="status-chip">3 UNREAD</span>
                </div>

                <!-- Scenario 1 Button (Client Request) -->
                <button type="button" class="demo-scenario-btn w-full text-left p-3.5 rounded-xl border transition-all space-y-1.5 group border-[rgba(45,227,200,0.4)] bg-[rgba(45,227,200,0.1)] text-[#EAF1F7] cursor-pointer" data-scenario="client">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-[#EAF1F7] group-hover:text-[var(--cyan)]">Sarah Jenkins</span>
                        <span class="text-[#7C8AA0]">10:14 AM</span>
                    </div>
                    <div class="text-xs font-semibold text-[#B7C2D6] truncate">Q4 Security Audit Approval</div>
                    <div class="flex items-center justify-between pt-1 text-[10px]">
                        <span class="status-chip red">Urgent</span>
                        <span class="text-[var(--cyan)]">Enterprise Client</span>
                    </div>
                </button>

                <!-- Scenario 2 Button (Meeting Follow-up) -->
                <button type="button" class="demo-scenario-btn w-full text-left p-3.5 rounded-xl border border-white/10 hover:border-white/20 bg-white/5 text-[#B7C2D6] transition-all space-y-1.5 group cursor-pointer" data-scenario="meeting">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-[#B7C2D6] group-hover:text-[var(--cyan)]">David Chen</span>
                        <span class="text-[#7C8AA0]">11:30 AM</span>
                    </div>
                    <div class="text-xs font-semibold text-[#B7C2D6] truncate">Architecture Sync & API v2</div>
                    <div class="flex items-center justify-between pt-1 text-[10px]">
                        <span class="status-chip amber">Important</span>
                        <span class="text-[var(--violet)]">Engineering</span>
                    </div>
                </button>

                <!-- Scenario 3 Button (Invoice Received) -->
                <button type="button" class="demo-scenario-btn w-full text-left p-3.5 rounded-xl border border-white/10 hover:border-white/20 bg-white/5 text-[#B7C2D6] transition-all space-y-1.5 group cursor-pointer" data-scenario="invoice">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-[#B7C2D6] group-hover:text-[var(--cyan)]">Stripe Billing</span>
                        <span class="text-[#7C8AA0]">08:45 AM</span>
                    </div>
                    <div class="text-xs font-semibold text-[#B7C2D6] truncate">Invoice #INV-2026-9482 ($1,250)</div>
                    <div class="flex items-center justify-between pt-1 text-[10px]">
                        <span class="status-chip">Routine</span>
                        <span class="text-[var(--cyan)]">Finance</span>
                    </div>
                </button>

                <div class="pt-3 border-t border-white/10 text-[10.5px] text-[#7C8AA0] leading-relaxed">
                    💡 Click any message to trigger real-time AI context parsing and response modeling.
                </div>
            </div>

            <!-- Column 2: Deep Context & Email Thread Reader (4 Cols) -->
            <div class="lg:col-span-4 p-5 sm:p-6 space-y-4 bg-[#10162A]/90 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between mono text-[11px] text-[#7C8AA0] pb-3 border-b border-white/10">
                        <span class="text-[#EAF1F7] font-bold uppercase tracking-wider">SELECTED THREAD</span>
                        <span id="demo-sender-time" class="text-[#7C8AA0]">10:14 AM</span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-[#EAF1F7]" id="demo-sender-name">Sarah Jenkins</span>
                                <span class="mono text-xs text-[#7C8AA0]" id="demo-sender-email">&lt;sarah@enterprise.io&gt;</span>
                            </div>
                        </div>
                        <div class="text-xs font-medium text-[var(--cyan)] mono" id="demo-sender-role">VP of Enterprise Security</div>
                        <h3 class="text-sm font-semibold text-[#EAF1F7] pt-1" id="demo-subject">
                            Urgent: Q4 Production Rollout Approval & Security Audit
                        </h3>
                    </div>

                    <div class="p-4 rounded-xl bg-[#090C14] border border-white/10 text-xs sm:text-sm text-[#B7C2D6] leading-relaxed" id="demo-body">
                        "Hi team, our executive committee requires the finalized security penetration test report by 3:00 PM today to sign off on Friday's production deployment. Could you share the signed compliance copy?"
                    </div>
                </div>

                <!-- Live Urgency & Sentiment Radar -->
                <div class="cred-row mono text-xs space-y-2">
                    <div class="cred-line text-[11px]">
                        <span class="text-[#7C8AA0]">Urgency Gauge:</span>
                        <span id="demo-priority-badge" class="status-chip red">
                            Urgent (9.8 / 10)
                        </span>
                    </div>
                    <div class="cred-line text-[11px]">
                        <span class="text-[#7C8AA0]">Assigned Label:</span>
                        <span id="demo-category-badge" class="status-chip violet">
                            🏷️ Enterprise Client
                        </span>
                    </div>
                    <div class="cred-line text-[11px]">
                        <span class="text-[#7C8AA0]">Detected Deadline:</span>
                        <span id="demo-deadline-tag" class="text-[var(--cyan)] font-bold">Today at 3:00 PM EST</span>
                    </div>
                </div>
            </div>

            <!-- Column 3: Milo Dynamic Synthesis & Tone Morpher (5 Cols) -->
            <div class="lg:col-span-5 p-5 sm:p-6 space-y-4 bg-[#090C14]/95 flex flex-col justify-between">
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-[11px] text-[#7C8AA0] pb-3 border-b border-white/10 mono">
                        <div class="flex items-center gap-2">
                            <span class="dot"></span>
                            <span class="text-[#EAF1F7] font-bold uppercase tracking-wider">Milo AI Synthesis</span>
                        </div>
                        <span class="text-[var(--cyan)] font-bold">Latency: 0.8s</span>
                    </div>

                    <!-- Executive Briefing Card -->
                    <div class="p-3.5 rounded-xl bg-[#10162A] border border-white/10 text-xs space-y-1.5">
                        <div class="flex items-center justify-between mono text-[10.5px] text-[#7C8AA0]">
                            <span class="font-bold text-[var(--cyan)]">[EXECUTIVE BRIEFING]</span>
                            <span id="demo-intent-tag" class="text-[var(--violet)] font-bold">Security Report & Deadline</span>
                        </div>
                        <p class="text-xs text-[#B7C2D6] leading-relaxed" id="demo-summary">
                            Sarah needs the signed penetration test report prior to 3:00 PM for the executive board sign-off. High commercial impact.
                        </p>
                    </div>

                    <!-- Dynamic Tone Selector Pills -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-[11px] text-[#7C8AA0] mono">
                            <span class="font-bold text-[#EAF1F7] uppercase">Tone Engine:</span>
                            <span id="demo-typing-indicator" class="hidden text-[var(--cyan)] flex items-center gap-1 font-bold animate-pulse">
                                <span>Generating draft...</span>
                            </span>
                        </div>
                        <div class="grid grid-cols-4 gap-1.5 text-[11px] mono">
                            <button type="button" class="demo-tone-btn py-1.5 px-2 rounded-lg bg-[var(--cyan)] text-[#090C14] font-bold transition-all text-center cursor-pointer" data-tone="executive">
                                Executive
                            </button>
                            <button type="button" class="demo-tone-btn py-1.5 px-2 rounded-lg bg-white/5 hover:bg-white/10 text-stone-300 hover:text-white transition-all text-center cursor-pointer" data-tone="concise">
                                Concise
                            </button>
                            <button type="button" class="demo-tone-btn py-1.5 px-2 rounded-lg bg-white/5 hover:bg-white/10 text-stone-300 hover:text-white transition-all text-center cursor-pointer" data-tone="empathetic">
                                Warm
                            </button>
                            <button type="button" class="demo-tone-btn py-1.5 px-2 rounded-lg bg-white/5 hover:bg-white/10 text-stone-300 hover:text-white transition-all text-center cursor-pointer" data-tone="bullet">
                                Bullets
                            </button>
                        </div>
                    </div>

                    <!-- Editable Proposed Response Area -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-[11px] text-[#7C8AA0] mono">
                            <span class="font-bold text-[#EAF1F7] uppercase">Proposed Reply Draft</span>
                            <span class="status-chip amber text-[10px]">
                                Human Approval Gateway
                            </span>
                        </div>

                        <div class="relative">
                            <textarea id="demo-draft-textarea" rows="4" class="w-full p-4 text-xs sm:text-sm text-[#EAF1F7] bg-[#090C14] border border-white/15 rounded-xl focus:outline-none focus:border-[var(--cyan)] leading-relaxed resize-none transition-all workbench-scroll shadow-inner">Hi Sarah,

Thanks for following up. I've attached our finalized SOC2 Type II compliance audit and the signed security penetration report.

Please let me know if the committee needs any additional clarification ahead of Friday's rollout.

Best regards,
Alex</textarea>
                            
                            <!-- Success Approval Overlay -->
                            <div id="demo-approved-overlay" class="hidden absolute inset-0 bg-[#090C14]/95 text-white rounded-xl flex flex-col items-center justify-center p-5 text-center space-y-2.5 animate-fade-in border border-[var(--cyan)]/50 backdrop-blur-md">
                                <div class="w-10 h-10 rounded-full bg-[var(--cyan)]/20 text-[var(--cyan)] border border-[var(--cyan)]/40 flex items-center justify-center text-lg font-bold mono shadow-lg shadow-cyan-500/30">
                                    ✓
                                </div>
                                <span class="font-bold text-sm text-[#EAF1F7] mono uppercase">Draft Approved & Dispatched</span>
                                <p class="text-[11px] text-[#7C8AA0] max-w-xs">
                                    Simulated email successfully queued for Gmail dispatch with strict OAuth verification telemetry.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Toolbar -->
                <div class="pt-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-3 text-xs mono">
                    <div class="flex items-center gap-2">
                        <button type="button" id="demo-reset-btn" class="px-3.5 py-2 text-[#7C8AA0] hover:text-white bg-white/5 hover:bg-white/10 rounded-lg border border-white/10 transition-colors cursor-pointer">
                            Reset
                        </button>
                        <button type="button" id="demo-edit-btn" class="px-3.5 py-2 text-[var(--cyan)] hover:text-white bg-[rgba(45,227,200,0.1)] hover:bg-[rgba(45,227,200,0.2)] border border-[rgba(45,227,200,0.3)] rounded-lg transition-colors cursor-pointer">
                            Edit Text
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" id="demo-approve-btn" class="app-launch py-2 px-4.5 font-bold cursor-pointer">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5l7 7-7 7M3 12h18"/></svg>
                            <span>Approve & Send</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>
