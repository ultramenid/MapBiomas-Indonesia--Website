<div>
    <x-cms.page-header title="{{ $initiative ? 'Edit initiative' : 'New initiative' }}">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.initiatives.index') }}">Cancel</x-cms.button>
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
        <x-cms.panel title="Details">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-cms.input name="name" label="Name" wire:model.live.debounce.300ms="name" placeholder="e.g. Landy" />
                <x-cms.input name="slug" label="Slug" wire:model.defer="slug" placeholder="auto-generated-from-name"
                             hint="Auto-generated from the name — edit to customise." />
                <x-cms.input name="platformUrl" type="url" label="Platform URL" wire:model.defer="platformUrl"
                             placeholder="https://…" class="sm:col-span-2" />
                <div class="sm:col-span-2">
                    <label for="accentColor" class="mb-1.5 block text-xs font-medium text-ink">Accent color</label>
                    <div class="flex h-8 items-center gap-2">
                        <input type="color" id="accentColor" wire:model.defer="accentColor"
                               class="h-8 w-12 cursor-pointer rounded-md border border-line-strong bg-surface p-0.5">
                        <span class="text-sm text-ink-muted">{{ $accentColor }}</span>
                    </div>
                </div>
            </div>
        </x-cms.panel>

        <x-cms.panel title="Logo">
            <x-cms.image-upload model="logo" shape="square" fit="contain" :file="$logo"
                                hint="Transparent PNG works best — shown flat on the site. Max 2 MB."
                                :preview-url="$logo?->temporaryUrl() ?? media_url($currentLogo)" />
        </x-cms.panel>

        <x-cms.panel title="Description">
            <div class="grid gap-4">
                <x-cms.textarea name="descriptionEN" rows="3" label="Description (English)" wire:model.defer="descriptionEN"></x-cms.textarea>
                <x-cms.textarea name="descriptionID" rows="3" label="Description (Bahasa Indonesia)" wire:model.defer="descriptionID"></x-cms.textarea>
            </div>
        </x-cms.panel>

        <x-cms.panel title="Display options">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-cms.input name="sort" type="number" label="Display order" wire:model.defer="sort" placeholder="0"
                             hint="Lower numbers appear first." />
                <div class="pb-1">
                    <x-cms.toggle name="isActive" label="Active" hint="Hidden initiatives do not appear on the homepage."
                                  wire:model.defer="isActive" />
                </div>
            </div>
        </x-cms.panel>
    </div>
</div>
