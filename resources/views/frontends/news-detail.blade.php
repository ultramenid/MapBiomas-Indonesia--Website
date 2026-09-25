@extends('layouts.indexLayout')

@section('content')
    @include('partials.topbar')

    <article>
        @if ($news->image_path)
            <img src="{{ media_url($news->image_path) }}" alt="{{ $news->bi('title') }}"
                 class="w-full h-[280px] object-cover object-top">
        @endif

        <div class="max-w-3xl mx-auto px-4 py-12">
            <p class="text-center font-light text-sm text-gray-500">{{ $news->published_at?->translatedFormat('F j, Y') }}</p>
            <h1 class="mt-2 font-bold sm:text-3xl text-2xl text-center uppercase">{{ $news->bi('title') }}</h1>

            <div class="mt-8 prose prose-sm sm:prose-base max-w-none frontend font-light">
                {!! $news->bi('content') !!}
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('index', app()->getLocale()) }}" class="font-bold text-sm hover:text-fire">&larr; {{ __('Kembali ke beranda') }}</a>
            </div>
        </div>
    </article>

    @include('partials.footer')
@endsection
