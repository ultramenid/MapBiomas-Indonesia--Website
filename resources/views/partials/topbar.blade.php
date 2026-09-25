<div class="max-w-6xl mx-auto flex items-center justify-between py-8 px-4 ">
    <div x-data="{ open: false }" class="relative select-none">
        <svg @click="open = !open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-red-500 cursor-pointer">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
        </svg>
        <div style="display: none !important;" class="absolute left-0 mt-2 w-44 bg-white rounded-md shadow-lg z-10 select-none"
            x-show="open"
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-50"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            <a href="{{ route('about', [app()->getLocale()]) }}" class="block px-4 py-1 text-gray-800 hover:bg-gray-100">{{ __('Tentang') }}</a>

            <!-- Submenu container -->
            <div x-data="{ subOpen: false }" class="relative group">
                <button @mouseover="subOpen = true" class="w-full flex items-center justify-between px-4 py-1 text-gray-800 hover:bg-gray-100 cursor-pointer">
                    <span>{{__('Tim') }}</span>
                    <!-- Submenu indicator icon -->
                    <svg class="w-4 h-4 ml-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Submenu -->
                <div x-show="subOpen"
                    @click.away="subOpen = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-50"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="mt-2 w-full bg-white rounded-md shadow-lg z-20 sm:absolute sm:left-full sm:top-0 sm:ml-2 sm:mt-0 sm:w-52">
                    <a href="{{ route('team-technic', [app()->getLocale()]) }}" class="block px-4 py-1 text-gray-800 hover:bg-gray-100">{{ __('Tim Teknis') }}</a>
                    <a href="{{ route('team-scientific', [app()->getLocale()]) }}" class="block px-4 py-1 text-gray-800 hover:bg-gray-100">Scientific Advisory</a>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('index', [app()->getLocale()]) }}">
        <img src="{{ asset('assets/logo mapbiomas.png') }}" alt="Mapbiomas Indonesia" class="sm:h-20 h-10 select-none">
    </a>

    <!-- Language switch -->
    @php
        $langParams = fn (string $lang) => array_merge(Route::current()?->parameters() ?? [], ['lang' => $lang]);
    @endphp
    <div class="sm:flex gap-1 hidden">
        <a href="{{ route(Route::currentRouteName(), $langParams('id')) }}" class="text-sm @if(App::getLocale() == 'id') font-semibold @else font-light text-gray-500 @endif">Indonesia</a>
        <a href="#" class="">|</a>
        <a href="{{ route(Route::currentRouteName(), $langParams('en')) }}" class="text-sm @if(App::getLocale() == 'en') font-semibold @else font-light text-gray-500 @endif">English</a>
    </div>

    <div class="sm:hidden gap-1 flex">
        <a href="{{ route(Route::currentRouteName(), $langParams('id')) }}" class="text-sm @if(App::getLocale() == 'id') font-semibold @else font-light text-gray-500 @endif">ID</a>
        <a href="#" class="">|</a>
        <a href="{{ route(Route::currentRouteName(), $langParams('en')) }}" class="text-sm @if(App::getLocale() == 'en') font-semibold @else font-light text-gray-500 @endif">EN</a>
    </div>
</div>
