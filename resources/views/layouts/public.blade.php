<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription ?? __('seo.description') }}">
    <meta name="keywords" content="{{ __('seo.keywords') }}">
    @stack('meta')

    <title>
        @if(isset($title))
            {{ $title }} - {{ \App\Models\Setting::get('platform_name', 'Raffyz') }}
        @else
            {{ \App\Models\Setting::get('platform_name', 'Raffyz') }} - {{ __('seo.slogan') }}
        @endif
    </title>

    <script>
        function applyInitialTheme() {
            const defaultTheme = "{{ \App\Models\Setting::get('default_theme', 'system') }}";
            const storedTheme = localStorage.getItem('darkMode');
            let isDark;

            if (storedTheme !== null) {
                isDark = storedTheme === 'true';
            } else {
                if (defaultTheme === 'system') {
                    isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                } else {
                    isDark = defaultTheme === 'dark';
                }
            }

            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Apply immediately
        applyInitialTheme();

        // Re-apply when Livewire navigates (for SPA-like feel with wire:navigate)
        document.addEventListener('livewire:navigated', applyInitialTheme);
    </script>

    @php
        $favicon = \App\Models\Setting::get('favicon');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ Storage::url($favicon) }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    @php
        $primaryColor = \App\Models\Setting::get('primary_color', '#7c3aed'); // Default violet-600
        // Determine contrasting color for selection
        // Simple logic: if user picks a very light primary color, text should be black, else white. 
        // For now, keeping text-black for selection on primary bg as per original design, or can adjust.
    @endphp
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --primary-color-hover: color-mix(in srgb, var(--primary-color), black 10%);
            --primary-color-soft: color-mix(in srgb, var(--primary-color), transparent 85%);
            --bg-primary: {{ $primaryColor }};
        }
        .gaming-grid-bg {
            background-image: radial-gradient(var(--pattern-color, #333) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.1;
        }
        html:not(.dark) .gaming-grid-bg {
            --pattern-color: #000;
            opacity: 0.05;
        }
        html.dark .gaming-grid-bg {
             --pattern-color: #333;
             opacity: 0.1;
        }
        
        /* Livewire 3 & NProgress Navigation Progress Bar */
        .livewire-progress-bar,
        #nprogress .bar,
        [wire\:progress-bar] {
            background: {{ $primaryColor }} !important;
            background-color: {{ $primaryColor }} !important;
            height: 3px !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px {{ $primaryColor }}, 0 0 5px {{ $primaryColor }} !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-display min-h-screen bg-zinc-50 dark:bg-[#12120e] text-zinc-900 dark:text-white selection:bg-[var(--primary-color)] selection:text-black overflow-x-hidden flex flex-col transition-colors duration-300">
    <div class="fixed inset-0 gaming-grid-bg pointer-events-none z-0"></div>

    {{-- Navbar --}}
    <x-public.navbar />

    {{-- Main Content --}}
    <main class="min-h-[calc(100vh-160px)]">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-public.footer />

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    @livewireScripts
</body>
</html>
