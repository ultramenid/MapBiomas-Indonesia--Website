<div>
<x-cms.page-header title="Users" description="People who can access this CMS.">
    <x-slot:actions>
        <x-cms.button variant="secondary" href="{{ route('cms.users.create') }}">New user</x-cms.button>
    </x-slot:actions>
</x-cms.page-header>

<x-cms.data-table>
    <x-slot:head>
        <x-cms.th>User</x-cms.th>
        <x-cms.th class="hidden md:table-cell">Role</x-cms.th>
        <x-cms.th class="hidden md:table-cell">Joined</x-cms.th>
        <x-cms.th class="w-20 text-right">Actions</x-cms.th>
    </x-slot:head>

    @foreach ($users as $user)
        <tr class="transition-colors hover:bg-hover/50">
            <x-cms.td>
                <div class="flex items-center gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-semibold text-accent-fg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate font-medium">
                            {{ $user->name }}
                            @if ($user->id === auth()->id())
                                <span class="ml-1 text-xs font-normal text-ink-muted">(you)</span>
                            @endif
                        </p>
                        <p class="truncate text-xs text-ink-muted">{{ $user->email }}</p>
                    </div>
                </div>
            </x-cms.td>
            <x-cms.td class="hidden md:table-cell">
                <x-cms.badge :tone="$user->isAdmin() ? 'green' : 'neutral'">{{ $user->roleName() }}</x-cms.badge>
            </x-cms.td>
            <x-cms.td class="hidden text-ink-muted md:table-cell">{{ $user->created_at->format('M j, Y') }}</x-cms.td>
            <x-cms.td class="text-right">
                <x-cms.dropdown>
                        <x-cms.dropdown-item href="{{ route('cms.users.edit', $user->id) }}">Edit</x-cms.dropdown-item>
                    <x-cms.dropdown-item wire:click="confirmDelete({{ $user->id }})" variant="danger">
                        Delete
                    </x-cms.dropdown-item>
                </x-cms.dropdown>
            </x-cms.td>
        </tr>
    @endforeach
</x-cms.data-table>

<x-cms.modal open="$wire.deleteId !== null" close="$wire.closeDelete()" title="Delete user">
    <p class="text-sm text-ink-muted">
        Are you sure you want to delete
        <span class="font-medium text-ink">{{ $deleteName ?: 'this user' }}</span>?
        This action cannot be undone.
    </p>
    <div class="mt-5 flex justify-end gap-2">
        <x-cms.button variant="secondary" @click="$wire.closeDelete()">Cancel</x-cms.button>
        <x-cms.button variant="danger" wire:click="destroy" wire:target="destroy">Delete</x-cms.button>
    </div>
</x-cms.modal>
</div>
