@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-8rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="w-full max-w-md space-y-8">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <div class="brand-badge w-14 h-14 mx-auto mb-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2DE3C8" stroke-width="2.2"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-[#EAF1F7] tracking-tight">Sign in to Milo</h2>
            <p class="text-xs sm:text-sm text-[#7C8AA0]">Milo — AI Agent to handle emails</p>
        </div>

        <!-- Flash Messages -->
        @if (session('status'))
            <div class="cred-row text-xs text-[var(--cyan)] mono flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[var(--cyan)] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="cred-row text-xs text-[var(--red)] mono border-[rgba(242,120,120,0.3)] flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[var(--red)] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Card -->
        <div class="role-card">
            <div class="role-inner p-6 sm:p-8 space-y-6">
                <!-- 1-Click Instant Demo Sandbox -->
                <div>
                    <a href="{{ route('dev.login') }}" class="app-launch w-full py-3.5 justify-center font-bold text-sm">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Instant Demo Sandbox</span>
                    </a>
                    <p class="text-[11px] text-center text-[#7C8AA0] mt-2 mono">Instant access with preloaded urgent contracts & AI drafts</p>
                </div>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-[10.5px] uppercase tracking-wider mono">
                        <span class="bg-[#10162A] px-3 text-[#7C8AA0]">Or connect real account</span>
                    </div>
                </div>

                <!-- Google OAuth Button -->
                <a href="{{ route('auth.google') }}" class="app-launch violet w-full py-3 justify-center text-xs font-bold">
                    <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Sign in with Google (Gmail)</span>
                </a>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-[10.5px] uppercase tracking-wider mono">
                        <span class="bg-[#10162A] px-3 text-[#7C8AA0]">Or email sign in</span>
                    </div>
                </div>

                <!-- Email Direct Sign In Form -->
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-4 mono text-xs">
                    @csrf
                    <div>
                        <label for="email" class="block font-semibold uppercase tracking-wider text-[#7C8AA0] mb-1.5">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', 'demo@ai-email-agent.local') }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-[#090C14] border border-white/10 text-[#EAF1F7] text-xs focus:outline-none focus:border-[var(--cyan)] font-sans transition-colors" placeholder="you@company.com">
                        @error('email')
                            <p class="text-xs text-[var(--red)] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block font-semibold uppercase tracking-wider text-[#7C8AA0] mb-1.5">Password</label>
                        <input type="password" id="password" name="password" value="password" required class="w-full px-3.5 py-2.5 rounded-xl bg-[#090C14] border border-white/10 text-[#EAF1F7] text-xs focus:outline-none focus:border-[var(--cyan)] font-sans transition-colors" placeholder="••••••••">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-[#EAF1F7] font-semibold text-xs transition-colors cursor-pointer">
                        Sign In with Email
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
