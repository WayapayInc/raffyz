<div class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto z-10 relative w-full flex-grow font-display text-zinc-900 dark:text-white transition-colors duration-300">
    <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-100 dark:bg-white/5 border border-zinc-200 dark:border-white/10 text-zinc-500 dark:text-gray-400 w-fit mb-4">
            <span class="material-icons text-sm">gavel</span>
            <span class="text-xs font-bold uppercase tracking-wider">{{ __('messages.legal_documentation') }}</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-bold text-zinc-900 dark:text-white mb-6 tracking-tight">{{ $page->title ?? __('messages.terms_and_conditions') }}</h1>
        @if($page)
            <p class="text-zinc-500 dark:text-gray-500 text-sm mt-4">{{ __('messages.last_updated') }}: {{ $page->updated_at->locale(app()->getLocale())->translatedFormat('F d, Y') }}</p>
        @else
            <p class="text-zinc-500 dark:text-gray-500 text-sm mt-4">{{ __('messages.last_updated') }}: {{ date('F d, Y') }}</p>
        @endif
    </div>

    <div class="bg-white dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-2xl p-8 md:p-12 shadow-2xl relative overflow-hidden transition-colors duration-300">
        {{-- Background Blobs --}}
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none bg-primary/5"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full blur-3xl -ml-32 -mb-32 pointer-events-none bg-primary/5"></div>
        
        <div class="relative z-10 prose prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-gray-300 transition-colors duration-300">
            @if($page)
                <div class="terms-content">
                    {!! $page->content !!}
                </div>
            @else
                <div class="text-center py-12 text-zinc-500 dark:text-gray-500">
                    <p>{{ __('messages.no_content') }}</p>
                </div>
            @endif
        </div>
    </div>
    

</div>
