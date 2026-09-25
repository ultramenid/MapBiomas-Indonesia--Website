@props([
    'title' => 'CMS',
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} · MapBiomas Indonesia CMS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/thumbnail.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Apply stored theme before first paint to avoid flashing
        (function () {
            var stored = localStorage.getItem('cms-theme');
            var dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (dark) document.documentElement.classList.add('dark');
        })();
        window.toggleTheme = function () {
            var isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('cms-theme', isDark ? 'dark' : 'light');
            window.dispatchEvent(new CustomEvent('cms-theme-changed'));
        };
    </script>
    <script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    @livewireStyles
    @livewireScripts
</head>
<body class="min-h-screen bg-canvas font-sans text-ink antialiased">
<div x-data="{ sidebarOpen: false }">
    {{-- Desktop sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 hidden w-60 flex-col border-r border-line bg-surface lg:flex">
        <div class="flex h-14 items-center gap-2.5 border-b border-line px-5">
            <img src="{{ asset('assets/logo mapbiomas.png') }}" alt="MapBiomas Indonesia" class="h-7 w-auto">
        </div>
        <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-5">
            @include('cms.partials.nav')
        </nav>
        <div class="border-t border-line px-5 py-3">
            <p class="text-[11px] text-ink-subtle">MapBiomas Indonesia</p>
        </div>
    </aside>

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 lg:hidden">
        <div x-show="sidebarOpen" x-transition.opacity.duration.150ms class="absolute inset-0 bg-black/50"
             @click="sidebarOpen = false"></div>
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="absolute inset-y-0 left-0 flex w-60 flex-col border-r border-line bg-surface">
            <div class="flex h-14 items-center justify-between border-b border-line px-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('assets/logo mapbiomas.png') }}" alt="MapBiomas Indonesia" class="h-7 w-auto">
                </div>
                <button type="button" @click="sidebarOpen = false" aria-label="Close menu"
                        class="inline-flex h-7 w-7 items-center justify-center rounded-md text-ink-muted hover:bg-hover hover:text-ink">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-5">
                @include('cms.partials.nav')
            </nav>
        </aside>
    </div>

    {{-- Main column --}}
    <div class="flex min-h-screen flex-col lg:pl-60">
        <header class="sticky top-0 z-20 flex h-14 items-center justify-between border-b border-line bg-canvas/80 px-4 backdrop-blur sm:px-6">
            <button type="button" @click="sidebarOpen = true" aria-label="Open menu"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-md text-ink-muted hover:bg-hover hover:text-ink lg:hidden">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            @include('cms.partials.breadcrumb')
            <div class="flex items-center gap-1.5">
                <a href="{{ url('/' . app()->getLocale()) }}" target="_blank" title="View website"
                   class="inline-flex h-8 w-8 items-center justify-center rounded-md text-ink-muted transition-colors hover:bg-hover hover:text-ink">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M15 3h6v6" />
                        <path d="M10 14 21 3" />
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                    </svg>
                </a>
                <button type="button" onclick="toggleTheme()" title="Toggle theme"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-md text-ink-muted transition-colors hover:bg-hover hover:text-ink">
                    <svg class="h-4 w-4 dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                    </svg>
                    <svg class="hidden h-4 w-4 dark:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
                    </svg>
                </button>
                <div x-data="{ open: false }" @click.outside="open = false" class="relative ml-1">
                    <button type="button" @click="open = !open" aria-label="Account menu"
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-accent text-xs font-semibold text-accent-fg">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 z-30 mt-2 w-56 origin-top-right rounded-md border border-line bg-surface py-1 shadow-lg">
                        <div class="border-b border-line px-3 py-2">
                            <p class="truncate text-sm font-medium text-ink">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-ink-muted">{{ auth()->user()->email }}</p>
                            <span class="mt-1.5 inline-flex items-center rounded-full bg-hover px-2 py-0.5 text-[11px] font-medium text-ink-muted">
                                {{ auth()->user()->roleName() }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('cms.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-sm text-danger hover:bg-hover">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <path d="m16 17 5-5-5-5M21 12H9" />
                                </svg>
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-5xl flex-1 px-4 py-8 sm:px-6">
            {{ $slot }}
        </main>
    </div>
</div>

<x-toaster-hub />
@stack('scripts')
</body>
</html>
