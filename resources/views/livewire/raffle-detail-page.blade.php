@push('meta')
    @php
        $firstImage = ($raffle->images && count($raffle->images) > 0) ? Storage::url($raffle->images[0]) : asset('images/default.jpg');
        $description = trim(preg_replace('/\s+/', ' ', strip_tags($raffle->description)));
    @endphp


    <meta property="og:type" content="website" />
    <meta property="og:image:width" content="200"/>
    <meta property="og:image:height" content="200"/>

    <!-- Current locale and alternate locales -->
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}" />
    <meta property="og:locale:alternate" content="es_ES" />

    <!-- Og Meta Tags -->
    <link rel="canonical" href="{{ $raffle->getUrl() }}"/>
    <meta property="og:site_name" content="{{ $raffle->title }} - {{ config('app.name') }}"/>
    <meta property="og:url" content="{{ $raffle->getUrl() }}"/>
    <meta property="og:image" content="{{ $firstImage }}"/>

    <meta property="og:title" content="{{ $raffle->title }} - {{ config('app.name') }}"/>
    <meta property="og:description" content="{{ Str::words($description, 30) }}"/>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:image" content="{{ $firstImage }}" />
    <meta name="twitter:title" content="{{ $raffle->title }}" />
    <meta name="twitter:description" content="{{ Str::words($description, 30) }}"/> 
@endpush

<div class="pt-28 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto z-10 relative w-full flex-grow text-zinc-900 dark:text-white font-display transition-colors duration-300">
    <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-gray-400 mb-8">
        <a class="hover:text-primary transition-colors" href="{{ route('raffles.index') }}" wire:navigate>{{ __('messages.raffles') }}</a>
        <span class="material-icons text-xs">chevron_right</span>
        <span class="text-zinc-900 dark:text-white">{{ $raffle->title }}</span>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        {{-- Left Column: Image & Description --}}
        <div class="lg:col-span-7" x-data="{ activeImage: 0 }">
            {{-- Main Image --}}
            <div class="relative rounded-2xl overflow-hidden bg-white dark:bg-surface-dark border border-zinc-200 dark:border-white/10 group aspect-[4/3] lg:aspect-auto lg:h-[500px] shadow-2xl dark:shadow-none mb-6">

                
                {{-- Status Badge --}}
                <div class="absolute top-4 left-4 z-20">
                    @if($raffle->isActive())
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 dark:bg-black/60 border border-zinc-200 dark:border-white/10 backdrop-blur-md">
                            <span class="animate-pulse h-2 w-2 rounded-full bg-green-500"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-green-600 dark:text-green-400">{{ __('messages.active') }}</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 dark:bg-black/60 border border-zinc-200 dark:border-white/10 backdrop-blur-md">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-red-500 dark:text-red-400">{{ $raffle->effective_status->getLabel() }}</span>
                        </div>
                    @endif
                </div>

                {{-- Images --}}
                @if($raffle->images && count($raffle->images) > 0)
                    @foreach($raffle->images as $index => $image)
                        <img x-show="activeImage === {{ $index }}"
                             x-transition:enter="transition opacity duration-500"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             alt="{{ $raffle->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 absolute inset-0" 
                             src="{{ Storage::url($image) }}"/>
                    @endforeach
                @endif
            </div>

            {{-- Thumbnails Grid --}}
             @if($raffle->images && count($raffle->images) > 1)
                <div class="flex gap-3 overflow-x-auto py-2 px-1 mb-8 lg:justify-center no-scrollbar">
                    @foreach($raffle->images as $index => $image)
                        <button @click="activeImage = {{ $index }}"
                                :class="activeImage === {{ $index }} ? 'border-primary opacity-100 shadow-neon' : 'border-zinc-200 dark:border-white/10 opacity-60 hover:opacity-100 hover:scale-105'"
                                class="w-14 h-14 rounded-lg overflow-hidden transition-all duration-300 bg-zinc-200 dark:bg-zinc-900 border-2 shrink-0 relative group/thumb">
                            <img src="{{ Storage::url($image) }}" alt="" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover/thumb:scale-110">
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Description (Desktop) --}}
            <div class="hidden lg:block mt-8">
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-icons text-primary">description</span> {{ __('messages.description') }}
                </h3>
                <div class="bg-zinc-50 dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-2xl p-8 prose prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-gray-300 terms-content transition-colors duration-300">
                    {!! $raffle->description !!}
                </div>
            </div>
        </div>

        {{-- Right Column: Details & Purchase --}}
        <div class="lg:col-span-5 flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-3 mb-1">
                    @if($raffle->isActive())
                        <span class="bg-green-500/10 text-green-600 dark:text-green-500 border border-green-500/20 px-3 py-1 rounded text-xs font-bold uppercase tracking-wider">{{ __('messages.active') }}</span>
                    @else
                        <span class="bg-red-500/10 text-red-600 dark:text-red-500 border border-red-500/20 px-3 py-1 rounded text-xs font-bold uppercase tracking-wider">{{ $raffle->effective_status->getLabel() }}</span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight leading-none">
                    {{ $raffle->title }}
                </h1>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-4xl font-bold text-primary">{{ \App\Helpers\CurrencyHelper::format($raffle->ticket_price) }}</span>
                    <span class="text-zinc-500 dark:text-gray-500 text-lg">{{ __('messages.per_ticket') }}</span>
                </div>
            </div>

            {{-- Stats Card --}}
            <div class="bg-white dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-xl p-6 shadow-lg transition-colors duration-300">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-zinc-500 dark:text-gray-400 text-sm font-medium">{{ __('messages.sold_percentage') }}</span>
                    <span class="text-zinc-900 dark:text-white font-mono font-bold">{{ $raffle->sold_percentage }}%</span>
                </div>
                <div class="h-4 bg-zinc-200 dark:bg-[#151511] border border-zinc-300 dark:border-white/5 rounded-full overflow-hidden mb-3">
                    <div class="h-full bg-primary relative overflow-hidden shadow-[0_0_10px_rgba(244,244,37,0.5)] transition-all duration-500" style="width: {{ $raffle->sold_percentage }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-zinc-500 dark:text-gray-500">
                    <span>{{ $raffle->tickets_sold }} {{ __('messages.sold') }}</span>
                    <span>{{ $raffle->tickets_available }} {{ __('messages.tickets_remaining') }}</span>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 gap-4 text-sm font-sans">
                <div class="flex items-center gap-3 text-zinc-600 dark:text-gray-300">
                    <span class="material-icons text-primary">calendar_today</span>
                    <div class="flex flex-col">
                        <span class="text-xs text-zinc-500 dark:text-gray-500 uppercase">{{ __('messages.ends_on') }}</span>
                        <span class="font-medium font-mono">{{ $raffle->end_date->locale(app()->getLocale())->translatedFormat('d F, Y - h:i A') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-zinc-600 dark:text-gray-300">
                    <span class="material-icons text-primary">confirmation_number</span>
                    <div class="flex flex-col">
                        <span class="text-xs text-zinc-500 dark:text-gray-500 uppercase">{{ __('messages.total_tickets') }}</span>
                        <span class="font-medium font-mono">{{ number_format($raffle->total_tickets) }}</span>
                    </div>
                </div>
            </div>

            {{-- Purchase Box --}}
            @if($raffle->isActive())
                <div class="bg-white dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-xl p-6 mt-4 shadow-lg ring-1 ring-zinc-200 dark:ring-white/5 transition-colors duration-300"
                     x-data="{ showAlert: false, alertMessage: '' }"
                     @quantity-invalid.window="showAlert = true; alertMessage = $event.detail.message || '{{ __('messages.min_tickets', ['count' => $raffle->minimum_purchase_ticket ?? 1]) }}'; setTimeout(() => showAlert = false, 3000)">
                    
                    {{-- Alert for Min/Max Qty --}}
                    <div x-show="showAlert" x-transition class="mb-4 p-3 bg-red-500/10 border border-red-500/20 text-red-500 rounded text-sm" x-text="alertMessage">
                    </div>

                    @if(($raffle->minimum_purchase_ticket ?? 1) > 1)
                        <div class="mb-4 p-3 bg-blue-500/10 border border-blue-500/20 text-blue-500 dark:text-blue-400 rounded text-sm flex items-center gap-2">
                             <span class="material-icons text-sm">info</span>
                             {{ __('messages.minimum_tickets_notice', ['count' => $raffle->minimum_purchase_ticket]) }}
                        </div>
                    @endif

                    {{-- Sold Out / Temporarily Unavailable Alert --}}
                    @if($raffle->tickets_available <= 0)
                        <div class="mb-4 p-4 bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 rounded-xl text-sm flex items-center gap-3">
                            <span class="material-icons text-xl">hourglass_empty</span>
                            <div class="flex flex-col">
                                <span class="font-bold">{{ __('messages.tickets_not_available') }}</span>
                                <span>{{ __('messages.pending_reservations_notice') }}</span>
                            </div>
                        </div>
                    @endif

                    <label class="block text-zinc-500 dark:text-gray-400 text-sm font-bold mb-4">{{ __('messages.select_quantity') }}</label>
                    <div class="flex items-center gap-4 mb-2">
                        <button wire:click="decrementQuantity" 
                                @if($raffle->tickets_available <= 0 || $quantity <= ($raffle->minimum_purchase_ticket ?? 1)) disabled @endif
                                class="w-12 h-12 rounded-lg bg-zinc-100 dark:bg-[#151511] hover:bg-zinc-200 dark:hover:bg-white/5 border border-zinc-200 dark:border-white/10 flex items-center justify-center transition-colors text-zinc-900 dark:text-white hover:text-primary disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="material-icons">remove</span>
                        </button>
                        <input class="w-full h-12 bg-zinc-100 dark:bg-[#151511] border border-zinc-200 dark:border-white/10 rounded-lg text-center text-xl font-bold text-zinc-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none disabled:opacity-50 disabled:cursor-not-allowed" 
                               type="number" 
                               wire:model.live.debounce.500ms="quantity" 
                               @if($raffle->tickets_available <= 0) disabled @endif
                               min="{{ $raffle->minimum_purchase_ticket ?? 1 }}" max="{{ \App\Models\Setting::get('max_purchase_tickets', 100) }}"/>
                        <button wire:click="incrementQuantity" 
                                @if($raffle->tickets_available <= 0) disabled @endif
                                class="w-12 h-12 rounded-lg bg-zinc-100 dark:bg-[#151511] hover:bg-zinc-200 dark:hover:bg-white/5 border border-zinc-200 dark:border-white/10 flex items-center justify-center transition-colors text-zinc-900 dark:text-white hover:text-primary disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="material-icons">add</span>
                        </button>
                    </div>
                    <div wire:loading wire:target="quantity, decrementQuantity, incrementQuantity" class="w-full mb-6 text-center">
                        <div class="inline-flex items-center justify-center gap-2">
                            <div class="w-4 h-4 rounded-full border-2 border-current border-t-transparent text-primary animate-spin"></div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-gray-400">{{ __('messages.checking_availability') }}</span>
                        </div>
                    </div>
                    <div wire:loading.remove wire:target="quantity, decrementQuantity, incrementQuantity" class="h-6 mb-4"></div>
                    
                    <div class="flex justify-between items-center mb-6 pt-6 border-t border-zinc-200 dark:border-white/5">
                        <span class="text-zinc-500 dark:text-gray-400">{{ __('messages.total') }}:</span>
                        <span class="text-2xl font-bold text-zinc-900 dark:text-white">{{ \App\Helpers\CurrencyHelper::format($this->getRawTotal()) }}</span>
                    </div>
                    
                    <button wire:click="openModal" wire:loading.attr="disabled"
                            @if($raffle->tickets_available <= 0) disabled @endif
                            class="w-full bg-primary hover:bg-primary-hover text-white dark:text-black font-bold text-lg py-4 rounded-xl shadow-neon transition-all duration-300 flex items-center justify-center gap-2 group cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                        <span wire:loading.remove wire:target="openModal">
                            {{ $raffle->tickets_available <= 0 ? __('messages.no_tickets_available') : __('messages.buy_tickets') }}
                        </span>
                        <div wire:loading wire:target="openModal" class="w-6 h-6 border-3 border-current border-t-transparent rounded-full animate-spin"></div>
                        @if($raffle->tickets_available > 0)
                            <span wire:loading.remove wire:target="openModal" class="material-icons text-xl group-hover:translate-x-1 transition-transform">confirmation_number</span>
                        @endif
                    </button>
                </div>
            @else
                <div class="p-6 bg-white dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-xl text-center text-zinc-500 dark:text-gray-400 mt-4">
                    {{ __('messages.raffle_ended') }}
                </div>
            @endif

            {{-- Description (Mobile) --}}
            <div class="lg:hidden mt-8">
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.description') }}</h3>
                <div class="bg-zinc-50 dark:bg-surface-dark border border-zinc-200 dark:border-white/10 rounded-xl p-6 prose prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-gray-300 terms-content">
                    {!! $raffle->description !!}
                </div>
            </div>
        </div>
    </div>

    {{-- Purchase Modal (Reused existing logic, updated styles to match dark theme) --}}
    @if($showModal)
        @teleport('body')
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" 
                 x-data="{ show: true }" 
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
             
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="show = false; $wire.closeModal()"></div>
            <div class="relative bg-white dark:bg-surface-dark rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden border border-zinc-200 dark:border-white/10 text-zinc-900 dark:text-white transition-colors duration-300"
                 x-on:scroll-top-modal.window="$refs.modalScrollContainer.scrollTop = 0"
            >
                {{-- Modal Header --}}
                <div class="bg-zinc-50 dark:bg-surface-dark px-6 py-5 flex items-center justify-between z-10 rounded-t-3xl border-b border-zinc-200 dark:border-white/5 shrink-0 transition-colors duration-300">
                    <div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.purchase_tickets') }}</h3>
                        <p class="text-zinc-500 dark:text-gray-400 text-sm">{{ $raffle->title }}</p>
                    </div>
                    <button @click="show = false; $wire.closeModal()" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-zinc-200 dark:hover:bg-white/5 transition-colors text-zinc-400 dark:text-gray-400 hover:text-zinc-900 dark:hover:text-white">
                        <span class="material-icons">close</span>
                    </button>
                </div>
                
                {{-- Rest of modal content with scrollable area --}}
                <div class="p-6 overflow-y-auto flex-1 custom-scrollbar" x-ref="modalScrollContainer">
                     {{-- Steps --}}
                     <div class="flex items-center mb-12">
                        @for($i = 1; $i <= 3; $i++)
                            {{-- Step Node --}}
                            <div class="flex flex-col items-center relative">
                                <div @class([
                                    'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300 z-10',
                                    'bg-primary text-white dark:text-black shadow-lg shadow-neon' => $step >= $i,
                                    'bg-zinc-200 dark:bg-white/10 text-zinc-500 dark:text-gray-500' => $step < $i,
                                ])>{{ $i }}</div>
                                <span @class([
                                    'absolute top-10 whitespace-nowrap text-[10px] font-bold uppercase tracking-wider transition-all duration-300',
                                    'text-primary' => $step >= $i,
                                    'text-zinc-400 dark:text-gray-500' => $step < $i,
                                ])>{{ __('messages.step_' . $i . '_title') }}</span>
                            </div>

                            {{-- Connector Line --}}
                            @if($i < 3)
                                <div class="flex-1 h-0.5 bg-zinc-200 dark:bg-white/10 mx-2 rounded overflow-hidden">
                                    <div @class([
                                        'h-full bg-primary transition-all duration-500',
                                        'w-full' => $step > $i,
                                        'w-0' => $step <= $i,
                                    ])></div>
                                </div>
                            @endif
                        @endfor
                    </div>

                    @if($showSuccess)
                         <div class="text-center space-y-5 py-4">
                            <div class="w-20 h-20 mx-auto bg-green-500/20 rounded-full flex items-center justify-center">
                                <span class="material-icons text-green-500 dark:text-green-400 text-4xl">check</span>
                            </div>
                            <h4 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.purchase_success') }}</h4>
                            <p class="text-zinc-500 dark:text-gray-400">{{ __('messages.purchase_success_message') }}</p>
                            <button wire:click="closeModal" class="px-8 py-3 bg-primary text-white dark:text-black font-bold rounded-xl hover:bg-primary-hover transition-all shadow-neon">{{ __('messages.close') }}</button>
                        </div>
                    @elseif($step === 1)
                        {{-- Step 1 Details --}}
                        <div class="space-y-5">
                            <div class="bg-zinc-50 dark:bg-black/20 p-5 rounded-2xl space-y-4 border border-zinc-200 dark:border-white/5">
                                <div class="flex justify-between"><span class="text-zinc-500 dark:text-gray-400">{{ __('messages.quantity') }}</span><span class="text-primary font-bold">{{ $quantity }}</span></div>
                                <div class="flex justify-between"><span class="text-zinc-500 dark:text-gray-400">{{ __('messages.total') }}</span><span class="text-primary font-bold text-xl">{{ \App\Helpers\CurrencyHelper::format($this->getRawTotal()) }}</span></div>
                            </div>
                            <button wire:click="nextStep" wire:loading.attr="disabled" class="w-full py-3.5 bg-primary text-white dark:text-black font-bold rounded-xl hover:bg-primary-hover transition-all shadow-neon flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="nextStep">{{ __('messages.next') }} →</span>
                                <div wire:loading wire:target="nextStep" class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                            </button>
                        </div>
                    @elseif($step === 2)
                        {{-- Step 2 Payment --}}
                        <div class="space-y-4">
                            @foreach($paymentMethods as $method)
                                <label class="block cursor-pointer" 
                                       @if($selectedPaymentMethodId != $method->id) wire:click.prevent="selectPaymentMethod({{ $method->id }})" @endif>
                                    <input type="radio" name="payment_method" value="{{ $method->id }}" 
                                           class="sr-only" 
                                           @if($selectedPaymentMethodId == $method->id) checked @endif>
                                    <div @class([
                                        'p-4 border rounded-2xl transition-all',
                                        'border-primary bg-primary/10' => $selectedPaymentMethodId == $method->id,
                                        'border-zinc-200 dark:border-white/10 bg-zinc-50 dark:bg-white/5' => $selectedPaymentMethodId != $method->id,
                                    ])>
                                         <div class="flex items-center gap-4 w-full">
                                            @if($method->logo)
                                                <img src="{{ Storage::url($method->logo) }}" class="w-10 h-10 rounded">
                                            @else
                                                <div class="w-10 h-10 rounded bg-zinc-200 dark:bg-white/10 flex items-center justify-center"><span class="material-icons text-zinc-500 dark:text-white">payments</span></div>
                                            @endif
                                            <span class="font-bold text-zinc-900 dark:text-white">{{ $method->name }}</span>
                                            
                                            {{-- Spinner TARGETED to this specific method selection --}}
                                            <div wire:loading wire:target="selectPaymentMethod({{ $method->id }})" class="ml-auto">
                                                <div class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                            </div>
                                        </div>
                                        @if($selectedPaymentMethodId == $method->id)
                                            <div class="mt-4 pt-4 border-t border-primary/20 text-zinc-600 dark:text-gray-400 text-sm" x-data="{ copied: false }">
                                                <div id="instructions-{{ $method->id }}" class="text-zinc-900 dark:text-white/90 leading-relaxed mb-4 font-medium">
                                                    {!! $method->instructions !!}
                                                </div>

                                                <div class="flex justify-start mb-3">
                                                    <button type="button" 
                                                            @click="
                                                                const text = document.getElementById('instructions-{{ $method->id }}').innerText.trim();
                                                                navigator.clipboard.writeText(text);
                                                                copied = true;
                                                                setTimeout(() => copied = false, 2000);
                                                            "
                                                            class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest bg-primary text-white dark:text-black hover:bg-primary-hover transition-all px-4 py-2 rounded-lg shadow-lg active:scale-95">
                                                        <span class="material-icons text-sm" x-text="copied ? 'check' : 'content_copy'"></span>
                                                        <span x-text="copied ? '{{ __('messages.copied') }}' : '{{ __('messages.copy_data') }}'"></span>
                                                    </button>
                                                </div>

                                                @if($method->exchange_rate != 1)
                                                    <div class="mt-4 p-3 bg-white dark:bg-white/5 border border-zinc-200 dark:border-white/10 rounded-xl flex justify-between items-center">
                                                        <div class="flex flex-col">
                                                            <span class="text-[10px] text-zinc-500 dark:text-gray-500 uppercase font-bold">{{ __('messages.total_to_pay') }}</span>
                                                            <span class="text-primary font-bold text-base">{{ number_format($this->getRawTotal() * $method->exchange_rate, 2) }} {{ $method->currency_code }}</span>
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="text-[10px] text-zinc-500 dark:text-gray-500 uppercase font-bold">{{ __('messages.rate') }}</span>
                                                            <p class="text-zinc-700 dark:text-white text-xs font-mono">1 USD = {{ number_format($method->exchange_rate, 2) }} {{ $method->currency_code }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                             <div class="flex gap-3 mt-4">
                                <button wire:click="previousStep" wire:loading.attr="disabled" class="flex-1 py-3.5 bg-zinc-200 dark:bg-white/10 text-zinc-700 dark:text-white font-semibold rounded-xl hover:bg-zinc-300 dark:hover:bg-white/20 transition-colors flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="previousStep">← {{ __('messages.back') }}</span>
                                    <div wire:loading wire:target="previousStep" class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                </button>
                                <button wire:click="nextStep" 
                                        wire:loading.attr="disabled" 
                                        class="flex-1 py-3.5 font-bold rounded-xl transition-all duration-300 flex items-center justify-center gap-2 group {{ $selectedPaymentMethodId ? 'bg-primary text-white dark:text-black shadow-neon cursor-pointer hover:bg-primary-hover' : 'bg-zinc-100 dark:bg-white/5 text-zinc-400 dark:text-gray-500 cursor-not-allowed border border-zinc-200 dark:border-white/5 opacity-50' }}" 
                                        @if(!$selectedPaymentMethodId) disabled @endif>
                                    <span wire:loading.remove wire:target="nextStep">{{ __('messages.next') }} →</span>
                                    <div wire:loading wire:target="nextStep" class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                </button>
                            </div>
                        </div>
                    @elseif($step === 3)
                        {{-- Step 3 Form --}}




                         <form wire:submit="submitPurchase" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-gray-400 mb-1.5">{{ __('messages.full_name') }} *</label>
                                <input type="text" wire:model="fullName" required class="w-full px-4 py-3 bg-white dark:bg-black/20 border border-zinc-300 dark:border-white/10 rounded-xl text-zinc-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('fullName') border-red-500 @enderror">
                                @error('fullName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-gray-400 mb-1.5">{{ __('messages.identity_document') }} *</label>
                                <input type="text" wire:model="identityDocument" required class="w-full px-4 py-3 bg-white dark:bg-black/20 border border-zinc-300 dark:border-white/10 rounded-xl text-zinc-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('identityDocument') border-red-500 @enderror">
                                @error('identityDocument') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-gray-400 mb-1.5">{{ __('messages.email') }} *</label>
                                <input type="email" wire:model="email" required class="w-full px-4 py-3 bg-white dark:bg-black/20 border border-zinc-300 dark:border-white/10 rounded-xl text-zinc-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('email') border-red-500 @enderror">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-gray-400 mb-1.5">{{ __('messages.phone') }} *</label>
                                <input type="text" wire:model="phone" required class="w-full px-4 py-3 bg-white dark:bg-black/20 border border-zinc-300 dark:border-white/10 rounded-xl text-zinc-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('phone') border-red-500 @enderror">
                                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-gray-400 mb-1.5 flex items-center gap-1.5">
                                    {{ __('messages.reference_number') }} *
                                    <span class="material-icons text-xs text-zinc-400 dark:text-gray-500 cursor-help" title="{{ __('messages.transaction_number_info') }}">info</span>
                                </label>
                                <input type="text" wire:model="referenceNumber" required class="w-full px-4 py-3 bg-white dark:bg-black/20 border border-zinc-300 dark:border-white/10 rounded-xl text-zinc-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none @error('referenceNumber') border-red-500 @enderror">
                                @error('referenceNumber') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-6 p-4 bg-zinc-50 dark:bg-white/5 border border-zinc-200 dark:border-white/10 rounded-2xl space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-zinc-500 dark:text-gray-400">{{ __('messages.total_amount') }}</span>
                                    <span class="text-zinc-900 dark:text-white font-bold">{{ \App\Helpers\CurrencyHelper::format($this->getRawTotal()) }} {{ \App\Models\Setting::get('currency_code') }}</span>
                                </div>
                                @if($this->converted_total)
                                    <div class="flex justify-between items-center pt-3 border-t border-zinc-200 dark:border-white/5">
                                        <span class="text-zinc-500 dark:text-gray-400">{{ __('messages.amount_charged') }}</span>
                                        <span class="text-primary font-bold text-lg">{{ $this->converted_total }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Ticket Availability Alert --}}
                            @error('quantity')
                                <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800 flex items-center gap-2" role="alert">
                                    <span class="material-icons text-base">warning</span>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror

                             <div class="flex gap-3 mt-4">
                                <button type="button" wire:click="previousStep" wire:loading.attr="disabled" class="flex-1 py-3.5 bg-zinc-200 dark:bg-white/10 text-zinc-700 dark:text-white font-semibold rounded-xl hover:bg-zinc-300 dark:hover:bg-white/20 transition-colors flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="previousStep">← {{ __('messages.back') }}</span>
                                    <div wire:loading wire:target="previousStep" class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                </button>
                                <button type="submit" wire:loading.attr="disabled" class="flex-1 py-3.5 bg-primary text-white dark:text-black font-bold rounded-xl hover:bg-primary-hover transition-all shadow-neon flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="submitPurchase">{{ __('messages.send') }}</span>
                                    <div wire:loading wire:target="submitPurchase" class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>

@script
<script>
    $wire.on('payment-submitted', () => {
        const duration = 5 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);
            confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
            confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
        }, 250);
    });
</script>
@endscript
