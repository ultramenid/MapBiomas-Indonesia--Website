<div>
    <x-cms.page-header title="{{ $pageTitle }}">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.pages.index') }}">Cancel</x-cms.button>
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

    <div class="max-w-3xl">
        <x-cms.form-tabs>
            <x-slot:en>
                <x-cms.panel title="Content (English)">
                    <x-cms.rich-text field="contentEN" :height="520" :value="$contentEN">
                        {{ $contentEN }}
                    </x-cms.rich-text>
                </x-cms.panel>
            </x-slot:en>

            <x-slot:idn>
                <x-cms.panel title="Content (Bahasa Indonesia)">
                    <x-cms.rich-text field="contentID" :height="520" :value="$contentID">
                        {{ $contentID }}
                    </x-cms.rich-text>
                </x-cms.panel>
            </x-slot:idn>
        </x-cms.form-tabs>

        <x-cms.form-actions cancel="{{ route('cms.pages.index') }}" />
    </div>
</div>
