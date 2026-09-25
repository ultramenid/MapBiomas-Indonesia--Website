<div>
<x-cms.page-header title="{{ $user ? 'Edit user' : 'New user' }}">
    <x-slot:actions>
        <x-cms.button variant="secondary" href="{{ route('cms.users.index') }}">Cancel</x-cms.button>
        <x-cms.button wire:click="save" loadingTarget="save">Save</x-cms.button>
    </x-slot:actions>
</x-cms.page-header>

@if ($errors->any())
    <div class="mb-5 rounded-md border border-danger/30 bg-danger/10 px-4 py-3">
        <p class="text-sm font-medium text-danger">Please fix the following before saving:</p>
        <ul class="mt-1.5 list-inside list-disc text-sm text-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-cms.panel class="max-w-xl">
    <form wire:submit.prevent class="space-y-4">
        <x-cms.input name="name" label="Full name" wire:model.defer="name" placeholder="e.g. Budi Santoso" />
        <x-cms.input name="email" type="email" label="Email" wire:model.defer="email" placeholder="name@example.com" />
        <div class="grid gap-4 sm:grid-cols-2">
            <x-cms.input name="password" type="password" label="{{ $user ? 'New password' : 'Password' }}"
                         wire:model.defer="password" autocomplete="new-password"
                         hint="{{ $user ? 'Leave empty to keep the current password.' : 'Minimum 8 characters.' }}" />
            <x-cms.input name="passwordConfirmation" type="password" label="Confirm password"
                         wire:model.defer="passwordConfirmation" autocomplete="new-password" />
        </div>
        <x-cms.select name="role" label="Role" wire:model.defer="role"
                      :options="['1' => 'Admin — full access, manages users', '2' => 'Editor — manages content only']" />
    </form>
</x-cms.panel>
</div>
