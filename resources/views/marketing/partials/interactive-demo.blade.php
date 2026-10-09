<section id="interactive-workbench" class="py-20 sm:py-28 lg:py-32 border-b border-[#e7e5df] bg-[#121417] text-[#fbfaf7]" aria-labelledby="workbench-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-stone-800">
            <div class="space-y-3">
                <div class="font-mono-tech text-xs text-blue-400 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span>01/ Interactive Product Demonstration</span>
                </div>
                <h2 id="workbench-heading" class="font-sans font-extrabold text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight">
                    Watch an inbox <span class="font-serif-hero italic font-normal text-blue-400">become a plan.</span>
                </h2>
            </div>
            <p class="text-sm text-stone-400 max-w-md font-sans leading-relaxed">
                Switch between three live scenarios below to observe how the AI agent structures incoming messages and prepares human-verified drafts.
            </p>
        </div>

        <!-- 3-Column Interactive Pro Workbench Container -->
        <div class="bg-[#181c22] rounded-xl border border-stone-800 shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-stone-800 font-mono-tech">
            
            <!-- Column 1: Inbox Stream Selector (3 Cols) -->
            <div class="lg:col-span-3 p-4 space-y-3 bg-[#14171d]">
                <div class="flex items-center justify-between text-[11px] text-stone-400 pb-2 border-b border-stone-800">
                    <span>INCOMING STACK</span>
                    <span>3 UNREAD</span>
                </div>

                <!-- Scenario 1 Button (Client Request) -->
                <button type="button" class="demo-scenario-btn w-full text-left p-3 rounded-lg border transition-all space-y-1 group bg-[#1e232c] border-blue-500/80 text-white" data-scenario="client">
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-bold text-stone-300 group-hover:text-blue-400">Sarah Jenkins</span>
                        <span class="text-stone-500">10:14 AM</span>
                    </div>
                    <div class="text-xs font-semibold text-stone-200 truncate">Q4 Security Audit Approval</div>
                    <div class="flex items-center justify-between pt-1 text-[10px]">
                        <span class="px-1.5 py-0.5 rounded bg-rose-950 text-rose-300 border border-rose-800 uppercase font-bold">Urgent</span>
                        <span class="text-stone-500">Client</span>
                    </div>
                </button>

                <!-- Scenario 2 Button (Meeting Follow-up) -->
                <button type="button" class="demo-scenario-btn w-full text-left p-3 rounded-lg border border-stone-800 hover:border-stone-700 bg-transparent hover:bg-[#1a1e26] text-stone-300 transition-all space-y-1 group" data-scenario="meeting">
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-bold text-stone-300 group-hover:text-blue-400">David Chen</span>
                        <span class="text-stone-500">11:30 AM</span>
                    </div>
                    <div class="text-xs font-semibold text-stone-200 truncate">Architecture Sync & API v2</div>
                    <div class="flex items-center justify-between pt-1 text-[10px]">
                        <span class="px-1.5 py-0.5 rounded bg-amber-950 text-amber-300 border border-amber-800 uppercase font-bold">Important</span>
                        <span class="text-stone-500">DevOps</span>
                    </div>
                </button>

                <!-- Scenario 3 Button (Invoice Received) -->
                <button type="button" class="demo-scenario-btn w-full text-left p-3 rounded-lg border border-stone-800 hover:border-stone-700 bg-transparent hover:bg-[#1a1e26] text-stone-300 transition-all space-y-1 group" data-scenario="invoice">
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-bold text-stone-300 group-hover:text-blue-400">Stripe Billing</span>
                        <span class="text-stone-500">08:45 AM</span>
                    </div>
                    <div class="text-xs font-semibold text-stone-200 truncate">Invoice #INV-2026-9482 ($1,250)</div>
                    <div class="flex items-center justify-between pt-1 text-[10px]">
                        <span class="px-1.5 py-0.5 rounded bg-stone-800 text-stone-400 border border-stone-700 uppercase font-bold">Routine</span>
                        <span class="text-stone-500">Finance</span>
                    </div>
                </button>
            </div>

            <!-- Column 2: Full Email Thread Reader (4 Cols) -->
            <div class="lg:col-span-4 p-5 sm:p-6 space-y-4 bg-[#181c22] font-sans flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between font-mono-tech text-[11px] text-stone-400 pb-3 border-b border-stone-800">
                        <span>ORIGINAL MESSAGE</span>
                        <span id="demo-sender-time" class="text-stone-500">10:14 AM</span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-white" id="demo-sender-name">Sarah Jenkins</span>
                            <span class="font-mono-tech text-xs text-stone-400" id="demo-sender-email">&lt;sarah@enterprise.io&gt;</span>
                        </div>
                        <h3 class="text-sm font-semibold text-stone-200" id="demo-subject">
                            Urgent: Q4 Production Rollout Approval & Security Audit
                        </h3>
                    </div>

                    <div class="p-4 rounded-lg bg-[#121417] border border-stone-800 text-xs sm:text-sm text-stone-300 leading-relaxed font-sans" id="demo-body">
                        "Hi team, our executive committee requires the finalized security penetration test report by 3:00 PM today to sign off on Friday's production deployment. Could you share the signed compliance copy?"
                    </div>
                </div>

                <div class="p-3.5 rounded-lg bg-[#14171d] border border-stone-800 font-mono-tech text-xs space-y-2">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-stone-400">Urgency Gauge:</span>
                        <span id="demo-priority-badge" class="px-2 py-0.5 rounded font-bold uppercase text-[10px] bg-rose-950 text-rose-300 border border-rose-800">
                            Urgent (9.8 / 10)
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-stone-400">Assigned Label:</span>
                        <span id="demo-category-badge" class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-950 text-blue-300 border border-blue-800">
                            🏷️ Enterprise Client
                        </span>
                    </div>
                </div>
            </div>

            <!-- Column 3: AI Synthesis & Human Action Studio (5 Cols) -->
            <div class="lg:col-span-5 p-5 sm:p-6 space-y-4 bg-[#14171d] flex flex-col justify-between">
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-[11px] text-stone-400 pb-3 border-b border-stone-800">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                            <span class="text-blue-400 font-bold uppercase">Claude 3.5 Synthesis Engine</span>
                        </div>
                        <span class="text-stone-500">Latency: 1.1s</span>
                    </div>

                    <!-- AI Takeaway -->
                    <div class="p-3.5 rounded-lg bg-[#181c22] border border-stone-800 text-xs space-y-1.5 font-sans">
                        <div class="flex items-center justify-between font-mono-tech text-[10px] text-stone-400">
                            <span class="font-bold text-stone-300">[EXECUTIVE BRIEFING]</span>
                            <span id="demo-intent-tag" class="text-blue-400">Intent: Security Addendum</span>
                        </div>
                        <p class="text-xs text-stone-300 leading-relaxed" id="demo-summary">
                            Sarah needs the signed penetration test report prior to 3:00 PM for the executive board sign-off. High commercial impact.
                        </p>
                    </div>

                    <!-- Editable Proposed Draft -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-[11px] text-stone-400">
                            <span class="font-bold text-white uppercase font-mono-tech">Proposed Draft Response</span>
                            <span class="text-amber-400 font-mono-tech text-[10px]">Awaiting Human Approval</span>
                        </div>

                        <div class="relative">
                            <textarea id="demo-draft-textarea" rows="4" class="w-full p-3.5 text-xs sm:text-sm text-stone-200 bg-[#121417] border border-stone-700 rounded-lg focus:outline-none focus:border-blue-500 font-sans leading-relaxed resize-none transition-all workbench-scroll">"Hi Sarah, thanks for reaching out. I've attached our completed SOC2 Type II compliance audit and the signed security report. Please let us know if any further clarifications are needed ahead of Friday's deployment."</textarea>
                            
                            <!-- Success Approval Overlay -->
                            <div id="demo-approved-overlay" class="hidden absolute inset-0 bg-[#0d0f12]/95 text-white rounded-lg flex flex-col items-center justify-center p-4 text-center space-y-2 animate-fade-in border border-emerald-500/50">
                                <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-400/40 flex items-center justify-center text-base font-bold font-mono-tech">
                                    ✓
                                </div>
                                <span class="font-bold text-xs text-white font-mono-tech uppercase">Draft Approved & Synchronized</span>
                                <p class="text-[11px] text-stone-400 max-w-xs font-sans">
                                    Simulated action dispatched. In production, this syncs with your Gmail Drafts / API.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Toolbar -->
                <div class="pt-4 border-t border-stone-800 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <button type="button" id="demo-reset-btn" class="px-3 py-1.5 text-stone-400 hover:text-white bg-stone-900 hover:bg-stone-800 rounded border border-stone-800 transition-colors">
                            Reset
                        </button>
                        <button type="button" id="demo-edit-btn" class="px-3 py-1.5 text-blue-300 hover:text-blue-200 bg-blue-950/80 hover:bg-blue-900 border border-blue-800 rounded transition-colors">
                            Edit Text
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" id="demo-approve-btn" class="px-4 py-2 font-bold text-white bg-blue-600 hover:bg-blue-500 active:scale-98 rounded transition-all flex items-center gap-1.5 shadow-sm">
                            <span>✓ Approve & Send</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>
