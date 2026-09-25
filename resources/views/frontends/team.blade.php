@extends('layouts.indexLayout')

@section('meta')
    @include('partials.metaIndex')
@endsection

@section('content')
    @include('partials.topbar')
    <div class="max-w-5xl mx-auto px-4">

        <div class="w-full justify-center items-center">
            <h1 class="font-bold mt-24 sm:text-3xl text-2xl text-center ">TIM TEKNIS</h1>
        </div>

        @foreach (['koordinator' => 'Koordinator', 'inti' => 'Tim Inti', 'regio' => 'Tim Regio'] as $groupKey => $groupLabel)
            @if ($groups[$groupKey]->count())
                <h2 class="{{ $loop->first ? 'mt-2' : 'mt-24' }} font-semibold text-2xl text-center">{{ __($groupLabel) }}</h2>

                <div class="flex flex-wrap justify-between gap-2 px-4">
                    @foreach ($groups[$groupKey] as $member)
                        @if ($groupKey === 'koordinator')
                            <div class="flex lg:flex-row flex-col lg:items-start items-center gap-6 mt-12 lg:w-[48%] w-full">
                                @if ($member->photo_path)
                                    <img src="{{ media_url($member->photo_path) }}" alt="{{ $member->name }}"
                                         class="w-52 h-52 object-cover rounded-full bg-tim" />
                                @endif
                                <div>
                                    <h3 class="font-semibold text-sm uppercase">
                                        {{ strtoupper($member->name) }}
                                        @if ($member->bi('position'))
                                            <br>({{ $member->bi('position') }})
                                        @endif
                                    </h3>
                                    @include('partials.collection-dots', ['member' => $member, 'size' => 'lg', 'collectionMax' => $collectionMax])
                                </div>
                            </div>
                        @else
                            <div class="flex lg:items-start items-center gap-4 mt-12 lg:w-[30%] w-full">
                                @if ($member->photo_path)
                                    <img src="{{ media_url($member->photo_path) }}" alt="{{ $member->name }}"
                                         class="w-32 h-32 object-cover object-top bg-tim rounded-full" />
                                @endif
                                <div>
                                    <h3 class="font-semibold text-sm uppercase lg:text-nowrap text-wrap">
                                        {{ $member->name }}
                                        @if ($groupKey === 'regio' && $member->region)
                                            <br>({{ $member->region }})
                                        @endif
                                    </h3>
                                    @include('partials.collection-dots', ['member' => $member, 'collectionMax' => $collectionMax])
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endforeach

    </div>

    @include('partials.footer')
@endsection
