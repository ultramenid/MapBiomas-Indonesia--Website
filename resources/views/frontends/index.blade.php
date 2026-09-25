@extends('layouts.indexLayout')

@section('meta')
    @include('partials.metaIndex')
@endsection

@section('content')
    @include('partials.topbar')

     <div class="max-w-6xl mx-auto px-4 ">
        <a href="#" class=" px-1 py-1  text-biru-wallacea bg-mapbiomasid text-white">{{__('News') }}</a>


        <div class="flex justify-between lg:flex-row flex-col gap-10 mt-3">
            @forelse ($news as $item)
                <div class="flex flex-col lg:w-[27%] w-full">
                    @if ($item->image_path)
                        <div>
                            <img src="{{ media_url($item->image_path) }}" alt="{{ $item->bi('title') }}"
                                 class="h-44 w-full object-cover object-center">
                        </div>
                    @endif
                    <a class="font-light text-sm mt-4">{{ $item->published_at?->translatedFormat('F Y') }}</a>
                    @if ($item->isExternal())
                        <a href="{{ $item->external_url }}" target="_blank" rel="noopener" class="font-bold hover:text-fire">{{ $item->bi('title') }}</a>
                    @else
                        <a href="{{ route('news.show', [app()->getLocale(), $item->slug]) }}" class="font-bold hover:text-fire">{{ $item->bi('title') }}</a>
                    @endif
                    <p class="mt-3 font-light text-sm">{{ $item->bi('excerpt') }}</p>
                </div>
            @empty
                <p class="font-light text-sm py-8">{{__('Tidak ada berita untuk saat ini.') }}</p>
            @endforelse
        </div>
    </div>

    <div class="max-w-6xl mx-auto flex justify-between gap-12 px-4 items-center mt-12">
        <div class="border-b border-gray-600 w-full">
        </div>
        <a class="text-xl font-semibold text-mapbiomasid">INITIATIVES</a>
        <div class="border-b border-gray-600 w-full">
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 mt-12">
        <div class="flex lg:flex-row  justify-between flex-col gap-10 mt-3">
            @forelse ($initiatives as $initiative)
                <div class="flex flex-col lg:w-[27%] w-full items-center">
                    <a href="{{ $initiative->platform_url }}" target="_blank" rel="noopener">
                        @if ($initiative->logo_path)
                            <img src="{{ media_url($initiative->logo_path) }}" alt="{{ $initiative->name }}" class="h-12">
                        @else
                            <span class="flex h-12 items-center text-xl font-bold" style="color: {{ $initiative->accent_color }}">{{ $initiative->name }}</span>
                        @endif
                    </a>
                    <p class=" mt-4 text-sm text-center font-light">{{ $initiative->bi('description') }}</p>
                </div>
            @empty
                <p class="font-light text-sm py-8 text-center w-full">{{__('Belum ada inisiatif.') }}</p>
            @endforelse
        </div>
    </div>


    @include('partials.footer')
@endsection
