@extends('layouts.indexLayout')

@section('meta')
    @include('partials.metaIndex')
@endsection

@section('content')
    @include('partials.topbar')
    <div class="max-w-5xl mx-auto " x-data="{ open: [] }">
        <img src="{{ asset('assets/landy-hero.jpeg') }}" alt="Tim MapBiomas Indonesia" class="w-full sm:h-[280px] h-full object-cover object-top">
        <div class="max-w-2xl mx-auto mt-4 px-4">
            <h1 class="mt-6 text-xl">FAQ</h1>
            <?php foreach ($data as $item): ?>
            <div
                class="<?php echo $item->id == 1 ? 'border-t border-b' : 'border-b'; ?> py-4 px-4 border-landy mt-12"
                x-data="{ icon: false }"

                @mouseover="icon = true"
                @mouseover.away="icon = false"
                :class="{ 'bg-landy-2': open.includes('{{$item->id}}') }"
            >
                <div class="flex justify-between ">
                    <a class="text-black font-semibold cursor-pointer"
                        href="#"
                        @click.prevent="icon = false"
                    @click="open.includes('{{$item->id}}') ? open = open.filter(i => i !== '{{$item->id}}') : open.push('{{$item->id}}')">
                        {{ $item->question }}
                    </a>
                    <svg
                        x-show="icon"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <div x-show="open.includes('{{$item->id}}')" x-transition class="mt-2" style="display: none !important;">
                    <div class="mt-6 prose max-w-none text-sm leading-relaxed break-words">
                        {!! $item->answer !!}
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>



    @include('partials.footer')
@endsection
