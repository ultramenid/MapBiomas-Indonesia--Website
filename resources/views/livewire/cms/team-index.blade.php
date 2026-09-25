<div>
<x-cms.page-header title="Team" description="Technical team and Scientific Advisory Committee members.">
    <x-slot:actions>
        <x-cms.button variant="secondary" href="{{ route('cms.team.create') }}">New member</x-cms.button>
    </x-slot:actions>
</x-cms.page-header>

<div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center">
    <input type="search" placeholder="Search by name or region…"
           wire:model.live.debounce.400ms="search"
           class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus sm:max-w-xs">
    <select wire:model.live="category"
            class="h-8 rounded-md border border-line-strong bg-surface px-2 text-sm text-ink cms-focus sm:w-44">
        <option value="">All categories</option>
        <option value="technical">Technical team</option>
        <option value="scientific">Scientific Advisory</option>
    </select>
</div>

@if ($members->count())
    <x-cms.data-table>
        <x-slot:head>
            <x-cms.th>Member</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Category</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Group / Region</x-cms.th>
            <x-cms.th>Status</x-cms.th>
            <x-cms.th class="w-20 text-right">Actions</x-cms.th>
        </x-slot:head>

        @foreach ($members as $member)
            <tr class="transition-colors hover:bg-hover/50">
                <x-cms.td>
                    <div class="flex items-center gap-3">
                        @if ($member->photo_path)
                            <img src="{{ media_url($member->photo_path) }}" alt=""
                                 class="h-9 w-9 shrink-0 rounded-full border border-line object-cover">
                        @else
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-hover text-xs font-medium text-ink-muted">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </span>
                        @endif
                        <div class="min-w-0">
                            <p class="truncate font-medium">{{ $member->name }}</p>
                            @if ($member->position_en)
                                <p class="truncate text-xs text-ink-muted">{{ $member->position_en }}</p>
                            @endif
                        </div>
                    </div>
                </x-cms.td>
                <x-cms.td class="hidden md:table-cell">
                    <x-cms.badge :tone="$member->category === 'technical' ? 'green' : 'neutral'">
                        {{ $member->category === 'technical' ? 'Technical' : 'Scientific' }}
                    </x-cms.badge>
                </x-cms.td>
                <x-cms.td class="hidden text-ink-muted md:table-cell">
                    {{ match (true) {
                        $member->team_group === 'koordinator' => 'Koordinator',
                        $member->team_group === 'inti' => 'Tim Inti',
                        $member->team_group === 'regio' => 'Tim Regio' . ($member->region ? ' · ' . $member->region : ''),
                        default => '—',
                    } }}
                </x-cms.td>
                <x-cms.td>
                    <x-cms.badge :tone="$member->is_active ? 'green' : 'amber'">
                        {{ $member->is_active ? 'Active' : 'Hidden' }}
                    </x-cms.badge>
                </x-cms.td>
                <x-cms.td class="text-right">
                    <x-cms.dropdown>
                        <x-cms.dropdown-item href="{{ route('cms.team.edit', $member->id) }}">Edit</x-cms.dropdown-item>
                        <x-cms.dropdown-item wire:click="confirmDelete({{ $member->id }})" variant="danger">
                            Delete
                        </x-cms.dropdown-item>
                    </x-cms.dropdown>
                </x-cms.td>
            </tr>
        @endforeach
    </x-cms.data-table>

    {{ $members->links('cms.pagination') }}
@else
    <x-cms.empty-state
        title="{{ $search || $category ? 'No matching members' : 'No team members yet' }}"
        description="{{ $search || $category ? 'Try a different search or filter.' : 'Add technical or scientific advisory members.' }}">
        @if (! $search && ! $category)
            <x-slot:action>
                <x-cms.button href="{{ route('cms.team.create') }}">New member</x-cms.button>
            </x-slot:action>
        @endif
    </x-cms.empty-state>
@endif

<x-cms.modal open="$wire.deleteId !== null" close="$wire.closeDelete()" title="Delete team member">
    <p class="text-sm text-ink-muted">
        Are you sure you want to delete
        <span class="font-medium text-ink">{{ $deleteName ?: 'this member' }}</span>?
        This action cannot be undone.
    </p>
    <div class="mt-5 flex justify-end gap-2">
        <x-cms.button variant="secondary" @click="$wire.closeDelete()">Cancel</x-cms.button>
        <x-cms.button variant="danger" wire:click="destroy" wire:target="destroy">Delete</x-cms.button>
    </div>
</x-cms.modal>
</div>
