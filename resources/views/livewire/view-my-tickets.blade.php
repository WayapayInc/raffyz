<div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto z-10 relative w-full flex-grow flex flex-col items-center justify-center font-display transition-colors duration-300">
    <div class="w-full max-w-3xl flex flex-col items-center text-center space-y-8 py-12 md:py-24">
        <div class="h-40 w-40 bg-zinc-100 dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-2xl flex items-center justify-center shadow-lg mb-4 animate-[bounce_3s_infinite] transition-colors duration-300">
            <span class="material-icons text-primary" style="font-size: 4rem;">confirmation_number</span>
        </div>
        
        <div class="space-y-4">
            <h1 class="text-4xl md:text-6xl font-bold text-zinc-900 dark:text-white tracking-tight">
                {{ __('messages.view_my') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary/60">{{ __('messages.tickets_title_suffix') }}</span>
            </h1>
            <p class="text-zinc-500 dark:text-gray-400 text-lg max-w-lg mx-auto">
                {{ __('messages.enter_document_id') }}
            </p>
        </div>

        {{-- Search Form --}}
        <div class="w-full max-w-xl mt-8">
            <form wire:submit="search" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-grow group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-icons text-zinc-400 dark:text-gray-500 group-focus-within:text-primary transition-colors">badge</span>
                    </div>
                    <input type="text" wire:model="identityDocument"
                           required
                           class="block w-full pl-12 pr-4 py-4 bg-white dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300 text-lg shadow-inner"
                           placeholder="{{ __('messages.identity_document') }}" />
                </div>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="search"
                        class="bg-primary text-white dark:text-black font-bold text-lg px-8 py-4 rounded-xl hover:bg-primary-hover transition-all duration-300 shadow-neon hover:shadow-primary flex items-center justify-center gap-2 sm:w-auto w-full disabled:opacity-50">
                    <span wire:loading.remove wire:target="search">{{ __('messages.search') }}</span>
                    <span wire:loading wire:target="search" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span class="material-icons" wire:loading.remove wire:target="search">search</span>
                </button>
            </form>
            @error('identityDocument')
                <p class="mt-2 text-sm text-red-500 font-medium text-left">{{ $message }}</p>
            @enderror
            <p class="mt-4 text-xs text-zinc-400 dark:text-gray-400">
                {{ __('messages.secure_lookup_notice') }}
            </p>
        </div>

         {{-- Results Section --}}
        <div class="w-full max-w-4xl mt-12">
            {{-- Loading Skeleton --}}
            <div wire:loading wire:target="search" class="w-full space-y-6">
                @for($i = 0; $i < 2; $i++)
                    <div class="bg-white dark:bg-surface-dark rounded-2xl border border-zinc-200 dark:border-white/10 overflow-hidden shadow-sm animate-pulse">
                        <div class="px-6 py-4 bg-zinc-100 dark:bg-white/5">
                            <div class="h-5 bg-zinc-200 dark:bg-white/10 rounded w-1/3 mb-2"></div>
                            <div class="h-3 bg-zinc-200 dark:bg-white/10 rounded w-1/4"></div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="h-4 bg-zinc-200 dark:bg-white/10 rounded w-1/2"></div>
                                <div class="h-4 bg-zinc-200 dark:bg-white/10 rounded w-1/2"></div>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-4">
                                <div class="h-8 w-16 bg-zinc-200 dark:bg-white/10 rounded"></div>
                                <div class="h-8 w-16 bg-zinc-200 dark:bg-white/10 rounded"></div>
                                <div class="h-8 w-16 bg-zinc-200 dark:bg-white/10 rounded"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            {{-- Actual Results --}}
            <div wire:loading.remove wire:target="search">
                @if($searched)
                    @if(count($tickets) > 0)
                        <div class="space-y-6 text-left">
                            @foreach($tickets as $ticket)
                                <div class="bg-white dark:bg-surface-dark rounded-2xl border border-zinc-200 dark:border-white/10 overflow-hidden shadow-lg hover:shadow-primary/20 transition-all duration-300 group">
                                    <div class="px-6 py-4 bg-gradient-to-r from-primary to-primary/80 text-white dark:text-black flex justify-between items-center">
                                        <div>
                                            <h3 class="font-bold text-lg group-hover:text-white dark:group-hover:text-black transition-colors">{{ $ticket->raffle->title }}</h3>
                                            <p class="text-white/80 dark:text-black/70 text-sm">{{ __('messages.purchased_on') }}: {{ $ticket->created_at->locale(app()->getLocale())->translatedFormat('d F, Y - h:i A') }}</p>
                                        </div>
                                         <span class="material-icons text-white/40 dark:text-black/40 group-hover:text-white dark:group-hover:text-black transition-colors">confirmation_number</span>
                                    </div>
                                    <div class="p-6 space-y-6">
                                        <div class="grid grid-cols-2 gap-6 text-sm border-b border-zinc-200 dark:border-white/5 pb-6">
                                            <div>
                                                <span class="text-zinc-500 dark:text-gray-500 uppercase text-xs tracking-wider">{{ __('messages.quantity') }}</span>
                                                <p class="font-bold text-zinc-900 dark:text-white text-lg">{{ $ticket->tickets_quantity }} {{ __('messages.tickets_sold') }}</p>
                                            </div>
                                            <div>
                                                <span class="text-zinc-500 dark:text-gray-500 uppercase text-xs tracking-wider">{{ __('messages.total_amount') }}</span>
                                                <p class="font-bold text-primary text-lg">{{ \App\Helpers\CurrencyHelper::format($ticket->total_amount) }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-zinc-600 dark:text-gray-300 mb-3 flex items-center gap-2">
                                                <span class="material-icons text-xs text-primary">tag</span> {{ __('messages.ticket_numbers') }}
                                            </h4>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($ticket->tickets_bought as $number)
                                                    <span class="inline-flex items-center px-3 py-1.5 bg-zinc-100 dark:bg-white/5 border border-zinc-200 dark:border-white/10 text-primary rounded-lg text-sm font-mono font-bold hover:bg-primary hover:text-white dark:hover:text-black transition-colors cursor-default">
                                                        #{{ $number }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 bg-white dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-2xl transition-colors duration-300">
                             <div class="w-16 h-16 mx-auto bg-zinc-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-4">
                                <span class="material-icons text-zinc-400 dark:text-gray-500 text-3xl">search_off</span>
                            </div>
                            <h3 class="text-zinc-900 dark:text-white font-bold text-lg mb-2">{{ __('messages.no_tickets_found') }}</h3>
                            <p class="text-zinc-500 dark:text-gray-500">{{ __('messages.check_typing_error') }}</p>
                        </div>
                    @endif
                @else
                    {{-- Default empty state features --}}
                @endif
            </div>
        </div>
    </div>
</div>
