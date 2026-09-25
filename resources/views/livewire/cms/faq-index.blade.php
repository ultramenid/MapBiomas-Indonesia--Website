<div>
<x-cms.page-header title="FAQ">
    <x-slot:actions>
        <x-cms.button variant="secondary" href="{{ route('cms.faq.create') }}">New FAQ</x-cms.button>
    </x-slot:actions>
</x-cms.page-header>

<input type="search" placeholder="Search FAQ…"
       wire:model.live.debounce.400ms="search"
       class="mb-4 h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus sm:max-w-xs">

@if ($faqs->count())
    <x-cms.data-table>
        <x-slot:head>
            <x-cms.th class="w-12">#</x-cms.th>
            <x-cms.th>Question (EN)</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Question (ID)</x-cms.th>
            <x-cms.th class="w-20 text-right">Actions</x-cms.th>
        </x-slot:head>

        @foreach ($faqs as $faq)
            <tr class="transition-colors hover:bg-hover/50">
                <x-cms.td class="text-ink-subtle">{{ $faq->id }}</x-cms.td>
                <x-cms.td class="max-w-sm font-medium">
                    <span class="line-clamp-2">{{ Str::limit(strip_tags($faq->questionEN), 90) }}</span>
                </x-cms.td>
                <x-cms.td class="hidden max-w-sm md:table-cell">
                    <span class="line-clamp-2 text-ink-muted">{{ Str::limit(strip_tags($faq->questionID), 90) }}</span>
                </x-cms.td>
                <x-cms.td class="text-right">
                    <x-cms.dropdown>
                        <x-cms.dropdown-item href="{{ route('cms.faq.edit', $faq->id) }}">Edit</x-cms.dropdown-item>
                        <x-cms.dropdown-item wire:click="confirmDelete({{ $faq->id }})" variant="danger">
                            Delete
                        </x-cms.dropdown-item>
                    </x-cms.dropdown>
                </x-cms.td>
            </tr>
        @endforeach
    </x-cms.data-table>

    {{ $faqs->links('cms.pagination') }}
@else
    <x-cms.empty-state
        title="{{ $search ? 'No matching FAQ' : 'No FAQ yet' }}"
        description="{{ $search ? 'Try a different search term.' : 'Create the first FAQ entry so visitors can find answers.' }}">
        @if (! $search)
            <x-slot:action>
                <x-cms.button href="{{ route('cms.faq.create') }}">New FAQ</x-cms.button>
            </x-slot:action>
        @endif
    </x-cms.empty-state>
@endif

<x-cms.modal open="$wire.deleteId !== null" close="$wire.closeDelete()" title="Delete FAQ">
    <p class="text-sm text-ink-muted">
        Are you sure you want to delete
        <span class="font-medium text-ink">{{ $deleteName ?: 'this FAQ' }}</span>?
        This action cannot be undone.
    </p>
    <div class="mt-5 flex justify-end gap-2">
        <x-cms.button variant="secondary" @click="$wire.closeDelete()">Cancel</x-cms.button>
        <x-cms.button variant="danger" wire:click="destroy" wire:target="destroy">Delete</x-cms.button>
    </div>
</x-cms.modal>
</div>
