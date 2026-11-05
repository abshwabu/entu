<x-app-layout
    title="500 — Server Error"
    description="An unexpected error occurred while processing your request."
>
    <div class="min-h-[70vh] flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Aurora ambient glow -->
        <div class="pointer-events-none absolute inset-0 -z-10 flex items-center justify-center">
            <div class="w-[500px] h-[500px] rounded-full bg-brand-violet/20 blur-[120px] dark:bg-brand-violet/30"></div>
        </div>

        <div class="max-w-2xl text-center space-y-8 relative z-10">
            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-mono font-semibold uppercase tracking-widest bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20">
                Server Error 500
            </span>

            <h1 class="font-display font-extrabold text-5xl sm:text-7xl text-text-ink dark:text-white tracking-tight leading-none">
                Something Went Wrong
            </h1>

            <p class="text-text-muted-light dark:text-text-muted-dark text-base sm:text-lg max-w-lg mx-auto leading-relaxed">
                Our servers encountered an unexpected condition. Please try refreshing the page or return to the home page.
            </p>

            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <a
                    href="{{ route('home') }}"
                    class="px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-gradient text-white hover:brightness-110 hover:shadow-lg hover:shadow-brand-500/25 transition-all duration-200"
                >
                    &larr; Return to Safety
                </a>

                <a
                    href="{{ route('contact') }}"
                    class="px-8 py-3.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-paper-raised dark:bg-ink-soft text-text-ink dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-ink-raised transition-all duration-200 border border-neutral-200 dark:border-line"
                >
                    Report Issue
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
