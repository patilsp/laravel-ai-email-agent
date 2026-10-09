<section id="specifications" class="py-20 sm:py-28 border-b border-[#e7e5df] bg-[#fbfaf7]" aria-labelledby="workflow-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Header -->
        <div class="max-w-3xl space-y-4">
            <div class="font-mono-tech text-xs text-stone-500 uppercase tracking-widest flex items-center gap-2">
                <span class="text-blue-600 font-bold">06/</span>
                <span>System Architecture</span>
            </div>
            <h2 id="workflow-heading" class="font-sans font-extrabold text-3xl sm:text-5xl text-[#121417] tracking-tight leading-[1.1]">
                From incoming stream<br />
                <span class="font-serif-hero italic font-normal text-blue-600">to verified action.</span>
            </h2>
            <p class="text-base text-stone-600 leading-relaxed max-w-2xl font-normal">
                Four orchestrated background phases executed systematically to reclaim hours every week.
            </p>
        </div>

        <!-- 4-Phase Architecture Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 font-mono-tech text-xs">
            
            <!-- Step 1 -->
            <div class="bg-white p-6 rounded-xl border border-stone-300 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-stone-400">
                        <span class="font-bold text-blue-600 text-sm">PHASE 01</span>
                        <span>INGEST</span>
                    </div>
                    <h3 class="font-bold text-base text-stone-900 font-sans">Receive</h3>
                    <p class="text-xs text-stone-600 leading-relaxed font-sans">
                        Laravel scheduler polls the Gmail API every 2 minutes using encrypted OAuth refresh tokens.
                    </p>
                </div>
                <div class="pt-3 border-t border-stone-100 text-[10px] text-stone-400">
                    Input: Unread messages
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-xl border border-stone-300 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-stone-400">
                        <span class="font-bold text-blue-600 text-sm">PHASE 02</span>
                        <span>COGNITION</span>
                    </div>
                    <h3 class="font-bold text-base text-stone-900 font-sans">Understand</h3>
                    <p class="text-xs text-stone-600 leading-relaxed font-sans">
                        Milo evaluates message intent, deadline pressure, tone sentiment, and conversation history.
                    </p>
                </div>
                <div class="pt-3 border-t border-stone-100 text-[10px] text-stone-400">
                    Engine: Claude 3.5 Sonnet
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-xl border border-stone-300 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-stone-400">
                        <span class="font-bold text-blue-600 text-sm">PHASE 03</span>
                        <span>SYNTHESIS</span>
                    </div>
                    <h3 class="font-bold text-base text-stone-900 font-sans">Prepare</h3>
                    <p class="text-xs text-stone-600 leading-relaxed font-sans">
                        Milo generates high-fidelity reply drafts, assigns Gmail labels, and stars urgent priority items.
                    </p>
                </div>
                <div class="pt-3 border-t border-stone-100 text-[10px] text-stone-400">
                    Output: Stored queue draft
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-6 rounded-xl border border-stone-300 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-stone-400">
                        <span class="font-bold text-emerald-600 text-sm">PHASE 04</span>
                        <span>DECISION</span>
                    </div>
                    <h3 class="font-bold text-base text-stone-900 font-sans">Review</h3>
                    <p class="text-xs text-stone-600 leading-relaxed font-sans">
                        You inspect drafts in your dashboard. One click approves and dispatches via your Gmail account.
                    </p>
                </div>
                <div class="pt-3 border-t border-stone-100 text-[10px] text-emerald-700 font-semibold">
                    Gate: Human-in-the-loop
                </div>
            </div>

        </div>

    </div>
</section>
