<x-app-layout
    title="500 — Server Error"
    description="An unexpected error occurred while processing your request."
>
    <div class="min-h-[70vh] flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl text-center space-y-8">
            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-mono font-semibold uppercase tracking-widest bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20">
                Server Error 500
            </span>

            <h1 class="font-display font-extrabold text-5xl sm:text-7xl text-neutral-900 dark:text-white tracking-tight leading-none">
                Something Went Wrong
            </h1>

            <p class="text-neutral-600 dark:text-neutral-400 text-base sm:text-lg max-w-lg mx-auto leading-relaxed">
                Our servers encountered an unexpected condition. Please try refreshing the page or return to the home page.
            </p>

            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <a
                    href="{{ route('home') }}"
                    class="px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-wider bg-mint-500 text-panel-deep hover:bg-mint-400 transition-all duration-200 shadow-md"
                >
                    &larr; Return to Safety
                </a>

                <a
                    href="{{ route('contact') }}"
                    class="px-8 py-3.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-neutral-100 dark:bg-panel-soft text-neutral-800 dark:text-neutral-200 hover:bg-neutral-200 dark:hover:bg-panel-raised transition-all duration-200 border border-[#e5e5df] dark:border-panel-soft"
                >
                    Report Issue
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
