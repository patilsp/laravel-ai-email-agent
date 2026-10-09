<!-- Section A: Priority Without The Noise -->
<section id="triage-system" class="py-20 sm:py-28 border-b border-[#e7e5df] bg-[#fbfaf7]" aria-labelledby="priority-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-5 space-y-6">
                <div class="font-mono-tech text-xs text-stone-500 uppercase tracking-widest flex items-center gap-2">
                    <span class="text-blue-600 font-bold">02/</span>
                    <span>Triage Intelligence</span>
                </div>
                <h2 id="priority-heading" class="font-sans font-extrabold text-3xl sm:text-5xl text-[#121417] tracking-tight leading-[1.1]">
                    Know what needs<br />
                    <span class="font-serif-hero italic font-normal text-blue-600">you first.</span>
                </h2>
                <p class="text-base text-stone-600 leading-relaxed font-normal">
                    Conventional inboxes treat critical client escalations with the same visual weight as routine newsletters. Our triage engine parses deadlines, sender hierarchy, and urgency sentiment to surface what matters right at the top.
                </p>
                <div class="pt-2 font-mono-tech text-xs space-y-2 text-stone-700">
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-bold">✓</span>
                        <span>Zero arbitrary badge clutter — strict hierarchical sorting</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-bold">✓</span>
                        <span>Direct synchronization with Gmail stars & priority labels</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="bg-white rounded-xl border border-stone-300 p-5 sm:p-7 shadow-sm space-y-3 font-sans">
                    <div class="flex items-center justify-between text-xs font-mono-tech pb-3 border-b border-stone-200">
                        <span class="text-stone-500">TRIAGE HIERARCHY DEMO</span>
                        <span class="text-stone-400">3 of 48 unread</span>
                    </div>

                    <!-- Level 1: Urgent -->
                    <div class="p-4 rounded-lg bg-[#faf9f6] border-l-4 border-l-rose-600 border-stone-200 border flex items-start justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-[#121417]">VP of Infrastructure</span>
                                <span class="font-mono-tech text-[10px] text-rose-700 bg-rose-50 border border-rose-200 px-1.5 py-0.5 rounded uppercase font-bold">Urgent</span>
                            </div>
                            <div class="text-xs font-medium text-stone-800">Production DB read latency spike incident report</div>
                        </div>
                        <span class="font-mono-tech text-[11px] text-stone-400 whitespace-nowrap">2m ago</span>
                    </div>

                    <!-- Level 2: Important -->
                    <div class="p-4 rounded-lg bg-white border-l-4 border-l-amber-500 border-stone-200 border flex items-start justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-[#121417]">Acme Partner Counsel</span>
                                <span class="font-mono-accent text-[10px] text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded uppercase font-bold">Important</span>
                            </div>
                            <div class="text-xs font-medium text-stone-800">Revised SLA agreement comments & redlines</div>
                        </div>
                        <span class="font-mono-tech text-[11px] text-stone-400 whitespace-nowrap">18m ago</span>
                    </div>

                    <!-- Level 3: Routine -->
                    <div class="p-4 rounded-lg bg-white border-l-4 border-l-stone-300 border-stone-200 border flex items-start justify-between gap-3 opacity-70">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-stone-700">Substack Engineering Digest</span>
                                <span class="font-mono-tech text-[10px] text-stone-600 bg-stone-100 border border-stone-200 px-1.5 py-0.5 rounded uppercase">Newsletter</span>
                            </div>
                            <div class="text-xs text-stone-600">Weekly Tech & Architecture round-up edition #104</div>
                        </div>
                        <span class="font-mono-tech text-[11px] text-stone-400 whitespace-nowrap">1h ago</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Section B: Context Before You Respond -->
<section id="thread-synthesis" class="py-20 sm:py-28 border-b border-[#e7e5df] bg-white" aria-labelledby="context-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-7 order-2 lg:order-1">
                <div class="bg-[#fbfaf7] rounded-xl border border-stone-300 p-6 shadow-sm space-y-4 font-mono-tech text-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                        <span class="text-stone-500 font-bold uppercase">Thread Synthesis Matrix</span>
                        <span class="text-blue-600">7 Replies Evaluated</span>
                    </div>

                    <div class="space-y-3 font-sans text-xs">
                        <div class="p-4 bg-white rounded-lg border border-stone-200 space-y-1">
                            <span class="font-mono-tech text-[10px] text-stone-400 uppercase tracking-wider block">Decision Under Review</span>
                            <span class="font-bold text-stone-900 block text-sm">Choose between AWS Multi-Region vs Single Region Failover</span>
                            <p class="text-stone-500 text-xs mt-1">Lead Architect requires consensus before tomorrow's infrastructure sprint planning.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 font-mono-tech text-[11px]">
                            <div class="p-3.5 bg-white rounded-lg border border-stone-200">
                                <span class="text-stone-400 block text-[10px]">Deadline</span>
                                <span class="font-bold text-rose-700">Today, 5:00 PM EST</span>
                            </div>
                            <div class="p-3.5 bg-white rounded-lg border border-stone-200">
                                <span class="text-stone-400 block text-[10px]">Sentiment</span>
                                <span class="font-bold text-stone-800">Decisive / Constructive</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-6 order-1 lg:order-2">
                <div class="font-mono-tech text-xs text-stone-500 uppercase tracking-widest flex items-center gap-2">
                    <span class="text-blue-600 font-bold">03/</span>
                    <span>Synthesis Engine</span>
                </div>
                <h2 id="context-heading" class="font-sans font-extrabold text-3xl sm:text-5xl text-[#121417] tracking-tight leading-[1.1]">
                    Context, before<br />
                    <span class="font-serif-hero italic font-normal text-blue-600">you respond.</span>
                </h2>
                <p class="text-base text-stone-600 leading-relaxed font-normal">
                    Never spend 15 minutes skimming a long email chain just to figure out who asked for what. Claude provides an executive briefing of deadlines, open questions, and required decisions before you type a word.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Section C: A Thoughtful First Draft -->
<section class="py-20 sm:py-28 border-b border-[#e7e5df] bg-[#fbfaf7]" aria-labelledby="draft-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-5 space-y-6">
                <div class="font-mono-tech text-xs text-stone-500 uppercase tracking-widest flex items-center gap-2">
                    <span class="text-blue-600 font-bold">04/</span>
                    <span>Drafting Studio</span>
                </div>
                <h2 id="draft-heading" class="font-sans font-extrabold text-3xl sm:text-5xl text-[#121417] tracking-tight leading-[1.1]">
                    Start with a<br />
                    <span class="font-serif-hero italic font-normal text-blue-600">better answer.</span>
                </h2>
                <p class="text-base text-stone-600 leading-relaxed font-normal">
                    Instead of staring at a blank composer, you start with a polished, context-grounded response formulated to match professional communication standards. Edit individual sentences or approve the draft in seconds.
                </p>
                <div class="pt-2 font-mono-tech text-xs text-stone-500">
                    No robotic phrasing. Contextual, precise, and human.
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="bg-white rounded-xl border border-stone-300 p-6 sm:p-7 shadow-sm space-y-4">
                    <div class="flex items-center justify-between text-xs font-mono-tech pb-3 border-b border-stone-200">
                        <span class="text-stone-500">COMPOSED DRAFT VIEW</span>
                        <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded font-bold">Ready for Review</span>
                    </div>

                    <div class="space-y-3 font-sans">
                        <div class="text-xs text-stone-400 font-mono-tech">To: Elena Rostova &lt;elena@acmedesign.co&gt;</div>
                        <div class="p-5 rounded-lg bg-[#faf9f6] border border-stone-200 text-sm text-stone-800 font-serif-hero leading-relaxed italic">
                            "Hi Elena, thank you for sharing the updated design token tokens. I've reviewed the spacing scale and typography updates; everything aligns with our brand guidelines. Let's proceed with the component library release."
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Section D: Automation With A Human in Control -->
<section id="human-gateway" class="py-20 sm:py-28 border-b border-[#e7e5df] bg-white" aria-labelledby="safeguards-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-14">
        
        <div class="max-w-3xl space-y-4">
            <div class="font-mono-tech text-xs text-stone-500 uppercase tracking-widest flex items-center gap-2">
                <span class="text-blue-600 font-bold">05/</span>
                <span>Semi-Autonomous Safeguards</span>
            </div>
            <h2 id="safeguards-heading" class="font-sans font-extrabold text-3xl sm:text-5xl text-[#121417] tracking-tight leading-[1.1]">
                Ready for you.<br />
                <span class="font-serif-hero italic font-normal text-blue-600">Never beyond you.</span>
            </h2>
            <p class="text-base text-stone-600 leading-relaxed max-w-2xl font-normal">
                Autonomous AI that sends emails blindly creates severe liability. Our core architectural rule is simple: AI prepares the intelligence; you approve the outgoing action.
            </p>
        </div>

        <!-- Sequential Pipeline Visualizer -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 font-mono-tech text-xs">
            <div class="p-6 rounded-xl bg-[#fbfaf7] border border-stone-200 space-y-2">
                <div class="text-stone-400 font-bold">STAGE 01</div>
                <div class="font-bold text-stone-900 font-sans text-sm">Email Received</div>
                <p class="text-xs text-stone-500 font-sans">Synced securely via Gmail OAuth token polling.</p>
            </div>
            <div class="p-6 rounded-xl bg-[#fbfaf7] border border-stone-200 space-y-2">
                <div class="text-stone-400 font-bold">STAGE 02</div>
                <div class="font-bold text-stone-900 font-sans text-sm">AI Analysis</div>
                <p class="text-xs text-stone-500 font-sans">Claude extracts intent, sentiment, deadlines, and labels.</p>
            </div>
            <div class="p-6 rounded-xl bg-[#fbfaf7] border border-stone-200 space-y-2">
                <div class="text-stone-400 font-bold">STAGE 03</div>
                <div class="font-bold text-stone-900 font-sans text-sm">Suggested Draft</div>
                <p class="text-xs text-stone-500 font-sans">Formulates personalized response draft in your voice.</p>
            </div>
            <div class="p-6 rounded-xl bg-[#121417] text-[#fbfaf7] border border-stone-800 space-y-2 shadow-md">
                <div class="text-blue-400 font-bold">STAGE 04 // GATEWAY</div>
                <div class="font-bold text-white font-sans text-sm">Your Approval</div>
                <p class="text-xs text-stone-300 font-sans">You click Approve, edit the draft, or discard.</p>
            </div>
        </div>

    </div>
</section>
