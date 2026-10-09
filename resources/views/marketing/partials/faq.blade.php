<section id="faq" class="py-16 sm:py-24 border-t border-white/10 relative overflow-hidden" aria-labelledby="faq-heading">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Vaultline Header -->
        <div class="space-y-3 text-left">
            <div class="eyebrow">
                <span class="line"></span>
                <span>06/ SPECIFICATIONS & TRANSPARENCY</span>
            </div>
            <h2 id="faq-heading" class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-[#EAF1F7] tracking-tight">
                Frequently Asked <span class="accent">Questions</span>
            </h2>
            <p class="text-sm sm:text-base text-[#B7C2D6]">
                Direct answers regarding Google OAuth security, privacy, automation safeguards, and availability.
            </p>
        </div>

        <!-- Hairline Vaultline Accordion -->
        <div class="divide-y divide-white/10 border-y border-white/10 font-sans" id="faq-accordion">
            
            <!-- Q1 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-bold text-base text-[#EAF1F7] hover:text-[var(--cyan)] transition-colors cursor-pointer" aria-expanded="false">
                    <span>What does Milo do?</span>
                    <span class="faq-icon mono text-[var(--cyan)] text-lg transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden pt-3 text-xs sm:text-sm text-[#B7C2D6] leading-relaxed">
                    <strong class="text-[#EAF1F7]">Milo is an AI Agent to handle emails.</strong> Milo monitors your incoming Gmail messages, prioritizes them based on urgency and deadline, categorizes them into custom Gmail labels, generates concise thread briefings, and drafts contextual replies awaiting your approval.
                </div>
            </div>

            <!-- Q2 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-bold text-base text-[#EAF1F7] hover:text-[var(--cyan)] transition-colors cursor-pointer" aria-expanded="false">
                    <span>Does Milo send emails automatically without permission?</span>
                    <span class="faq-icon mono text-[var(--cyan)] text-lg transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden pt-3 text-xs sm:text-sm text-[#B7C2D6] leading-relaxed">
                    <strong class="text-[#EAF1F7]">No.</strong> Milo operates under a strict semi-autonomous model. While it reads, categorizes, and pre-composes responses automatically, no email is ever sent without your explicit click on "Approve & Send".
                </div>
            </div>

            <!-- Q3 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-bold text-base text-[#EAF1F7] hover:text-[var(--cyan)] transition-colors cursor-pointer" aria-expanded="false">
                    <span>Can I customize the tone or edit drafted responses?</span>
                    <span class="faq-icon mono text-[var(--cyan)] text-lg transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden pt-3 text-xs sm:text-sm text-[#B7C2D6] leading-relaxed">
                    Yes. Every drafted reply includes an editable textarea where you can modify words, change paragraphs, or switch tone presets (Executive, Concise, Warm, or Bullets) before sending.
                </div>
            </div>

            <!-- Q4 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-bold text-base text-[#EAF1F7] hover:text-[var(--cyan)] transition-colors cursor-pointer" aria-expanded="false">
                    <span>How does Gmail integration work?</span>
                    <span class="faq-icon mono text-[var(--cyan)] text-lg transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden pt-3 text-xs sm:text-sm text-[#B7C2D6] leading-relaxed">
                    You authenticate via Google OAuth 2.0 with scoped permissions. The Laravel backend polls for new unread messages in the background, routes them through the AI engine, and syncs labels directly to your Gmail account.
                </div>
            </div>

            <!-- Q5 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-bold text-base text-[#EAF1F7] hover:text-[var(--cyan)] transition-colors cursor-pointer" aria-expanded="false">
                    <span>Which AI model powers Milo?</span>
                    <span class="faq-icon mono text-[var(--cyan)] text-lg transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden pt-3 text-xs sm:text-sm text-[#B7C2D6] leading-relaxed">
                    Milo uses <strong class="text-[#EAF1F7]">Anthropic Claude 3.5 Sonnet</strong> via the official Laravel AI SDK (`laravel/ai`), chosen for its world-class reading comprehension, nuance, and natural professional writing tone.
                </div>
            </div>

            <!-- Q6 -->
            <div class="py-5">
                <button type="button" class="faq-toggle w-full text-left flex items-center justify-between gap-4 font-bold text-base text-[#EAF1F7] hover:text-[var(--cyan)] transition-colors cursor-pointer" aria-expanded="false">
                    <span>How is my email data protected?</span>
                    <span class="faq-icon mono text-[var(--cyan)] text-lg transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden pt-3 text-xs sm:text-sm text-[#B7C2D6] leading-relaxed">
                    All credentials and refresh tokens are encrypted at rest with AES-256. API requests to Anthropic fall under enterprise commercial agreements where customer data is not retained or used to train general AI models.
                </div>
            </div>

        </div>

    </div>
</section>
