@props([
    'model', // Livewire property holding the temporary upload
    'label' => null,
    'previewUrl' => null, // current/new image to preview
    'file' => null, // the Livewire temporary upload, used for the name/size chip
    'hint' => null,
    'shape' => 'wide', // preview box shape: wide | square | circle
    'fit' => 'cover', // preview scaling: cover | contain
])

@php
$uid = 'upload-'.(string) $model;
$box = [
    'wide' => 'h-36 w-full sm:w-56 rounded-md',
    'square' => 'h-40 w-40 rounded-md',
    'circle' => 'h-40 w-40 rounded-full',
][$shape] ?? 'h-36 w-full sm:w-56 rounded-md';

$fileSize = null;
if ($file) {
    $bytes = (int) $file->getSize();
    $fileSize = $bytes >= 1024 * 1024
        ? round($bytes / (1024 * 1024), 1).' MB'
        : ($bytes >= 1024 ? round($bytes / 1024, 1).' KB' : $bytes.' B');
}
@endphp

<div x-data="{
        dragging: false,
        uploading: false,
        pct: 0,
    }"
    x-on:livewire-upload-start="uploading = true; pct = 0"
    x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-cancel="uploading = false; pct = 0"
    x-on:livewire-upload-progress="pct = $event.detail.progress">
    @if ($label)
        <span class="mb-1.5 block text-xs font-medium text-ink">{{ $label }}</span>
    @endif
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
        {{-- Dropzone: click or Enter opens the picker, files can be dropped onto it --}}
        <div class="cms-checker group relative shrink-0 cursor-pointer overflow-hidden border border-dashed
                    border-line-strong bg-hover transition-[border-color,box-shadow] cms-focus {{ $box }}"
             x-on:click="$refs.file.click()"
             x-on:keydown.enter.prevent="$refs.file.click()"
             x-on:keydown.space.prevent="$refs.file.click()"
             x-on:dragover.prevent="dragging = true"
             x-on:dragleave="dragging = false"
             x-on:drop.prevent="
                dragging = false;
                const dropped = $event.dataTransfer?.files?.[0];
                if (! dropped) return;
                uploading = true; pct = 0;
                $wire.upload('{{ $model }}', dropped,
                    () => { uploading = false; },
                    () => { uploading = false; },
                    (e) => { pct = e.detail?.progress ?? 0; });
             "
             x-bind:class="dragging || uploading ? 'border-accent shadow-[0_0_0_3px_color-mix(in_srgb,var(--accent)_25%,transparent)]' : ''"
             role="button" tabindex="0" aria-label="Upload image"
             aria-describedby="{{ $uid }}-hint">
            @if ($previewUrl)
                <img src="{{ $previewUrl }}" alt="Preview"
                     class="h-full w-full {{ $fit === 'contain' ? 'object-contain p-1.5' : 'object-cover' }}" />
            @else
                <div class="flex h-full w-full flex-col items-center justify-center gap-1 px-3 text-center">
                    <svg class="h-7 w-7 text-ink-subtle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="9" cy="9" r="2" />
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                    </svg>
                    <p class="text-[11px] font-medium text-ink-muted">Drop image here</p>
                    <p class="text-[11px] text-ink-subtle">or click to browse</p>
                </div>
            @endif

            {{-- Hover scrim — shows that the box itself opens the picker --}}
            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center gap-1
                        bg-black/50 text-xs font-medium text-white opacity-0 transition-opacity
                        group-hover:opacity-100"
                 x-show="! uploading" x-cloak
                 x-bind:class="dragging ? 'opacity-100' : ''">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                    <path d="m21.174 6.812-1.059 1.06-2.137-2.138 1.06-1.06a1.5 1.5 0 0 1 2.121 0l.095.096a1.5 1.5 0 0 1 0 2.121z" />
                    <path d="M18.935 7.872 9 17.808V20h2.192l9.936-9.936" />
                </svg>
                {{ $previewUrl ? 'Replace image' : 'Upload image' }}
            </div>

            {{-- Upload progress overlay --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-black/50 text-white"
                 x-show="uploading" x-cloak>
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-xs font-medium" x-text="pct > 0 ? 'Uploading… ' + pct + '%' : 'Uploading…'"></p>
                <div class="h-1 w-24 overflow-hidden rounded-full bg-white/30">
                    <div class="h-full rounded-full bg-white transition-[width] duration-150"
                         x-bind:style="'width: ' + pct + '%'"></div>
                </div>
            </div>
        </div>

        <div class="w-full">
            <input type="file" id="{{ $uid }}" x-ref="file" accept="image/*" wire:model="{{ $model }}" class="sr-only" />

            <div class="flex flex-wrap items-center gap-2">
                <label for="{{ $uid }}"
                       class="inline-flex h-8 cursor-pointer select-none items-center gap-1.5 rounded-md border border-line-strong
                              bg-surface px-3 text-xs font-medium text-ink hover:bg-hover">
                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                        <path d="M12 3v12" />
                        <path d="m7 8 5-5 5 5" />
                        <path d="M5 21h14" />
                    </svg>
                    {{ $previewUrl ? 'Replace image' : 'Choose image' }}
                </label>
                @if ($file)
                    <button type="button" wire:click="$set('{{ $model }}', null)"
                            class="inline-flex h-8 items-center rounded-md px-3 text-xs font-medium text-danger
                                   transition-colors hover:bg-danger/10">
                        Remove
                    </button>
                @endif
            </div>

            @if ($file)
                <p class="mt-2 inline-flex max-w-full items-center gap-1.5 rounded-md border border-line bg-hover px-2 py-1 text-xs text-ink-muted">
                    <svg class="h-3.5 w-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                    </svg>
                    <span class="truncate">{{ $file->getClientOriginalName() }}</span>
                    <span class="shrink-0 text-ink-subtle">{{ $fileSize }}</span>
                </p>
            @endif

            @error($model)
                <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
            @enderror
            @if ($hint && ! $errors->has($model))
                <p id="{{ $uid }}-hint" class="mt-1.5 text-xs text-ink-muted">{{ $hint }}</p>
            @endif
        </div>
    </div>
</div>