@php
$navGroups = [
    [
        'label' => 'Overview',
        'items' => [
            ['route' => 'cms.dashboard', 'match' => 'cms.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z'],
        ],
    ],
    [
        'label' => 'Content',
        'items' => [
            ['route' => 'cms.pages.index', 'match' => 'cms.pages.*', 'label' => 'Pages', 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6M16 13H8M16 17H8M10 9H8'],
            ['route' => 'cms.news.index', 'match' => 'cms.news.*', 'label' => 'News', 'icon' => 'M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0V9M18 14h-8M15 18h-5M10 6h8v4h-8V6Z'],
            ['route' => 'cms.media.index', 'match' => 'cms.media.*', 'label' => 'Media', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['route' => 'cms.faq.index', 'match' => 'cms.faq.*', 'label' => 'FAQ', 'icon' => 'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20zM9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01'],
        ],
    ],
    [
        'label' => 'People',
        'items' => [
            ['route' => 'cms.team.index', 'match' => 'cms.team.*', 'label' => 'Team', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75'],
        ],
    ],
    [
        'label' => 'Site',
        'items' => [
            ['route' => 'cms.initiatives.index', 'match' => 'cms.initiatives.*', 'label' => 'Initiatives', 'icon' => 'M12 2 2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'],
            ['route' => 'cms.partners.index', 'match' => 'cms.partners.*', 'label' => 'Partners', 'icon' => 'M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71'],
        ],
    ],
];

if (auth()->user()?->isAdmin()) {
    $navGroups[] = [
        'label' => 'Manage',
        'items' => [
            ['route' => 'cms.users.index', 'match' => 'cms.users.*', 'label' => 'Users', 'icon' => 'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20zM12 8v4M12 16h.01'],
        ],
    ];
}
@endphp

@foreach ($navGroups as $group)
    <div>
        <p class="px-2 pb-1.5 text-[11px] font-semibold uppercase tracking-widest text-ink-subtle">{{ $group['label'] }}</p>
        <div class="space-y-0.5">
            @foreach ($group['items'] as $item)
                @php
                    $active = request()->routeIs($item['match']);
                @endphp
                <a href="{{ route($item['route']) }}" aria-current="{{ $active ? 'page' : 'false' }}"
                   class="flex items-center gap-2.5 rounded-md px-2.5 py-1.5 text-sm transition-colors
                          {{ $active ? 'bg-hover font-medium text-ink' : 'text-ink-muted hover:bg-hover hover:text-ink' }}">
                    <svg class="h-4 w-4 shrink-0 {{ $active ? 'text-accent' : 'text-ink-subtle' }}"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
@endforeach
