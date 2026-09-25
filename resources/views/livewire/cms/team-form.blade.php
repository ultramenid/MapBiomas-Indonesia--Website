<div>
    <x-cms.page-header title="{{ $member ? 'Edit member' : 'New member' }}">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.team.index') }}">Cancel</x-cms.button>
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
        <x-cms.panel title="Member">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-cms.input name="name" label="Full name" wire:model.defer="name" placeholder="e.g. Timer Manurung" />
                <x-cms.select name="category" label="Category" wire:model.live="category"
                              :options="['technical' => 'Technical team', 'scientific' => 'Scientific Advisory']" />

                @if ($category === 'technical')
                    <x-cms.select name="teamGroup" label="Team group" wire:model.live="teamGroup"
                                  :options="['' => '— select —', 'koordinator' => 'Koordinator', 'inti' => 'Tim Inti', 'regio' => 'Tim Regio']" />
                    @if ($teamGroup === 'regio')
                        <x-cms.input name="region" label="Region" wire:model.defer="region" placeholder="e.g. Sumatera, Kalimantan, Papua" />
                    @endif
                    <div class="grid gap-4 sm:col-span-2 sm:grid-cols-2">
                        <x-cms.input name="positionEN" label="Position (English)" wire:model.defer="positionEN" placeholder="e.g. General Coordinator" />
                        <x-cms.input name="positionID" label="Position (Bahasa Indonesia)" wire:model.defer="positionID" placeholder="e.g. Koordinator Umum" />
                    </div>
                @endif

                <x-cms.input name="email" type="email" label="Email (optional)" wire:model.defer="email" placeholder="name@example.com" />
            </div>
        </x-cms.panel>

        <x-cms.panel title="Photo" description="Shown as a circle on the public team page.">
            <x-cms.image-upload model="photo" shape="circle" hint="Square photo works best — JPG or PNG, max 2 MB."
                                :file="$photo" :preview-url="$photo?->temporaryUrl() ?? media_url($currentPhoto)" />
        </x-cms.panel>

        @if ($category === 'technical')
            <x-cms.panel title="Collection badges"
                         description="Which collection numbers did this member contribute to? Shown as colored dots on the public team page.">
                <div class="grid gap-5 sm:grid-cols-3">
                    @foreach ($collectionMax as $initiative => $max)
                        @php $count = $collectionCounts[$initiative] ?? $max; @endphp
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-ink-muted">{{ ucfirst($initiative) }}</p>
                            <div class="flex flex-wrap items-center gap-3">
                                @for ($i = 1; $i <= $count; $i++)
                                    <label class="flex cursor-pointer items-center gap-1.5 text-sm text-ink">
                                        <input type="checkbox" value="{{ $i }}"
                                               wire:model.defer="collections.{{ $initiative }}"
                                               class="h-3.5 w-3.5 rounded border-line-strong text-accent focus:ring-accent/40">
                                        C{{ $i }}
                                    </label>
                                @endfor
                                <button type="button" wire:click="addCollection('{{ $initiative }}')"
                                        class="inline-flex items-center rounded-md border border-dashed border-line-strong px-2 py-1 text-xs font-medium text-ink-muted transition hover:border-accent hover:text-accent">
                                    + Add collection
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-cms.panel>
        @endif

        <x-cms.form-tabs>
            <x-slot:en>
                <x-cms.panel title="Bio (English)">
                    <x-cms.rich-text field="bioEN" :height="280" :value="$bioEN">{{ $bioEN }}</x-cms.rich-text>
                </x-cms.panel>
            </x-slot:en>
            <x-slot:idn>
                <x-cms.panel title="Bio (Bahasa Indonesia)">
                    <x-cms.rich-text field="bioID" :height="280" :value="$bioID">{{ $bioID }}</x-cms.rich-text>
                </x-cms.panel>
            </x-slot:idn>
        </x-cms.form-tabs>

        <x-cms.panel title="Display options">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-cms.input name="sort" type="number" label="Display order" wire:model.defer="sort"
                             placeholder="0" hint="Lower numbers appear first." />
                <div class="pb-1">
                    <x-cms.toggle name="isActive" label="Active" hint="Hidden members do not appear on the public site."
                                  wire:model.defer="isActive" />
                </div>
            </div>
        </x-cms.panel>

        <x-cms.form-actions cancel="{{ route('cms.team.index') }}" />
    </div>
</div>
