<x-cms-layout title="Dashboard">
    <x-cms.page-header title="Dashboard" description="Overview of MapBiomas Indonesia content.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.news.create') }}">New news</x-cms.button>
            <x-cms.button href="{{ route('cms.faq.create') }}">New FAQ</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
        <x-cms.stat-card label="News" :value="$stats['news']" :href="route('cms.news.index')" />
        <x-cms.stat-card label="FAQ entries" :value="$stats['faqs']" :href="route('cms.faq.index')" />
        <x-cms.stat-card label="Team members" :value="$stats['team']" :href="route('cms.team.index')" />
        <x-cms.stat-card label="Initiatives" :value="$stats['initiatives']" :href="route('cms.initiatives.index')" />
        <x-cms.stat-card label="Partners" :value="$stats['partners']" :href="route('cms.partners.index')" />
        @if (auth()->user()->isAdmin())
            <x-cms.stat-card label="Users" :value="$stats['users']" :href="route('cms.users.index')" />
        @endif
    </div>

    <div class="mt-6 grid gap-4 lg:grid-cols-2">
        <x-cms.panel title="Recent news">
            @if ($recentNews->count())
                <ul class="divide-y divide-line">
                    @foreach ($recentNews as $item)
                        <li class="flex items-center justify-between gap-3 py-2.5 first:pt-0 last:pb-0">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-ink">{{ $item->title_en }}</p>
                                <p class="text-xs text-ink-muted">{{ $item->published_at?->format('M j, Y') ?? 'No date' }}</p>
                            </div>
                            <x-cms.badge :tone="$item->type === 'internal' ? 'green' : 'neutral'">
                                {{ ucfirst($item->type) }}
                            </x-cms.badge>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3 border-t border-line pt-3">
                    <a href="{{ route('cms.news.index') }}" class="text-sm text-accent hover:underline">View all news →</a>
                </div>
            @else
                <p class="py-6 text-center text-sm text-ink-muted">No news yet.</p>
            @endif
        </x-cms.panel>

        <x-cms.panel title="Recent FAQ">
            @if ($recentFaqs->count())
                <ul class="divide-y divide-line">
                    @foreach ($recentFaqs as $faq)
                        <li class="py-2.5 first:pt-0 last:pb-0">
                            <p class="truncate text-sm font-medium text-ink">
                                {{ \Illuminate\Support\Str::limit(strip_tags($faq->questionEN), 70) }}
                            </p>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3 border-t border-line pt-3">
                    <a href="{{ route('cms.faq.index') }}" class="text-sm text-accent hover:underline">View all FAQ →</a>
                </div>
            @else
                <p class="py-6 text-center text-sm text-ink-muted">No FAQ entries yet.</p>
            @endif
        </x-cms.panel>
    </div>
</x-cms-layout>
