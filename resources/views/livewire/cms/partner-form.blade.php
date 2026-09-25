<div>
    <x-cms.page-header title="{{ $partner ? 'Edit partner' : 'New partner' }}">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.partners.index') }}">Cancel</x-cms.button>
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

    <div class="max-w-3xl space-y-4">
        <x-cms.panel title="Partner details">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-cms.input name="name" label="Name" wire:model.defer="name" placeholder="e.g. Auriga" />
                <x-cms.select name="category" label="Category" wire:model.defer="category"
                              :options="['cocreator' => 'Co-creator', 'supported' => 'Supported by']" />
                <x-cms.input name="url" type="url" label="Website URL (optional)" wire:model.defer="url"
                             placeholder="https://…" class="sm:col-span-2" />
            </div>
        </x-cms.panel>

        <x-cms.panel title="Logo">
            <x-cms.image-upload model="logo" shape="square" fit="contain" :file="$logo"
                                hint="Color logos on a white background are shown as-is on the site. Max 2 MB."
                                :preview-url="$logo?->temporaryUrl() ?? media_url($currentLogo)" />
        </x-cms.panel>

        <x-cms.panel title="Display options">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-cms.input name="sort" type="number" label="Display order" wire:model.defer="sort" placeholder="0"
                             hint="Lower numbers appear first." />
                <div class="pb-1">
                    <x-cms.toggle name="isActive" label="Active" hint="Hidden partners do not appear in the footer."
                                  wire:model.defer="isActive" />
                </div>
            </div>
        </x-cms.panel>
    </div>
</div>
