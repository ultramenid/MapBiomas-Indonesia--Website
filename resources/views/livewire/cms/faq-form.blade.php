<div>
    <x-cms.page-header title="{{ $faq ? 'Edit FAQ' : 'New FAQ' }}"
                       description="Answers support rich text and inline images.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.faq.index') }}">Cancel</x-cms.button>
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
                <x-cms.panel title="English">
                    <div class="space-y-4">
                        <x-cms.textarea name="questionEN" rows="2" label="Question" wire:model.defer="questionEN"
                                        placeholder="Write the question in English…"></x-cms.textarea>
                        <div>
                            <span class="mb-1.5 block text-xs font-medium text-ink">Answer</span>
                            <x-cms.rich-text field="answerEN" :value="$answerEN">
                                {{ $answerEN }}
                            </x-cms.rich-text>
                        </div>
                    </div>
                </x-cms.panel>
            </x-slot:en>

            <x-slot:idn>
                <x-cms.panel title="Bahasa Indonesia">
                    <div class="space-y-4">
                        <x-cms.textarea name="questionID" rows="2" label="Pertanyaan" wire:model.defer="questionID"
                                        placeholder="Tulis pertanyaan dalam Bahasa Indonesia…"></x-cms.textarea>
                        <div>
                            <span class="mb-1.5 block text-xs font-medium text-ink">Jawaban</span>
                            <x-cms.rich-text field="answerID" :value="$answerID">
                                {{ $answerID }}
                            </x-cms.rich-text>
                        </div>
                    </div>
                </x-cms.panel>
            </x-slot:idn>
        </x-cms.form-tabs>

        <x-cms.form-actions cancel="{{ route('cms.faq.index') }}" />
    </div>
</div>
