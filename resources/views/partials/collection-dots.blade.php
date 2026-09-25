@props([
    'member',
    'size' => 'sm', // sm | lg
    'collectionMax' => \App\Models\TeamMember::COLLECTION_MAX,
])

@php
$dotColors = [
    'landy' => 'bg-landy border-landy',
    'fire' => 'bg-fire border-fire',
    'alerta' => 'bg-alerta border-alerta',
];
$dotSize = $size === 'lg' ? 'h-6 w-6' : 'h-4 w-4';
@endphp

@if ($member->collections && array_filter($member->collections))
    <div class="flex flex-col gap-2 mt-3">
        @foreach ($collectionMax as $initiative => $max)
            @php $contributed = $member->collections[$initiative] ?? []; @endphp
            <div class="flex gap-2">
                @for ($i = 1; $i <= $max; $i++)
                    @if (in_array($i, $contributed))
                        <a x-data x-tooltip.raw="{{ ucfirst($initiative) }} - Collection {{ $i }}"
                           class="{{ $dotSize }} rounded-full {{ $dotColors[$initiative] }} cursor-pointer"></a>
                    @else
                        <a class="{{ $dotSize }} rounded-full border {{ $dotColors[$initiative] }}"></a>
                    @endif
                @endfor
            </div>
        @endforeach
    </div>
@endif
