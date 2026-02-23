<div class="min-h-screen bg-zinc-50 dark:bg-background-dark text-zinc-900 dark:text-white font-display transition-colors duration-300">
    <section class="max-w-7xl mx-auto px-6 lg:px-8 pt-32 pb-16">
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-8 tracking-tight text-zinc-900 dark:text-white">{{ __('messages.raffles') }}</h1>
            <div class="flex gap-3">
                <button wire:click="setFilter('all')" 
                        wire:loading.attr="disabled"
                        wire:target="setFilter('all')"
                        class="filter-btn {{ $filter === 'all' ? 'filter-btn-active' : '' }} disabled:opacity-50 disabled:cursor-wait">
                    {{ __('messages.all') }}
                </button>
                <button wire:click="setFilter('active')" 
                        wire:loading.attr="disabled"
                        wire:target="setFilter('active')"
                        class="filter-btn {{ $filter === 'active' ? 'filter-btn-active' : '' }} disabled:opacity-50 disabled:cursor-wait">
                    {{ __('messages.active_raffles') }}
                </button>
                <button wire:click="setFilter('finished')" 
                        wire:loading.attr="disabled"
                        wire:target="setFilter('finished')"
                        class="filter-btn {{ $filter === 'finished' ? 'filter-btn-active' : '' }} disabled:opacity-50 disabled:cursor-wait">
                    {{ __('messages.finished_raffles') }}
                </button>
            </div>
        </div>

        <div wire:loading.remove wire:target="setFilter" class="w-full">
            @if($raffles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
                    @foreach($raffles as $raffle)
                        <div class="group bg-white dark:bg-surface-dark rounded-xl border border-zinc-200 dark:border-white/10 overflow-hidden hover:border-primary/50 transition-all duration-300 hover:shadow-neon relative flex flex-col shadow-sm dark:shadow-none">
                            <div class="aspect-[16/10] overflow-hidden bg-zinc-100 dark:bg-black relative">
                                 @if($raffle->images && count($raffle->images) > 0)
                                    <img alt="{{ $raffle->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                         src="{{ Storage::url($raffle->images[0]) }}"/>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-surface-dark to-transparent z-10 transition-colors duration-300"></div>
                            </div>
                            <div class="p-8 relative z-20 -mt-12">
                                <a href="{{ route('raffles.show', $raffle->slug) }}" wire:navigate>
                                    <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2 line-clamp-1 group-hover:text-primary transition-colors">{{ $raffle->title }}</h3>
                                </a>
                                <p class="text-zinc-600 dark:text-gray-400 text-sm mb-8 leading-relaxed line-clamp-2">{{ strip_tags($raffle->description) }}</p>
                                
                                <div class="mb-8">
                                    <div class="flex justify-between items-end mb-3">
                                        <span class="text-[10px] uppercase tracking-[0.2em] text-zinc-500 dark:text-gray-500 font-bold">{{ __('messages.tickets_sold') }}</span>
                                        <span class="text-xs font-bold text-zinc-700 dark:text-white">{{ $raffle->sold_percentage }}%</span>
                                    </div>
                                    <div class="h-2 bg-zinc-200 dark:bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary relative overflow-hidden" style="width:{{ $raffle->sold_percentage }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between pt-6 border-t border-zinc-200 dark:border-white/5">
                                    <div>
                                        <span class="block text-[10px] uppercase tracking-[0.2em] text-zinc-500 dark:text-gray-500 font-bold mb-1">{{ __('messages.entry_price') }}</span>
                                        <span class="text-3xl font-bold text-primary">{{ \App\Helpers\CurrencyHelper::format($raffle->ticket_price) }}</span>
                                    </div>
                                    @if($raffle->isActive())
                                        <a href="{{ route('raffles.show', $raffle->slug) }}" wire:navigate 
                                           class="px-4 py-2 bg-zinc-100 dark:bg-white/5 hover:bg-primary hover:text-white dark:hover:text-black border border-zinc-200 dark:border-white/10 hover:border-primary rounded-lg text-sm font-bold transition-all duration-200 text-zinc-700 dark:text-gray-300">
                                            {{ __('messages.enter_now') }}
                                        </a>
                                    @else
                                        <span class="px-4 py-2 bg-zinc-100 dark:bg-white/5 border border-zinc-200 dark:border-white/10 rounded-lg text-sm font-bold text-zinc-500 dark:text-zinc-400 cursor-not-allowed">
                                            {{ $raffle->effective_status->getLabel() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 text-zinc-500 dark:text-gray-500 w-full">
                     <span class="material-icons mx-auto mb-4 opacity-30" style="font-size: 8rem;">local_activity</span>
                    <p class="text-xl">{{ __('messages.no_raffles') }}</p>
                </div>
            @endif
        </div>

        {{-- Skeleton Loading State --}}
        <div wire:loading.block wire:target="setFilter" class="w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
                @foreach(range(1, 6) as $index)
                    <div class="bg-white dark:bg-surface-dark rounded-xl border border-zinc-200 dark:border-white/10 overflow-hidden animate-pulse flex flex-col shadow-sm dark:shadow-none">
                        <div class="aspect-[16/10] bg-zinc-100 dark:bg-white/5 relative">
                            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-white dark:from-surface-dark to-transparent"></div>
                        </div>
                        <div class="p-8 relative -mt-12">
                            <div class="h-8 bg-zinc-200 dark:bg-white/10 rounded-lg w-3/4 mb-4"></div>
                            <div class="space-y-2 mb-8">
                                <div class="h-4 bg-zinc-100 dark:bg-white/5 rounded w-full"></div>
                                <div class="h-4 bg-zinc-100 dark:bg-white/5 rounded w-2/3"></div>
                            </div>
                            
                            <div class="mb-8">
                                <div class="flex justify-between items-end mb-3">
                                    <div class="h-2 bg-zinc-100 dark:bg-white/5 rounded w-20"></div>
                                    <div class="h-3 bg-zinc-100 dark:bg-white/5 rounded w-8"></div>
                                </div>
                                <div class="h-2 bg-zinc-200 dark:bg-white/10 rounded-full w-full"></div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-6 border-t border-zinc-200 dark:border-white/5">
                                <div>
                                    <div class="h-2 bg-zinc-100 dark:bg-white/5 rounded w-16 mb-2"></div>
                                    <div class="h-8 bg-zinc-200 dark:bg-white/10 rounded w-28"></div>
                                </div>
                                <div class="h-10 bg-zinc-100 dark:bg-white/5 border border-zinc-200 dark:border-white/10 rounded-lg w-32"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
