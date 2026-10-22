@php
    $email = setting('email', 'hello@entucreative.com');
    $phone = setting('phone', '+251 911 234 567');
    $location = setting('location', 'Addis Ababa, Ethiopia');
    $prefilledSubject = request('subject', '');
@endphp

<x-app-layout
    title="Contact & Project Inquiries — Entu Creative"
    description="Get in touch with graphic designer & art director Entu for brand identity, packaging design, or visual art direction projects."
>
    <div class="py-12 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs / Back Link -->
            <div class="mb-10">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-neutral-500 hover:text-ink dark:text-neutral-400 dark:hover:text-mint-400 transition-colors group focus-visible:outline-2 focus-visible:outline-mint-500 rounded-sm"
                >
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Back to Home</span>
                </a>
            </div>

            <!-- Page Header -->
            <div class="mb-14 sm:mb-20 max-w-3xl">
                <span class="inline-block text-xs font-mono font-semibold uppercase tracking-widest text-teal-700 dark:text-mint-400 mb-3">
                    Start a Conversation
                </span>
                <h1 class="font-display font-extrabold text-4xl sm:text-6xl text-neutral-900 dark:text-white tracking-tight mb-4 leading-[1.1]">
                    Let's create something iconic together.
                </h1>
                <p class="text-neutral-600 dark:text-neutral-400 text-base sm:text-lg leading-relaxed">
                    Have a new brand to launch, packaging in need of refinement, or an art direction assignment? Share your project details below and I'll get back to you promptly.
                </p>
            </div>

            <!-- Main Layout: Contact Information (Left) & Form (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                <!-- Left: Studio Details & Channels -->
                <div class="lg:col-span-5 space-y-8">
                    <!-- Studio Status Badge -->
                    <div class="p-6 rounded-3xl bg-paper-raised dark:bg-panel-soft border border-black/10 dark:border-silk-aqua/20 space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-mint-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-mint-500"></span>
                            </span>
                            <span class="text-xs font-mono font-semibold uppercase tracking-wider text-teal-800 dark:text-mint-400">
                                Current Availability
                            </span>
                        </div>
                        <p class="text-sm text-neutral-700 dark:text-neutral-300 leading-relaxed">
                            Currently accepting select branding, packaging, and creative direction partnerships for Q2 & Q3 2026.
                        </p>
                    </div>

                    <!-- Direct Contact Channels -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-neutral-400 dark:text-neutral-500">
                            Direct Channels
                        </h3>

                        <div class="space-y-4">
                            <!-- Email -->
                            <a
                                href="mailto:{{ $email }}"
                                class="p-4 rounded-2xl bg-white dark:bg-panel-soft border border-black/10 dark:border-silk-aqua/20 hover:border-mint-400/50 flex items-center gap-4 group transition-all"
                            >
                                <span class="w-10 h-10 rounded-xl bg-mint-500/15 text-teal-800 dark:text-mint-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>
                                </span>
                                <div class="overflow-hidden">
                                    <span class="block text-[11px] font-mono uppercase text-neutral-400">Direct Email</span>
                                    <span class="font-medium text-sm sm:text-base text-neutral-900 dark:text-white group-hover:text-teal-700 dark:group-hover:text-mint-400 transition-colors truncate block">
                                        {{ $email }}
                                    </span>
                                </div>
                            </a>

                            <!-- Phone -->
                            @if($phone)
                                <a
                                    href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}"
                                    class="p-4 rounded-2xl bg-white dark:bg-panel-soft border border-black/10 dark:border-silk-aqua/20 hover:border-mint-400/50 flex items-center gap-4 group transition-all"
                                >
                                    <span class="w-10 h-10 rounded-xl bg-mint-500/15 text-teal-800 dark:text-mint-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="block text-[11px] font-mono uppercase text-neutral-400">Phone / WhatsApp</span>
                                        <span class="font-medium text-sm sm:text-base text-neutral-900 dark:text-white group-hover:text-teal-700 dark:group-hover:text-mint-400 transition-colors">
                                            {{ $phone }}
                                        </span>
                                    </div>
                                </a>
                            @endif

                            <!-- Location -->
                            <div class="p-4 rounded-2xl bg-white dark:bg-panel-soft border border-black/10 dark:border-silk-aqua/20 flex items-center gap-4">
                                <span class="w-10 h-10 rounded-xl bg-mint-500/15 text-teal-800 dark:text-mint-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="block text-[11px] font-mono uppercase text-neutral-400">Studio Location</span>
                                    <span class="font-medium text-sm sm:text-base text-neutral-900 dark:text-white">
                                        {{ $location }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Networks -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-mono font-semibold uppercase tracking-widest text-neutral-400 dark:text-neutral-500">
                            Social Media & Profiles
                        </h3>
                        <div class="flex flex-wrap gap-2.5">
                            @if(setting('behance'))
                                <a href="{{ setting('behance') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-semibold bg-neutral-100 hover:bg-neutral-200 dark:bg-panel dark:hover:bg-panel-raised text-neutral-800 dark:text-neutral-200 border border-black/5 dark:border-silk-aqua/20 transition-colors">
                                    Behance ↗
                                </a>
                            @endif

                            @if(setting('dribbble'))
                                <a href="{{ setting('dribbble') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-semibold bg-neutral-100 hover:bg-neutral-200 dark:bg-panel dark:hover:bg-panel-raised text-neutral-800 dark:text-neutral-200 border border-black/5 dark:border-silk-aqua/20 transition-colors">
                                    Dribbble ↗
                                </a>
                            @endif

                            @if(setting('instagram'))
                                <a href="{{ setting('instagram') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-semibold bg-neutral-100 hover:bg-neutral-200 dark:bg-panel dark:hover:bg-panel-raised text-neutral-800 dark:text-neutral-200 border border-black/5 dark:border-silk-aqua/20 transition-colors">
                                    Instagram ↗
                                </a>
                            @endif

                            @if(setting('linkedin'))
                                <a href="{{ setting('linkedin') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-semibold bg-neutral-100 hover:bg-neutral-200 dark:bg-panel dark:hover:bg-panel-raised text-neutral-800 dark:text-neutral-200 border border-black/5 dark:border-silk-aqua/20 transition-colors">
                                    LinkedIn ↗
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div class="lg:col-span-7">
                    <div class="p-8 sm:p-12 rounded-3xl bg-paper-raised dark:bg-panel-soft border border-black/10 dark:border-silk-aqua/20 shadow-xl">
                        <!-- Success Flash Message -->
                        @if(session('success'))
                            <div class="mb-8 p-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-900 dark:text-emerald-200 flex items-start gap-4 animate-fade-in">
                                <span class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </span>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-base">Message Sent Successfully!</h4>
                                    <p class="text-sm leading-relaxed text-emerald-800 dark:text-emerald-300">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Honeypot anti-spam field (hidden from legitimate users) -->
                            <div class="hidden" aria-hidden="true" style="display: none !important;">
                                <label for="website">Please leave this field blank</label>
                                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" />
                            </div>

                            <!-- Name Field -->
                            <div class="space-y-2">
                                <label for="name" class="block text-xs font-mono font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                                    Your Name <span class="text-teal-700 dark:text-mint-400">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="e.g. Maya Lin"
                                    class="w-full px-4 py-3.5 rounded-xl bg-white dark:bg-panel border @error('name') border-red-500 dark:border-red-500 @else border-[#dcdcd6] dark:border-silk-aqua/25 @enderror text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-mint-500 focus:border-mint-500 transition-all text-sm"
                                />
                                @error('name')
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="space-y-2">
                                <label for="email" class="block text-xs font-mono font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                                    Email Address <span class="text-teal-700 dark:text-mint-400">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="e.g. maya@company.com"
                                    class="w-full px-4 py-3.5 rounded-xl bg-white dark:bg-panel border @error('email') border-red-500 dark:border-red-500 @else border-[#dcdcd6] dark:border-silk-aqua/25 @enderror text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-mint-500 focus:border-mint-500 transition-all text-sm"
                                />
                                @error('email')
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subject Field -->
                            <div class="space-y-2">
                                <label for="subject" class="block text-xs font-mono font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                                    Subject / Scope of Interest
                                </label>
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    value="{{ old('subject', $prefilledSubject) }}"
                                    placeholder="e.g. Brand Identity & Packaging for Artisan Roastery"
                                    class="w-full px-4 py-3.5 rounded-xl bg-white dark:bg-panel border @error('subject') border-red-500 dark:border-red-500 @else border-[#dcdcd6] dark:border-silk-aqua/25 @enderror text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-mint-500 focus:border-mint-500 transition-all text-sm"
                                />
                                @error('subject')
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message Body Field -->
                            <div class="space-y-2">
                                <label for="message" class="block text-xs font-mono font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                                    Project Details & Vision <span class="text-teal-700 dark:text-mint-400">*</span>
                                </label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="6"
                                    required
                                    placeholder="Tell me about your business, the challenge you are solving, ideal timeline, and any specific deliverables..."
                                    class="w-full px-4 py-3.5 rounded-xl bg-white dark:bg-panel border @error('message') border-red-500 dark:border-red-500 @else border-[#dcdcd6] dark:border-silk-aqua/25 @enderror text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-mint-500 focus:border-mint-500 transition-all text-sm resize-y"
                                >{{ old('message', old('body')) }}</textarea>
                                @error('message')
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                                @error('body')
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button
                                    type="submit"
                                    class="w-full py-4 px-8 rounded-full text-xs font-bold uppercase tracking-widest bg-mint-500 text-panel-deep hover:bg-mint-400 transition-all duration-300 shadow-md hover:shadow-xl flex items-center justify-center gap-3 group focus-visible:outline-2 focus-visible:outline-mint-500"
                                >
                                    <span>Send Inquiry</span>
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                    </svg>
                                </button>
                                    <span>Send Inquiry</span>
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                    </svg>
                                </button>
                                <p class="mt-3 text-center text-xs text-neutral-500 dark:text-neutral-400 font-mono">
                                    I typically review new inquiries and respond within 24–48 business hours.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
