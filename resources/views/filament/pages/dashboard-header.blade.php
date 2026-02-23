<header class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl flex items-center">
        @lang('messages.dashboard')
    </h1>
    <div class="d-block">
        <small class="text-gray-500 font-normal text-xs">License</small>
        <small class="inline-flex items-center rounded-full bg-success-500/10 px-2 py-1 text-xs font-medium text-success-700 ring-1 ring-inset ring-success-600/20 mr-2">
            {{ \App\Models\Setting::get('license_type') }}
        </small>
        <small class="fi-color fi-color-primary fi-text-color-700 dark:fi-text-color-300 fi-badge fi-size-sm ml-2">v{{ \App\Models\Setting::get('version') }}</small>
    </div>
</header>
