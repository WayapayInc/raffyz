<footer class="bg-zinc-100 dark:bg-surface-dark border-t border-zinc-200 dark:border-white/10 mt-auto transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            {{-- Brand Column --}}
            <div class="col-span-1 md:col-span-1 flex flex-col gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    @php
                        $logoLight = \App\Models\Setting::get('logo_light');
                        $logoDark = \App\Models\Setting::get('logo_dark');
                    @endphp
                    
                    @if($logoLight)
                        <img src="{{ Storage::url($logoLight) }}" alt="{{ config('app.name') }}" class="h-10 w-auto block dark:hidden">
                    @endif
                    
                    @if($logoDark)
                        <img src="{{ Storage::url($logoDark) }}" alt="{{ config('app.name') }}" class="h-10 w-auto hidden dark:block">
                    @endif

                    @if(!$logoLight && !$logoDark)
                        <div class="h-8 w-8 bg-primary rounded flex items-center justify-center shadow-neon">
                            <span class="material-icons text-black font-bold text-base">bolt</span>
                        </div>
                        <span class="font-bold text-xl tracking-tighter text-zinc-900 dark:text-white">LOOT<span class="text-primary">BOX</span></span>
                    @endif
                </a>
                <p class="text-zinc-600 dark:text-gray-400 text-sm leading-relaxed">
                    {{ \App\Models\Setting::get('platform_name', 'LOOTBOX') }} - {{ __('seo.slogan') }}
                </p>
                <div class="flex gap-3">
                    @php
                        $socials = [
                            'social_facebook' => ['icon' => 'facebook', 'label' => 'Facebook'],
                            'social_instagram' => ['icon' => 'instagram', 'label' => 'Instagram'],
                            'social_twitter' => ['icon' => 'content_copy', 'label' => 'X'],
                            'social_tiktok' => ['icon' => 'tiktok', 'label' => 'TikTok'],
                        ];
                    @endphp
                    @foreach($socials as $key => $social)
                        @php $url = \App\Models\Setting::get($key); @endphp
                        @if($url)
                            <a href="{{ $url }}" target="_blank" class="h-9 w-9 rounded-lg bg-zinc-200 dark:bg-white/5 hover:bg-primary/20 border border-zinc-300 dark:border-white/10 flex items-center justify-center transition-all group">
                                @if($social['icon'] === 'facebook')
                                    <svg class="w-5 h-5 text-zinc-500 dark:text-gray-400 group-hover:text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3l-.5 3H13v6.8c4.56-.93 8-4.96 8-9.8z"/></svg>
                                @elseif($social['icon'] === 'instagram')
                                    <svg class="w-5 h-5 text-zinc-500 dark:text-gray-400 group-hover:text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                @elseif($social['icon'] === 'content_copy')
                                    <svg class="w-4 h-4 text-zinc-500 dark:text-gray-400 group-hover:text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                @elseif($social['icon'] === 'tiktok')
                                    <svg class="w-5 h-5 text-zinc-500 dark:text-gray-400 group-hover:text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31 0 2.591.357 3.703 1.037.012.012.022.023.033.035a7.483 7.483 0 0 0 .151 1.1c.144.606.407 1.178.775 1.68.514.7 1.2 1.25 1.99 1.61.854.39 1.785.58 2.731.56v3.31c-.134.004-.268.006-.403.006a8.82 8.82 0 0 1-5.216-1.7v7.8a7.448 7.448 0 1 1-7.448-7.447c.18 0 .36.006.538.019v3.38a4.068 4.068 0 1 0 3.52 4.048V0h3.586z"/></svg>
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- Footer Logos --}}
                @php
                    $footerLogos = \App\Models\Setting::get('footer_logos');
                    if (is_string($footerLogos)) {
                        $footerLogos = json_decode($footerLogos, true);
                    }
                @endphp
                @if(is_array($footerLogos) && count($footerLogos) > 0)
                    <div class="flex flex-wrap gap-4 mt-6">
                        @foreach($footerLogos as $logo)
                             <img src="{{ Storage::url($logo) }}" alt="Partner Logo" class="h-8 w-auto">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Navigation Column --}}
            <div class="col-span-1">
                <h3 class="text-zinc-900 dark:text-white font-bold mb-6 uppercase text-xs tracking-[0.2em] opacity-50">{{ __('messages.links') }}</h3>
                <ul class="flex flex-col gap-4">
                    <li><a href="{{ route('home') }}" class="text-zinc-600 dark:text-gray-400 hover:text-primary transition-colors text-sm flex items-center gap-2 group" wire:navigate><span class="h-1 w-1 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>{{ __('messages.home') }}</a></li>
                    <li><a href="{{ route('raffles.index') }}" class="text-zinc-600 dark:text-gray-400 hover:text-primary transition-colors text-sm flex items-center gap-2 group" wire:navigate><span class="h-1 w-1 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>{{ __('messages.raffles') }}</a></li>
                    @php
                        $termsPage = \App\Models\Page::where('slug', 'terms-and-conditions')->first();
                    @endphp
                    @if($termsPage)
                        <li><a href="{{ route('terms') }}" class="text-zinc-600 dark:text-gray-400 hover:text-primary transition-colors text-sm flex items-center gap-2 group" wire:navigate><span class="h-1 w-1 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>{{ __('messages.terms_and_conditions') }}</a></li>
                    @endif
                    <li><a href="{{ route('my-tickets') }}" class="text-zinc-600 dark:text-gray-400 hover:text-primary transition-colors text-sm flex items-center gap-2 group" wire:navigate><span class="h-1 w-1 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>{{ __('messages.view_my_tickets') }}</a></li>
                </ul>
            </div>

            {{-- Contact Column --}}
            <div class="col-span-1">
                <h3 class="text-zinc-900 dark:text-white font-bold mb-6 uppercase text-xs tracking-[0.2em] opacity-50">{{ __('messages.contact_info') }}</h3>
                <ul class="flex flex-col gap-4">
                    @php $email = \App\Models\Setting::get('contact_email'); @endphp
                    @if($email)
                        <li class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-lg bg-zinc-200 dark:bg-white/5 flex items-center justify-center border border-zinc-300 dark:border-white/10 text-primary">
                                <span class="material-icons text-base">email</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-zinc-500 dark:text-gray-500 uppercase font-bold tracking-wider">{{ __('messages.email') }}</span>
                                <a href="mailto:{{ $email }}" class="text-zinc-700 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">{{ $email }}</a>
                            </div>
                        </li>
                    @endif

                    @php $whatsapp = \App\Models\Setting::get('contact_whatsapp'); @endphp
                    @if($whatsapp)
                        <li class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-lg bg-zinc-200 dark:bg-white/5 flex items-center justify-center border border-zinc-300 dark:border-white/10 text-primary">
                                <span class="material-icons text-base">phone</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-zinc-500 dark:text-gray-500 uppercase font-bold tracking-wider">{{ __('messages.whatsapp') }}</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" class="text-zinc-700 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">{{ $whatsapp }}</a>
                            </div>
                        </li>
                    @endif

                    @php $telegram = \App\Models\Setting::get('contact_telegram'); @endphp
                    @if($telegram)
                        <li class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-lg bg-zinc-200 dark:bg-white/5 flex items-center justify-center border border-zinc-300 dark:border-white/10 text-primary">
                                <span class="material-icons text-base">send</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-zinc-500 dark:text-gray-500 uppercase font-bold tracking-wider">{{ __('messages.telegram') }}</span>
                                <a href="https://t.me/{{ str_replace('@', '', $telegram) }}" target="_blank" class="text-zinc-700 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">{{ $telegram }}</a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-zinc-200 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-zinc-500 dark:text-gray-500 text-xs text-center md:text-left">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('platform_name', config('app.name')) }}. {{ __('messages.all_rights_reserved') }}
            </p>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 px-3 py-1 bg-zinc-200 dark:bg-white/5 rounded-full border border-zinc-300 dark:border-white/5">
                    <span class="h-1.5 w-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] text-zinc-500 dark:text-gray-400 font-bold uppercase tracking-widest">{{ __('messages.system_online') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-zinc-600 dark:text-gray-600 text-[10px] uppercase font-medium tracking-tighter italic">{{ __('messages.powered_by') }}</span>
                    <span class="text-zinc-400 dark:text-gray-400 text-xs font-bold leading-none">{{ config('app.name') }}</span>
                </div>
            </div>
        </div>
    </div>
</footer>
