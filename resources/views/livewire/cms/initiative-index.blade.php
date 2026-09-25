<div>
<x-cms.page-header title="Initiatives"
                   description="MapBiomas Indonesia platforms (Landy, Fire, Alerta, …) shown on the homepage.">
    <x-slot:actions>
        <x-cms.button variant="secondary" href="{{ route('cms.initiatives.create') }}">New initiative</x-cms.button>
    </x-slot:actions>
</x-cms.page-header>

@if ($initiatives->count())
    <x-cms.data-table>
        <x-slot:head>
            <x-cms.th>Initiative</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Platform URL</x-cms.th>
            <x-cms.th class="w-20">Sort</x-cms.th>
            <x-cms.th>Status</x-cms.th>
            <x-cms.th class="w-20 text-right">Actions</x-cms.th>
        </x-slot:head>

        @foreach ($initiatives as $initiative)
            <tr class="transition-colors hover:bg-hover/50">
                <x-cms.td>
                    <div class="flex items-center gap-3">
                        @if ($initiative->logo_path)
                            <img src="{{ media_url($initiative->logo_path) }}" alt=""
                                 class="h-8 w-14 shrink-0 object-contain">
                        @else
                            <span class="h-8 w-8 shrink-0 rounded-full" style="background: {{ $initiative->accent_color }}"></span>
                        @endif
                        <span class="font-medium">{{ $initiative->name }}</span>
                    </div>
                </x-cms.td>
                <x-cms.td class="hidden md:table-cell">
                    <a href="{{ $initiative->platform_url }}" target="_blank" rel="noopener"
                       class="text-sm text-accent hover:underline">{{ $initiative->platform_url }}</a>
                </x-cms.td>
                <x-cms.td class="text-ink-muted">{{ $initiative->sort }}</x-cms.td>
                <x-cms.td>
                    <x-cms.badge :tone="$initiative->is_active ? 'green' : 'amber'">
                        {{ $initiative->is_active ? 'Active' : 'Hidden' }}
                    </x-cms.badge>
                </x-cms.td>
                <x-cms.td class="text-right">
                    <x-cms.dropdown>
                        <x-cms.dropdown-item href="{{ route('cms.initiatives.edit', $initiative->id) }}">Edit</x-cms.dropdown-item>
                        <x-cms.dropdown-item wire:click="confirmDelete({{ $initiative->id }})" variant="danger">
                            Delete
                        </x-cms.dropdown-item>
                    </x-cms.dropdown>
                </x-cms.td>
            </tr>
        @endforeach
    </x-cms.data-table>
@else
    <x-cms.empty-state title="No initiatives yet"
                       description="Add the first initiative platform — it will appear on the homepage.">
        <x-slot:action>
            <x-cms.button href="{{ route('cms.initiatives.create') }}">New initiative</x-cms.button>
        </x-slot:action>
    </x-cms.empty-state>
@endif

<x-cms.modal open="$wire.deleteId !== null" close="$wire.closeDelete()" title="Delete initiative">
    <p class="text-sm text-ink-muted">
        Are you sure you want to delete
        <span class="font-medium text-ink">{{ $deleteName ?: 'this initiative' }}</span>?
        This action cannot be undone.
    </p>
    <div class="mt-5 flex justify-end gap-2">
        <x-cms.button variant="secondary" @click="$wire.closeDelete()">Cancel</x-cms.button>
        <x-cms.button variant="danger" wire:click="destroy" wire:target="destroy">Delete</x-cms.button>
    </div>
</x-cms.modal>
</div>
