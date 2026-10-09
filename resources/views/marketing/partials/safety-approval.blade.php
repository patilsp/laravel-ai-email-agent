<section id="human-safety" class="py-20 sm:py-28 lg:py-32 bg-slate-900 text-white relative overflow-hidden" aria-labelledby="safety-heading">
    <!-- Ambient mesh lighting -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10 space-y-16">
        
        <!-- Header -->
        <div class="max-w-3xl mx-auto text-center space-y-4">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider">
                Human-in-the-Loop Architecture
            </span>
            <h2 id="safety-heading" class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight">
                AI does the preparation.<br />
                <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-indigo-300 bg-clip-text text-transparent">You make the decision.</span>
            </h2>
            <p class="text-base sm:text-lg text-slate-300 leading-relaxed">
                We believe fully autonomous email sending is risky for professional relationships. Our semi-automatic design ensures zero accidental sends and zero embarrassing miscommunications.
            </p>
        </div>

        <!-- Interactive Safety & Approval Deep Dive Mockup -->
        <div class="max-w-4xl mx-auto bg-slate-800/90 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/40 backdrop-blur-md space-y-6">
            
            <!-- Header bar of review card -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-700/70">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-400/30 flex items-center justify-center font-bold text-sm">
                        #42
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-base text-white">Pending Approval Queue</span>
                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-400/20 text-amber-300 border border-amber-400/30">
                                ⏳ Awaiting Decision
                            </span>
                        </div>
                        <span class="text-xs text-slate-400">Incoming from: Jessica Reed (Partner & Investor Relations)</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-medium">Confidence Score:</span>
                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-xs font-bold font-mono">99.1%</span>
                </div>
            </div>

            <!-- Email Context + Reasoning Matrix -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                
                <!-- Left Details Column (Incoming email + Claude Reasoning) -->
                <div class="md:col-span-6 space-y-4">
                    <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-700/70 space-y-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Incoming Message</span>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            "Hey Alex, could you send over the updated financial forecast for Q3 before our executive sync on Wednesday at 10 AM?"
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 space-y-2.5">
                        <span class="text-xs font-bold text-indigo-300 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 011.341 1.342l-.8 1.598 1.583 3.955A1 1 0 0118 12a1 1 0 01-1 1v1.323l-3.954 1.582-1.599.8a1 1 0 01-1.341-1.342l.8-1.598-1.583-3.955A1 1 0 0110 8V6.677L6.046 5.095l-1.599.8A1 1 0 013.106 4.553l.8-1.598L2.323 1.954A1 1 0 013 1h14a1 1 0 011 1z"/>
                            </svg>
                            Claude's Reasoning Breakdown
                        </span>
                        <div class="space-y-1.5 text-xs text-slate-300">
                            <div>• <strong>Intent:</strong> Financial report request with specific deadline.</div>
                            <div>• <strong>Sentiment:</strong> Polite, professional & time-sensitive.</div>
                            <div>• <strong>Suggested Label:</strong> <span class="text-amber-300">⭐ VIP / Board</span></div>
                        </div>
                    </div>
                </div>

                <!-- Right Details Column (Draft Reply Preview + Action Triggers) -->
                <div class="md:col-span-6 space-y-4">
                    <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-700/80 space-y-3">
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <span class="font-bold text-slate-200">Proposed Draft Reply</span>
                            <span class="text-[11px] text-indigo-300">Tone: Professional / Crisp</span>
                        </div>
                        <p class="text-xs text-slate-200 leading-relaxed italic bg-slate-950/60 p-3 rounded-xl border border-slate-800">
                            "Hi Jessica, thanks for checking in. I've attached the finalized Q3 financial forecast model. I'll see you at Wednesday's 10 AM sync."
                        </p>
                    </div>

                    <!-- Action Command Strip -->
                    <div class="p-3 bg-slate-900/60 rounded-2xl border border-slate-700/60 flex items-center justify-between gap-2">
                        <button type="button" class="px-3.5 py-2 text-xs font-semibold text-rose-300 hover:text-rose-200 bg-rose-950/50 hover:bg-rose-900/50 border border-rose-800/60 rounded-xl transition-colors">
                            Discard
                        </button>
                        <div class="flex items-center gap-2">
                            <button type="button" class="px-3.5 py-2 text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition-colors">
                                Edit Draft
                            </button>
                            <button type="button" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Approve & Send</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>
