<div>
<x-cms.page-header title="News" description="Internal articles and external coverage links.">
    <x-slot:actions>
        <x-cms.button variant="secondary" href="{{ route('cms.news.create') }}">New news</x-cms.button>
    </x-slot:actions>
</x-cms.page-header>

<div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center">
    <input type="search" placeholder="Search news…"
           wire:model.live.debounce.400ms="search"
           class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus sm:max-w-xs">
    <select wire:model.live="type"
            class="h-8 rounded-md border border-line-strong bg-surface px-2 text-sm text-ink cms-focus sm:w-40">
        <option value="">All types</option>
        <option value="internal">Internal</option>
        <option value="external">External</option>
    </select>
</div>

@if ($news->count())
    <x-cms.data-table>
        <x-slot:head>
            <x-cms.th>Title</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Type</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Date</x-cms.th>
            <x-cms.th>Status</x-cms.th>
            <x-cms.th class="w-20 text-right">Actions</x-cms.th>
        </x-slot:head>

        @foreach ($news as $item)
            <tr class="transition-colors hover:bg-hover/50">
                <x-cms.td>
                    <div class="flex items-center gap-3">
                        @if ($item->image_path)
                            <img src="{{ media_url($item->image_path) }}" alt=""
                                 class="h-9 w-14 shrink-0 rounded border border-line object-cover">
                        @endif
                        <div class="min-w-0">
                            <p class="truncate font-medium">{{ $item->title_en }}</p>
                            <p class="truncate text-xs text-ink-muted">{{ $item->title_id }}</p>
                        </div>
                    </div>
                </x-cms.td>
                <x-cms.td class="hidden md:table-cell">
                    <x-cms.badge :tone="$item->type === 'internal' ? 'green' : 'neutral'">
                        {{ ucfirst($item->type) }}
                    </x-cms.badge>
                </x-cms.td>
                <x-cms.td class="hidden text-ink-muted md:table-cell">
                    {{ $item->published_at?->format('M j, Y') ?? '—' }}
                </x-cms.td>
                <x-cms.td>
                    <x-cms.badge :tone="$item->is_published ? 'green' : 'amber'">
                        {{ $item->is_published ? 'Published' : 'Draft' }}
                    </x-cms.badge>
                </x-cms.td>
                <x-cms.td class="text-right">
                    <x-cms.dropdown>
                        <x-cms.dropdown-item href="{{ route('cms.news.edit', $item->id) }}">Edit</x-cms.dropdown-item>
                        <x-cms.dropdown-item wire:click="confirmDelete({{ $item->id }})" variant="danger">
                            Delete
                        </x-cms.dropdown-item>
                    </x-cms.dropdown>
                </x-cms.td>
            </tr>
        @endforeach
    </x-cms.data-table>

    {{ $news->links('cms.pagination') }}
@else
    <x-cms.empty-state
        title="{{ $search || $type ? 'No matching news' : 'No news yet' }}"
        description="{{ $search || $type ? 'Try a different search or filter.' : 'Publish the first article or add an external coverage link.' }}">
        @if (! $search && ! $type)
            <x-slot:action>
                <x-cms.button href="{{ route('cms.news.create') }}">New news</x-cms.button>
            </x-slot:action>
        @endif
    </x-cms.empty-state>
@endif

<x-cms.modal open="$wire.deleteId !== null" close="$wire.closeDelete()" title="Delete news">
    <p class="text-sm text-ink-muted">
        Are you sure you want to delete
        <span class="font-medium text-ink">{{ $deleteName ?: 'this item' }}</span>?
        This action cannot be undone.
    </p>
    <div class="mt-5 flex justify-end gap-2">
        <x-cms.button variant="secondary" @click="$wire.closeDelete()">Cancel</x-cms.button>
        <x-cms.button variant="danger" wire:click="destroy" wire:target="destroy">Delete</x-cms.button>
    </div>
</x-cms.modal>
</div>
