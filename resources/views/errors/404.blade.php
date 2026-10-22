<x-app-layout
    title="404 — Page Not Found"
    description="The page or design piece you were looking for could not be found."
>
    <div class="min-h-[70vh] flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl text-center space-y-8">
            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-mono font-semibold uppercase tracking-widest bg-brand-500/10 text-brand-800 dark:text-brand-400 border border-brand-500/20">
                Error 404
            </span>

            <h1 class="font-display font-extrabold text-5xl sm:text-7xl text-neutral-900 dark:text-white tracking-tight leading-none">
                Page Not Found
            </h1>

            <p class="text-neutral-600 dark:text-neutral-400 text-base sm:text-lg max-w-lg mx-auto leading-relaxed">
                The visual artifact, case study, or page you are seeking might have been archived, renamed, or never existed.
            </p>

            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <a
                    href="{{ route('home') }}"
                    class="px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-400 text-ink hover:bg-brand-500 transition-all duration-200 shadow-md"
                >
                    &larr; Back to Home
                </a>

                <a
                    href="{{ route('work.index') }}"
                    class="px-8 py-3.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-all duration-200 border border-[#e5e5df] dark:border-[#2a2a30]"
                >
                    Browse Selected Work
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
