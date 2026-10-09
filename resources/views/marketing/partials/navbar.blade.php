<header class="sticky top-0 z-50 w-full bg-[#fbfaf7]/95 backdrop-blur-md border-b border-[#e7e5df] transition-all" id="main-header">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 group focus:outline-none" aria-label="Email AI Agent">
                <div class="w-8 h-8 bg-[#121417] text-[#fbfaf7] rounded flex items-center justify-center font-mono-tech font-bold text-xs tracking-wider group-hover:bg-blue-600 transition-colors">
                    ⌘E
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-[#121417] uppercase font-mono-tech">
                        Email AI Agent
                    </span>
                </div>
            </a>

            <!-- Editorial Nav -->
            <nav class="hidden lg:flex items-center gap-8 text-xs font-mono-tech uppercase tracking-wider" aria-label="Main Navigation">
                <a href="#interactive-workbench" class="text-stone-600 hover:text-[#121417] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-stone-400 group-hover:text-blue-600">01/</span>
                    <span>Live Workbench</span>
                </a>
                <a href="#triage-system" class="text-stone-600 hover:text-[#121417] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-stone-400 group-hover:text-blue-600">02/</span>
                    <span>Urgency Triage</span>
                </a>
                <a href="#thread-synthesis" class="text-stone-600 hover:text-[#121417] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-stone-400 group-hover:text-blue-600">03/</span>
                    <span>Thread Briefing</span>
                </a>
                <a href="#human-gateway" class="text-stone-600 hover:text-[#121417] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-stone-400 group-hover:text-blue-600">04/</span>
                    <span>Human Approval</span>
                </a>
                <a href="#specifications" class="text-stone-600 hover:text-[#121417] transition-colors py-1 flex items-center gap-1.5 group">
                    <span class="text-stone-400 group-hover:text-blue-600">05/</span>
                    <span>Specifications</span>
                </a>
            </nav>

            <!-- Primary Action -->
            <div class="hidden sm:flex items-center gap-3 font-mono-tech text-xs">
                <a href="#early-access" class="inline-flex items-center gap-2 px-4 py-2 font-semibold text-[#fbfaf7] bg-[#121417] hover:bg-blue-600 active:scale-98 rounded transition-all">
                    <span>Request Early Access</span>
                    <span>↗</span>
                </a>
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex lg:hidden">
                <button type="button" id="mobile-menu-btn" aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle Navigation" class="p-2 text-stone-700 hover:text-black rounded hover:bg-stone-100">
                    <svg id="hamburger-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg id="close-icon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden py-4 border-t border-[#e7e5df] flex-col gap-2 font-mono-tech text-xs">
            <a href="#interactive-workbench" class="mobile-nav-link px-3 py-2 text-stone-700 hover:text-blue-600 hover:bg-stone-100 rounded">01/ Live Workbench</a>
            <a href="#triage-system" class="mobile-nav-link px-3 py-2 text-stone-700 hover:text-blue-600 hover:bg-stone-100 rounded">02/ Urgency Triage</a>
            <a href="#thread-synthesis" class="mobile-nav-link px-3 py-2 text-stone-700 hover:text-blue-600 hover:bg-stone-100 rounded">03/ Thread Briefing</a>
            <a href="#human-gateway" class="mobile-nav-link px-3 py-2 text-stone-700 hover:text-blue-600 hover:bg-stone-100 rounded">04/ Human Approval</a>
            <a href="#specifications" class="mobile-nav-link px-3 py-2 text-stone-700 hover:text-blue-600 hover:bg-stone-100 rounded">05/ Specifications</a>
            <div class="pt-2 mt-2 border-t border-[#e7e5df]">
                <a href="#early-access" class="mobile-nav-link flex items-center justify-center gap-2 w-full py-2.5 font-semibold text-[#fbfaf7] bg-[#121417] rounded">
                    Request Early Access ↗
                </a>
            </div>
        </div>
    </div>
</header>
