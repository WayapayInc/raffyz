<x-layouts::app.sidebar :title="file_exists(storage_path('installed')) ? ($title ?? null) : 'Installer'">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
