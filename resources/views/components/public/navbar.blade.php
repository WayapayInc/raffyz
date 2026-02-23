<nav x-data="{ 
        darkMode: (function() {
            const stored = localStorage.getItem('darkMode');
            if (stored !== null) return stored === 'true';
            
            const defaultTheme = '{{ \App\Models\Setting::get('default_theme', 'system') }}';
            if (defaultTheme === 'system') {
                return window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            return defaultTheme === 'dark';
        })(),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            this.applyTheme();
        },
        applyTheme() {
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }" 
    x-init="applyTheme()"
    class="fixed top-0 w-full z-50">
    
    {{-- Navbar Background with Blur --}}
    <div class="absolute inset-0 bg-white/90 dark:bg-[#12120e]/80 backdrop-blur-md border-b border-zinc-200 dark:border-white/10 transition-colors duration-300"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 group cursor-pointer" wire:navigate>
                @php
                    $logoLight = \App\Models\Setting::get('logo_light');
                    $logoDark = \App\Models\Setting::get('logo_dark');
                @endphp
                
                @if($logoLight)
                    <img src="{{ Storage::url($logoLight) }}" alt="Logo Light" class="h-10 w-auto block dark:hidden">
                @endif
                
                @if($logoDark)
                    <img src="{{ Storage::url($logoDark) }}" alt="Logo Dark" class="h-10 w-auto hidden dark:block">
                @endif

                @if(!$logoLight && !$logoDark)
                    <div class="h-10 w-10 bg-primary rounded flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300 shadow-neon">
                        <span class="material-icons text-black font-bold">bolt</span>
                    </div>
                    <span class="font-bold text-2xl tracking-tighter text-zinc-900 dark:text-white">LOOT<span class="text-primary">BOX</span></span>
                @endif
            </a>
            
            {{-- Desktop Navigation --}}
            <div class="hidden md:flex space-x-8">
                <a class="{{ request()->routeIs('home') ? 'text-zinc-900 dark:text-white border-b-2 border-primary' : 'text-zinc-600 dark:text-gray-300' }} hover:text-primary transition-colors font-medium text-sm uppercase tracking-wide" href="{{ route('home') }}" wire:navigate>{{ __('messages.home') }}</a>
                <a class="{{ request()->routeIs('raffles.index') ? 'text-zinc-900 dark:text-white border-b-2 border-primary' : 'text-zinc-600 dark:text-gray-300' }} hover:text-primary transition-colors font-medium text-sm uppercase tracking-wide" href="{{ route('raffles.index') }}" wire:navigate>{{ __('messages.raffles') }}</a>
                <a class="{{ request()->routeIs('terms') ? 'text-zinc-900 dark:text-white border-b-2 border-primary' : 'text-zinc-600 dark:text-gray-300' }} hover:text-primary transition-colors font-medium text-sm uppercase tracking-wide" href="{{ route('terms') }}" wire:navigate>{{ __('messages.terms_and_conditions') }}</a>
                <a class="{{ request()->routeIs('my-tickets') ? 'text-zinc-900 dark:text-white border-b-2 border-primary' : 'text-zinc-600 dark:text-gray-300' }} hover:text-primary transition-colors font-medium text-sm uppercase tracking-wide" href="{{ route('my-tickets') }}" wire:navigate>{{ __('messages.view_my_tickets') }}</a>
            </div>

            <div class="flex items-center space-x-4">
                <button @click="toggleTheme()" class="w-10 h-10 rounded-full flex items-center justify-center text-zinc-600 dark:text-gray-300 hover:text-primary transition-colors hover:bg-zinc-100 dark:hover:bg-white/5" :title="darkMode ? '{{ __('messages.light_mode') }}' : '{{ __('messages.dark_mode') }}'">
                    <span class="material-icons" x-text="darkMode ? 'light_mode' : 'dark_mode'"></span>
                </button>
                
                {{-- Mobile Menu Button --}}
                <button id="mobile-menu-button" class="md:hidden w-10 h-10 rounded-md flex items-center justify-center text-zinc-600 dark:text-gray-300 hover:text-primary transition-colors hover:bg-zinc-100 dark:hover:bg-white/5">
                    <span class="material-icons">menu</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Off-canvas Menu --}}
    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300 md:hidden"></div>
    
    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 bottom-0 w-[80%] max-w-xs bg-white dark:bg-[#1c1c16] border-r border-zinc-200 dark:border-white/10 h-full shadow-2xl p-6 flex flex-col z-50 transform -translate-x-full transition-transform duration-300 md:hidden overflow-y-auto text-zinc-900 dark:text-white">
        
        <div class="flex justify-between items-center mb-10">
            <span class="font-bold text-xl tracking-tighter text-zinc-900 dark:text-white">MENU</span>
            <button id="close-sidebar" class="text-zinc-500 dark:text-gray-400 hover:text-primary dark:hover:text-white transition-colors p-1">
                <span class="material-icons">close</span>
            </button>
        </div>
        
        <div class="flex flex-col space-y-6">
            <a class="{{ request()->routeIs('home') ? 'text-primary border-l-2 border-primary pl-3' : 'text-zinc-600 dark:text-gray-300 hover:text-primary' }} transition-colors font-medium text-lg uppercase tracking-wide" href="{{ route('home') }}" wire:navigate>{{ __('messages.home') }}</a>
            <a class="{{ request()->routeIs('raffles.index') ? 'text-primary border-l-2 border-primary pl-3' : 'text-zinc-600 dark:text-gray-300 hover:text-primary' }} transition-colors font-medium text-lg uppercase tracking-wide" href="{{ route('raffles.index') }}" wire:navigate>{{ __('messages.raffles') }}</a>
            <a class="{{ request()->routeIs('terms') ? 'text-primary border-l-2 border-primary pl-3' : 'text-zinc-600 dark:text-gray-300 hover:text-primary' }} transition-colors font-medium text-lg uppercase tracking-wide" href="{{ route('terms') }}" wire:navigate>{{ __('messages.terms_and_conditions') }}</a>
            <a class="{{ request()->routeIs('my-tickets') ? 'text-primary border-l-2 border-primary pl-3' : 'text-zinc-600 dark:text-gray-300 hover:text-primary' }} transition-colors font-medium text-lg uppercase tracking-wide" href="{{ route('my-tickets') }}" wire:navigate>{{ __('messages.view_my_tickets') }}</a>
        </div>
    </div>
</nav>

<script>
    {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeSidebarButton = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                // Open
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => {
                    sidebarOverlay.classList.remove('opacity-0');
                    sidebarOverlay.classList.add('opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            } else {
                // Close
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.remove('opacity-100');
                sidebarOverlay.classList.add('opacity-0');
                setTimeout(() => {
                    sidebarOverlay.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
            }
        }

        if (mobileMenuButton && closeSidebarButton && sidebar && sidebarOverlay) {
            mobileMenuButton.addEventListener('click', toggleSidebar);
            closeSidebarButton.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);

            // Close menu when clicking on a link (mobile only)
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) { // lg breakpoint is 1024px
                        toggleSidebar();
                    }
                });
            });
        }
    }
</script>
