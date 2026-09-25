<div>
<x-cms.page-header title="Partners" description="Co-creators and supporters shown in the site footer.">
    <x-slot:actions>
        <x-cms.button variant="secondary" href="{{ route('cms.partners.create') }}">New partner</x-cms.button>
    </x-slot:actions>
</x-cms.page-header>

@if ($partners->count())
    <x-cms.data-table>
        <x-slot:head>
            <x-cms.th>Partner</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Category</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Website</x-cms.th>
            <x-cms.th>Status</x-cms.th>
            <x-cms.th class="w-20 text-right">Actions</x-cms.th>
        </x-slot:head>

        @foreach ($partners as $partner)
            <tr class="transition-colors hover:bg-hover/50">
                <x-cms.td>
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-14 shrink-0 items-center justify-center rounded border border-line bg-white p-1 dark:bg-white">
                            <img src="{{ media_url($partner->logo_path) }}" alt="{{ $partner->name }}"
                                 class="max-h-full max-w-full object-contain">
                        </span>
                        <span class="font-medium">{{ $partner->name }}</span>
                    </div>
                </x-cms.td>
                <x-cms.td class="hidden md:table-cell">
                    <x-cms.badge :tone="$partner->category === 'cocreator' ? 'green' : 'neutral'">
                        {{ $partner->category === 'cocreator' ? 'Co-creator' : 'Supported by' }}
                    </x-cms.badge>
                </x-cms.td>
                <x-cms.td class="hidden md:table-cell">
                    @if ($partner->url)
                        <a href="{{ $partner->url }}" target="_blank" rel="noopener"
                           class="text-sm text-accent hover:underline">{{ preg_replace('#^https?://#', '', $partner->url) }}</a>
                    @else
                        <span class="text-ink-muted">—</span>
                    @endif
                </x-cms.td>
                <x-cms.td>
                    <x-cms.badge :tone="$partner->is_active ? 'green' : 'amber'">
                        {{ $partner->is_active ? 'Active' : 'Hidden' }}
                    </x-cms.badge>
                </x-cms.td>
                <x-cms.td class="text-right">
                    <x-cms.dropdown>
                        <x-cms.dropdown-item href="{{ route('cms.partners.edit', $partner->id) }}">Edit</x-cms.dropdown-item>
                        <x-cms.dropdown-item wire:click="confirmDelete({{ $partner->id }})" variant="danger">
                            Delete
                        </x-cms.dropdown-item>
                    </x-cms.dropdown>
                </x-cms.td>
            </tr>
        @endforeach
    </x-cms.data-table>
@else
    <x-cms.empty-state title="No partners yet"
                       description="Add co-creator or supporter logos — they appear in the site footer.">
        <x-slot:action>
            <x-cms.button href="{{ route('cms.partners.create') }}">New partner</x-cms.button>
        </x-slot:action>
    </x-cms.empty-state>
@endif

<x-cms.modal open="$wire.deleteId !== null" close="$wire.closeDelete()" title="Delete partner">
    <p class="text-sm text-ink-muted">
        Are you sure you want to delete
        <span class="font-medium text-ink">{{ $deleteName ?: 'this partner' }}</span>?
        This action cannot be undone.
    </p>
    <div class="mt-5 flex justify-end gap-2">
        <x-cms.button variant="secondary" @click="$wire.closeDelete()">Cancel</x-cms.button>
        <x-cms.button variant="danger" wire:click="destroy" wire:target="destroy">Delete</x-cms.button>
    </div>
</x-cms.modal>
</div>
