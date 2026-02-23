<div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto z-10 relative w-full flex-grow">
    <div class="flex flex-col gap-12">
        {{-- Hero / Featured Raffle --}}
        @if($featuredRaffle)
            <div class="w-full">
                <div class="relative rounded-2xl overflow-hidden bg-surface-dark border border-white/10 group shadow-none transition-colors duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-background-dark via-transparent to-transparent z-10 transition-colors duration-300"></div>
                    @if($featuredRaffle->images && count($featuredRaffle->images) > 0)
                        <img alt="{{ $featuredRaffle->title }}" 
                             class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700" 
                             src="{{ Storage::url($featuredRaffle->images[0]) }}"/>
                    @endif
                    <div class="relative z-20 p-8 md:p-12 lg:p-16 flex flex-col justify-center min-h-[500px]">
                        @if($featuredRaffle->end_date && $featuredRaffle->end_date->isFuture())
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/20 border border-primary/40 text-primary w-fit mb-6 backdrop-blur-sm">
                                <span class="animate-pulse h-2 w-2 rounded-full bg-primary"></span>
                                <span class="text-xs font-bold uppercase tracking-wider">{{ __('messages.grand_prize') }}</span>
                            </div>
                        @endif
                        
                        <h1 class="text-5xl md:text-7xl font-bold text-white mb-4 tracking-tight leading-none drop-shadow-none">
                            {{ $featuredRaffle->title }}
                        </h1>
                        
                        <p class="text-gray-300 text-lg md:text-xl max-w-xl mb-8 leading-relaxed line-clamp-2">
                            {{ strip_tags($featuredRaffle->description) }}
                        </p>
                        
                        <div class="flex flex-wrap items-center gap-6 mb-10">
                            {{-- Countdown Timer --}}
                            @if($featuredRaffle->end_date)
                                <div class="bg-black/40 backdrop-blur-md rounded-lg p-4 border border-white/10 shadow-none text-gray-400"
                                     x-data="{
                                        expiry: new Date('{{ $featuredRaffle->end_date->toIso8601String() }}').getTime(),
                                        remaining: { days: '00', hours: '00', minutes: '00', seconds: '00' },
                                        init() {
                                            this.update();
                                            setInterval(() => this.update(), 1000);
                                        },
                                        update() {
                                            const now = new Date().getTime();
                                            const distance = this.expiry - now;
                                            if (distance < 0) {
                                                this.remaining = { days: '00', hours: '00', minutes: '00', seconds: '00' };
                                            } else {
                                                 this.remaining = {
                                                    days: Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0'),
                                                    hours: Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0'),
                                                    minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0'),
                                                    seconds: Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0'),
                                                };
                                            }
                                        }
                                     }">
                                    <span class="block text-xs uppercase tracking-widest mb-1">{{ __('messages.time_left') }}</span>
                                    <div class="font-mono text-2xl md:text-3xl text-primary font-bold flex gap-2">
                                        <span x-text="remaining.days + 'd'"></span><span class="text-gray-600">:</span>
                                        <span x-text="remaining.hours"></span><span class="text-gray-600">:</span>
                                        <span x-text="remaining.minutes"></span><span class="text-gray-600">:</span>
                                        <span x-text="remaining.seconds"></span>
                                    </div>
                                </div>
                            @endif

                            <div class="bg-black/40 backdrop-blur-md rounded-lg p-4 border border-white/10 min-w-[150px] shadow-none">
                                <span class="block text-xs text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.tickets_sold') }}</span>
                                <div class="font-mono text-2xl md:text-3xl text-white font-bold">
                                    {{ $featuredRaffle->tickets_sold }}<span class="text-gray-500 text-lg">/{{ $featuredRaffle->total_tickets }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('raffles.show', $featuredRaffle->slug) }}" wire:navigate 
                               class="bg-primary text-background-dark font-bold text-lg px-8 py-4 rounded-xl hover:bg-white hover:text-black transition-colors duration-300 shadow-neon flex items-center justify-center gap-2">
                                {{ __('messages.get_tickets_now') }} <span class="material-icons">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Raffles Grid --}}
        <div>

            @if($raffles->count() > 0)
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">{{ __('messages.raffles') }}</h2>
                @if($raffles->count() >= 6)
                <a href="{{ route('raffles.index') }}" wire:navigate class="text-sm font-medium text-primary hover:opacity-80 flex items-center gap-1">
                    {{ __('messages.view_all') }}
                    <span class="material-icons text-sm">arrow_forward</span>
                </a>
                @endif
            </div>
            @endif
            
            @if($raffles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($raffles as $raffle)
                        <div class="group bg-white dark:bg-surface-dark rounded-xl border border-zinc-200 dark:border-white/10 overflow-hidden hover:border-primary/50 transition-all duration-300 hover:shadow-neon relative shadow-sm dark:shadow-none">
                            <div class="h-64 overflow-hidden relative">
                                <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-surface-dark to-transparent z-10 transition-colors duration-300"></div>
                                @if($raffle->images && count($raffle->images) > 0)
                                    <img alt="{{ $raffle->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                         src="{{ Storage::url($raffle->images[0]) }}"/>
                                @endif
                            </div>
                            
                            <div class="p-6 relative z-20 -mt-12">
                                <a href="{{ route('raffles.show', $raffle->slug) }}" wire:navigate class="block">
                                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-1 group-hover:text-primary transition-colors line-clamp-1">{{ $raffle->title }}</h3>
                                </a>
                                <div class="text-zinc-600 dark:text-gray-400 text-sm mb-4 line-clamp-1">{{ strip_tags($raffle->description) }}</div>
                                
                                <div class="space-y-2 mb-6">
                                    <div class="flex justify-between text-xs font-mono">
                                        <span class="text-zinc-500 dark:text-gray-400">{{ __('messages.tickets_sold') }}</span>
                                        <span class="text-zinc-700 dark:text-white">{{ $raffle->sold_percentage }}%</span>
                                    </div>
                                    <div class="h-2 bg-zinc-200 dark:bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary relative overflow-hidden" style="width: {{ $raffle->sold_percentage }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-zinc-500 dark:text-gray-500 uppercase">{{ __('messages.entry_price') }}</span>
                                        <span class="text-lg font-bold text-primary">{{ \App\Helpers\CurrencyHelper::format($raffle->ticket_price) }}</span>
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

             @if(!$featuredRaffle)
                <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                    <div class="bg-zinc-100 dark:bg-white/5 rounded-full p-6 mb-6">
                        <span class="material-icons text-6xl text-zinc-400 dark:text-gray-500" style="font-size: 8rem">local_activity</span>
                    </div>
                    <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.no_active_raffles_moment') }}</h3>
                    <p class="text-zinc-500 dark:text-gray-400 max-w-md mx-auto mb-8">
                        {{ __('messages.no_active_raffles_description') }}
                    </p>
                </div>
             @endif
            @endif
