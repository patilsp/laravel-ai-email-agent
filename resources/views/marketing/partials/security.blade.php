<section id="security" class="py-20 sm:py-28 lg:py-32 border-t border-slate-200/70" aria-labelledby="security-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center space-y-4">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60 uppercase tracking-wider">
                Security & Data Integrity
            </span>
            <h2 id="security-heading" class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-slate-900 tracking-tight">
                Your privacy and inbox trust,<br />
                <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">protected by design.</span>
            </h2>
            <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
                We take email confidentiality seriously. Here is our straightforward, transparent approach to security.
            </p>
        </div>

        <!-- 4 Security Pillar Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Pillar 1 -->
            <div class="bg-white rounded-3xl p-7 border border-slate-200/90 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900">Official Google OAuth 2.0</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        We never ask for or store your Google password. Authorization happens directly via Google's secure OAuth consent screen with explicit, granular permissions.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium">
                    ✓ Scoped access only for requested Gmail features
                </div>
            </div>

            <!-- Pillar 2 -->
            <div class="bg-white rounded-3xl p-7 border border-slate-200/90 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900">Encrypted Token Storage</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        OAuth refresh tokens and credentials are encrypted at rest using Laravel's AES-256 encryption. Tokens are only decrypted during scheduled batch synchronization.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium">
                    ✓ AES-256 encryption for sensitive credentials
                </div>
            </div>

            <!-- Pillar 3 -->
            <div class="bg-white rounded-3xl p-7 border border-slate-200/90 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-50 border border-violet-100 text-violet-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900">Anthropic Claude Enterprise AI</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        We connect to Anthropic's API via the official Laravel AI SDK. Anthropic's commercial API policies state that customer API data is not used to train models.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium">
                    ✓ Strict no-training guarantee on commercial API endpoints
                </div>
            </div>

            <!-- Pillar 4 -->
            <div class="bg-white rounded-3xl p-7 border border-slate-200/90 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900">Revoke & Disconnect Anytime</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        You have full power to unlink your Gmail inbox and erase cached metadata at any moment directly from your settings or via Google Account Security.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium">
                    ✓ Complete data sovereignty & instant 1-click unlink
                </div>
            </div>

        </div>

    </div>
</section>
