<div>
    <x-cms.page-header title="{{ $news ? 'Edit news' : 'New news' }}">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.news.index') }}">Cancel</x-cms.button>
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
        <x-cms.panel title="Type">
            <div class="grid gap-3 sm:grid-cols-2">
                <label class="flex cursor-pointer items-start gap-3 rounded-md border p-3 transition-colors"
                       :class="type === 'internal' ? 'border-accent bg-accent-soft' : 'border-line-strong hover:bg-hover'">
                    <input type="radio" value="internal" wire:model.live="type"
                           class="mt-0.5 h-3.5 w-3.5 text-accent focus:ring-accent/40">
                    <span>
                        <span class="block text-sm font-medium text-ink">Internal</span>
                        <span class="mt-0.5 block text-xs text-ink-muted">Write the full article here — shows the content editor below.</span>
                    </span>
                </label>
                <label class="flex cursor-pointer items-start gap-3 rounded-md border p-3 transition-colors"
                       :class="type === 'external' ? 'border-accent bg-accent-soft' : 'border-line-strong hover:bg-hover'">
                    <input type="radio" value="external" wire:model.live="type"
                           class="mt-0.5 h-3.5 w-3.5 text-accent focus:ring-accent/40">
                    <span>
                        <span class="block text-sm font-medium text-ink">External</span>
                        <span class="mt-0.5 block text-xs text-ink-muted">Only a URL is needed — no article editor, just link out to another website.</span>
                    </span>
                </label>
            </div>
        </x-cms.panel>

        <x-cms.panel title="Details">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-cms.input name="titleEN" label="Title (English)" wire:model.live.debounce.300ms="titleEN" placeholder="Title in English" />
                <x-cms.input name="titleID" label="Title (Bahasa Indonesia)" wire:model.defer="titleID" placeholder="Judul dalam Bahasa Indonesia" />

                @if ($type === \App\Models\NewsItem::TYPE_EXTERNAL)
                    <x-cms.input name="externalUrl" type="url" label="External URL" wire:model.defer="externalUrl"
                                 placeholder="https://…" hint="Link to the article on the other website — no article body is written here." class="sm:col-span-2" />
                @else
                    <x-cms.input name="slug" label="Link (slug)" wire:model.defer="slug"
                                 placeholder="auto-generated-from-title" class="sm:col-span-2"
                                 hint="Auto-generated from the English title — edit to customise." />
                @endif

                <x-cms.input name="publishedAt" type="date" label="Date" wire:model.defer="publishedAt" />
                <div class="flex items-end pb-1.5">
                    <x-cms.toggle name="isPublished" label="Published" hint="Visible on the public site."
                                  wire:model.defer="isPublished" />
                </div>
            </div>
        </x-cms.panel>

        <x-cms.panel title="Cover image">
            <x-cms.image-upload model="image" :file="$image" hint="JPG or PNG, max 2 MB."
                                :preview-url="$image?->temporaryUrl() ?? media_url($currentImage)" />
        </x-cms.panel>

        <x-cms.form-tabs>
            <x-slot:en>
                <x-cms.panel title="English content"
                             :description="$type === \App\Models\NewsItem::TYPE_INTERNAL ? null : 'Excerpt only — external news links to the other website, so there is no article editor.'">
                    <div class="space-y-4">
                        <x-cms.textarea name="excerptEN" rows="2" label="Excerpt" wire:model.defer="excerptEN"
                                        placeholder="Short summary shown on the homepage…"></x-cms.textarea>
                        @if ($type === \App\Models\NewsItem::TYPE_INTERNAL)
                            <div>
                                <span class="mb-1.5 block text-xs font-medium text-ink">Article</span>
                                <x-cms.rich-text field="contentEN" :height="420" :value="$contentEN">{{ $contentEN }}</x-cms.rich-text>
                            </div>
                        @endif
                    </div>
                </x-cms.panel>
            </x-slot:en>
            <x-slot:idn>
                <x-cms.panel title="Konten Bahasa Indonesia"
                             :description="$type === \App\Models\NewsItem::TYPE_INTERNAL ? null : 'Ringkasan saja — berita eksternal tidak memiliki isi artikel.'">
                    <div class="space-y-4">
                        <x-cms.textarea name="excerptID" rows="2" label="Ringkasan" wire:model.defer="excerptID"
                                        placeholder="Ringkasan singkat untuk halaman utama…"></x-cms.textarea>
                        @if ($type === \App\Models\NewsItem::TYPE_INTERNAL)
                            <div>
                                <span class="mb-1.5 block text-xs font-medium text-ink">Isi artikel</span>
                                <x-cms.rich-text field="contentID" :height="420" :value="$contentID">{{ $contentID }}</x-cms.rich-text>
                            </div>
                        @endif
                    </div>
                </x-cms.panel>
            </x-slot:idn>
        </x-cms.form-tabs>

        <x-cms.form-actions cancel="{{ route('cms.news.index') }}" />
    </div>
</div>
