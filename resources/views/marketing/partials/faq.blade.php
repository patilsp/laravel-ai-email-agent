<section id="faq" class="py-20 sm:py-28 border-b border-[#e8e6e1] bg-white" aria-labelledby="faq-heading">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Header -->
        <div class="space-y-4 text-left">
            <div class="font-mono-accent text-[11px] text-stone-500 uppercase tracking-widest flex items-center gap-2">
                <span class="text-blue-600 font-bold">06.</span>
                <span>Specifications & Transparency</span>
            </div>
            <h2 id="faq-heading" class="font-sans font-extrabold text-3xl sm:text-4xl text-[#191c21] tracking-tight">
                Frequently Asked <span class="font-editorial italic font-normal text-blue-600">Questions</span>
            </h2>
            <p class="text-base text-stone-600 font-normal">
                Direct answers regarding integration, privacy, automation safeguards, and availability.
            </p>
        </div>

        <!-- Hairline Accordion -->
        <div class="divide-y divide-stone-200 border-y border-stone-200" id="faq-accordion">
            
            <!-- Q1 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-sans font-bold text-base text-[#191c21] hover:text-blue-600 transition-colors" aria-expanded="false">
                    <span>What does the AI Email Agent do?</span>
                    <span class="faq-icon font-mono-accent text-stone-400 text-sm font-normal transition-transform duration-200">[+]</span>
                </button>
                <div class="faq-content hidden pt-3 text-sm text-stone-600 leading-relaxed font-sans">
                    The agent monitors your incoming Gmail messages, prioritizes them based on urgency and deadline, categorizes them into custom Gmail labels, generates concise thread briefings, and drafts contextual replies awaiting your approval.
                </div>
            </div>

            <!-- Q2 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-sans font-bold text-base text-[#191c21] hover:text-blue-600 transition-colors" aria-expanded="false">
                    <span>Does it automatically send emails without my permission?</span>
                    <span class="faq-icon font-mono-accent text-stone-400 text-sm font-normal transition-transform duration-200">[+]</span>
                </button>
                <div class="faq-content hidden pt-3 text-sm text-stone-600 leading-relaxed font-sans">
                    <strong>No.</strong> The application operates under a strict semi-autonomous model. While it reads, categorizes, and pre-composes responses automatically, no email is ever sent without your explicit click on "Approve & Send".
                </div>
            </div>

            <!-- Q3 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-sans font-bold text-base text-[#191c21] hover:text-blue-600 transition-colors" aria-expanded="false">
                    <span>Can I edit a suggested reply before sending?</span>
                    <span class="faq-icon font-mono-accent text-stone-400 text-sm font-normal transition-transform duration-200">[+]</span>
                </button>
                <div class="faq-content hidden pt-3 text-sm text-stone-600 leading-relaxed font-sans">
                    Yes. Every drafted reply includes an "Edit Draft" toggle that opens an editable textarea where you can modify words, add attachments, or rewrite any paragraph.
                </div>
            </div>

            <!-- Q4 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-sans font-bold text-base text-[#191c21] hover:text-blue-600 transition-colors" aria-expanded="false">
                    <span>How does Gmail integration work?</span>
                    <span class="faq-icon font-mono-accent text-stone-400 text-sm font-normal transition-transform duration-200">[+]</span>
                </button>
                <div class="faq-content hidden pt-3 text-sm text-stone-600 leading-relaxed font-sans">
                    You authenticate via Google OAuth 2.0. The Laravel scheduler securely checks for new unread messages in the background, routes them through the AI engine, and syncs labels directly to your Gmail account.
                </div>
            </div>

            <!-- Q5 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-sans font-bold text-base text-[#191c21] hover:text-blue-600 transition-colors" aria-expanded="false">
                    <span>Which AI model powers the system?</span>
                    <span class="faq-icon font-mono-accent text-stone-400 text-sm font-normal transition-transform duration-200">[+]</span>
                </button>
                <div class="faq-content hidden pt-3 text-sm text-stone-600 leading-relaxed font-sans">
                    The backend uses <strong>Anthropic Claude 3.5 Sonnet</strong> via the official Laravel AI SDK (`laravel/ai`), chosen for its world-class reading comprehension, nuance, and natural professional writing tone.
                </div>
            </div>

            <!-- Q6 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-sans font-bold text-base text-[#191c21] hover:text-blue-600 transition-colors" aria-expanded="false">
                    <span>How is my email privacy protected?</span>
                    <span class="faq-icon font-mono-accent text-stone-400 text-sm font-normal transition-transform duration-200">[+]</span>
                </button>
                <div class="faq-content hidden pt-3 text-sm text-stone-600 leading-relaxed font-sans">
                    All credentials and tokens are encrypted at rest with AES-256. API requests to Anthropic fall under enterprise commercial terms where data is not retained or used to train general AI models.
                </div>
            </div>

            <!-- Q7 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-sans font-bold text-base text-[#191c21] hover:text-blue-600 transition-colors" aria-expanded="false">
                    <span>When is this available?</span>
                    <span class="faq-icon font-mono-accent text-stone-400 text-sm font-normal transition-transform duration-200">[+]</span>
                </button>
                <div class="faq-content hidden pt-3 text-sm text-stone-600 leading-relaxed font-sans">
                    We are currently onboarding early access users in weekly batches. Register your work email below to receive an onboarding invitation.
                </div>
            </div>

        </div>

    </div>
</section>
