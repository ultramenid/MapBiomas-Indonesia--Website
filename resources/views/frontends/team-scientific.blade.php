@extends('layouts.indexLayout')

@section('meta')
    @include('partials.metaIndex')
@endsection

@section('content')
    @include('partials.topbar')
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="font-semibold text-center uppercase">{{__('Komite Penasihat Akademik') }}</h1>
        <div class="sm:max-w-4xl w-full  ">
            <p class="mt-4 font-light leading-relaxed">{{__('Mengingat pentingnya kualitas data dan peta yang dihasilkan, MapBiomas Indonesia memandang perlu keberadaan Komite Penasihat Akademik (Scientific Advisory Committee - SAC) yang berperan memberikan masukan-masukan kepada MapBiomas Indonesia agar senantiasa selaras dengan prinsip dan/atau metode ilmiah.') }}</p>
            <p class="mt-4 font-light leading-relaxed">{{__('Oleh karena itu, pada 2023–sebelum rilis Koleksi 2–MapBiomas Indonesia membentuk Komite Penasihat Akademik yang terdiri atas pakar dan praktisi yang relevan. Keanggotaan komite ini dipilih dengan pertimbangan (i) keterwakilan geografis, (ii) keilmuan yang saling melengkapi, (iii) gender balance, dan (iv) multipihak, dan dengan durasi 2 tahun.') }}</p>
            <p class="mt-4 font-light leading-relaxed">{{__('Anggota Komite Penasihat Akademik MapBiomas Indonesia 2023-2025 adalah sebagai berikut:') }}</p>
        </div>

        {{-- sac --}}
        <div class="flex flex-wrap justify-between gap-2 mt-4 px-4">
            @foreach ($members as $member)
                <div class="flex lg:flex-row flex-col lg:items-start items-center gap-6 mt-12 lg:w-[48%] w-full">
                    <div class="w-52 h-52 shrink-0">
                        @if ($member->photo_path)
                            <img src="{{ media_url($member->photo_path) }}" alt="{{ $member->name }}"
                                 class="w-full h-full object-cover object-top rounded-full bg-tim" />
                        @endif
                    </div>

                    <div class="lg:w-6/12 w-full">
                        <h3 class="font-semibold text-sm uppercase">{{ strtoupper($member->name) }}</h3>
                        @if ($member->bi('bio'))
                            <p class="text-gray-700 text-sm leading-relaxed mt-1 font-light">{{ $member->bi('bio') }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    @include('partials.footer')
@endsection
