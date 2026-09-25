<div x-data="mediaManager({
        isPicker: {{ $isPicker ? 'true' : 'false' }},
        initialTab: '{{ $defaultType === 'file' ? 'document' : ($defaultType === 'image' ? 'image' : 'all') }}',
        listUrl: '{{ route('cms.media.list') }}',
        uploadUrl: '{{ route('cms.media.upload') }}',
        deleteUrl: '{{ route('cms.media.delete') }}',
        csrfToken: '{{ csrf_token() }}'
    })"
    class="flex flex-col gap-6">

    {{-- Top Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-line">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl font-bold text-ink">Media Library</h1>
                <span class="rounded-full bg-hover px-2.5 py-0.5 text-xs font-medium text-ink-muted"
                      x-text="filteredFiles.length + ' items'"></span>
            </div>
            <p class="text-xs text-ink-subtle mt-0.5">
                Manage, upload, and select images or files for MapBiomas content.
            </p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Search input --}}
            <div class="relative w-full sm:w-64">
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Search files..."
                       class="w-full rounded-md border border-line bg-surface px-3 py-1.5 pl-8 text-xs text-ink placeholder:text-ink-subtle focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent" />
                <svg class="absolute left-2.5 top-2 h-3.5 w-3.5 text-ink-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            {{-- Upload trigger button --}}
            <button type="button"
                    @click="$refs.fileInput.click()"
                    :disabled="isUploading"
                    class="inline-flex items-center gap-1.5 rounded-md bg-accent px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:opacity-90 transition-opacity disabled:opacity-50 shrink-0">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Upload</span>
            </button>
            <input type="file"
                   x-ref="fileInput"
                   @change="handleFileSelect($event)"
                   multiple
                   accept="image/jpeg,image/png,image/gif,image/webp,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt"
                   class="hidden" />
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 border-b border-line pb-2">
        <button type="button"
                @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'bg-hover text-ink font-semibold' : 'text-ink-muted hover:text-ink'"
                class="rounded-md px-3 py-1.5 text-xs transition-colors">
            All Media
        </button>
        <button type="button"
                @click="activeTab = 'image'"
                :class="activeTab === 'image' ? 'bg-hover text-ink font-semibold' : 'text-ink-muted hover:text-ink'"
                class="rounded-md px-3 py-1.5 text-xs transition-colors flex items-center gap-1.5">
            <span>Images</span>
            <span class="rounded bg-line px-1.5 py-0.2 text-[10px]" x-text="files.filter(f => f.type === 'image').length"></span>
        </button>
        <button type="button"
                @click="activeTab = 'document'"
                :class="activeTab === 'document' ? 'bg-hover text-ink font-semibold' : 'text-ink-muted hover:text-ink'"
                class="rounded-md px-3 py-1.5 text-xs transition-colors flex items-center gap-1.5">
            <span>Documents</span>
            <span class="rounded bg-line px-1.5 py-0.2 text-[10px]" x-text="files.filter(f => f.type === 'document').length"></span>
        </button>
    </div>

    {{-- Dropzone / Upload area --}}
    <div @dragover.prevent="isDragging = true"
         @dragleave.prevent="isDragging = false"
         @drop.prevent="handleDrop($event)"
         :class="isDragging ? 'border-accent bg-accent/5' : 'border-line/60 bg-surface/40 hover:bg-surface/70'"
         class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed p-6 transition-all cursor-pointer"
         @click="$refs.fileInput.click()">
        
        <template x-if="!isUploading">
            <div class="flex flex-col items-center gap-1.5 text-center pointer-events-none">
                <svg class="h-8 w-8 text-ink-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="text-xs font-medium text-ink">
                    Drag and drop files here, or <span class="text-accent underline">browse</span>
                </p>
                <p class="text-[11px] text-ink-subtle">
                    Supported: JPG, PNG, WEBP, GIF, PDF, DOCX, XLSX (max 25MB). Auto-hashed & validated.
                </p>
            </div>
        </template>

        <template x-if="isUploading">
            <div class="flex flex-col items-center gap-2 text-center pointer-events-none">
                <svg class="h-6 w-6 animate-spin text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-xs font-medium text-ink" x-text="uploadStatus"></p>
            </div>
        </template>
    </div>

    {{-- Error message alert --}}
    <div x-show="errorMessage" x-cloak
         class="rounded-lg border border-red-500/20 bg-red-500/10 p-3 text-xs text-red-600 dark:text-red-400 flex items-center justify-between">
        <span x-text="errorMessage"></span>
        <button type="button" @click="errorMessage = ''" class="font-bold ml-2">&times;</button>
    </div>

    {{-- Media Grid --}}
    <div>
        {{-- Loading Skeleton --}}
        <template x-if="isLoading">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <template x-for="i in 12" :key="i">
                    <div class="aspect-square rounded-lg bg-surface animate-pulse border border-line"></div>
                </template>
            </div>
        </template>

        {{-- Empty state --}}
        <template x-if="!isLoading && filteredFiles.length === 0">
            <div class="flex flex-col items-center justify-center py-16 text-center text-ink-subtle">
                <svg class="h-12 w-12 text-ink-subtle mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-sm font-medium text-ink">No media files found</p>
                <p class="text-xs text-ink-subtle mt-1">Upload a file or change your search filter.</p>
            </div>
        </template>

        {{-- Grid items --}}
        <div x-show="!isLoading && filteredFiles.length > 0"
             class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <template x-for="file in filteredFiles" :key="file.path">
                <div class="group relative flex flex-col rounded-lg border border-line bg-surface overflow-hidden hover:border-accent hover:shadow-md transition-all cursor-pointer"
                     :class="selectedFile?.path === file.path ? 'ring-2 ring-accent border-accent' : ''"
                     @click="selectedFile = file"
                     @dblclick="selectAndInsert(file)">

                    {{-- Image / File Preview --}}
                    <div class="relative aspect-square w-full bg-line/20 flex items-center justify-center overflow-hidden">
                        <template x-if="file.type === 'image'">
                            <img :src="file.url"
                                 :alt="file.name"
                                 loading="lazy"
                                 class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-105" />
                        </template>

                        <template x-if="file.type !== 'image'">
                            <div class="flex flex-col items-center gap-1.5 p-3 text-center">
                                <svg class="h-10 w-10 text-accent/80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="rounded bg-line px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-ink-muted"
                                      x-text="file.extension"></span>
                            </div>
                        </template>

                        {{-- Hover Overlay with Action Buttons --}}
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-2 pointer-events-auto">
                            <div class="flex justify-end gap-1.5">
                                <button type="button"
                                        @click.stop="copyUrl(file.url)"
                                        title="Copy URL"
                                        class="rounded bg-white/20 hover:bg-white/40 text-white p-1 transition-colors">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                                <button type="button"
                                        @click.stop="deleteFile(file)"
                                        title="Delete File"
                                        class="rounded bg-red-600/80 hover:bg-red-600 text-white p-1 transition-colors">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            <button type="button"
                                    @click.stop="selectAndInsert(file)"
                                    class="w-full rounded bg-accent py-1 text-center text-xs font-semibold text-white shadow hover:opacity-90 transition-opacity">
                                <span x-text="isPicker ? 'Insert' : 'Select'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- File details --}}
                    <div class="p-2 text-left">
                        <p class="truncate text-xs font-medium text-ink" :title="file.name" x-text="file.name"></p>
                        <div class="flex items-center justify-between text-[10px] text-ink-subtle mt-0.5">
                            <span x-text="file.size"></span>
                            <span x-text="file.modified_formatted"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mediaManager', (config) => ({
        isPicker: config.isPicker,
        activeTab: config.initialTab || 'all',
        searchQuery: '',
        files: [],
        selectedFile: null,
        isLoading: true,
        isUploading: false,
        uploadStatus: '',
        errorMessage: '',
        isDragging: false,

        init() {
            this.loadFiles();
        },

        get filteredFiles() {
            return this.files.filter(file => {
                const matchesTab = this.activeTab === 'all' || file.type === this.activeTab;
                const matchesSearch = !this.searchQuery || file.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchesTab && matchesSearch;
            });
        },

        async loadFiles() {
            this.isLoading = true;
            try {
                const res = await fetch(config.listUrl);
                if (!res.ok) throw new Error('Failed to load media files.');
                const data = await res.json();
                this.files = data.files || [];
            } catch (err) {
                this.errorMessage = err.message;
            } finally {
                this.isLoading = false;
            }
        },

        async handleFileSelect(e) {
            const files = Array.from(e.target.files);
            if (!files.length) return;
            await this.uploadFiles(files);
            e.target.value = '';
        },

        async handleDrop(e) {
            this.isDragging = false;
            const files = Array.from(e.dataTransfer.files);
            if (!files.length) return;
            await this.uploadFiles(files);
        },

        async uploadFiles(files) {
            this.isUploading = true;
            this.errorMessage = '';
            let count = 0;

            for (const file of files) {
                count++;
                this.uploadStatus = `Uploading (${count}/${files.length}): ${file.name}...`;

                const formData = new FormData();
                formData.append('file', file);

                try {
                    const res = await fetch(config.uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': config.csrfToken
                        },
                        body: formData
                    });

                    const json = await res.json();
                    if (!res.ok) {
                        const msg = json.message || (json.errors ? Object.values(json.errors).flat().join(' ') : 'Upload error');
                        throw new Error(msg);
                    }

                    if (json.file) {
                        this.files.unshift({
                            path: json.file.path,
                            name: json.file.name,
                            url: json.file.url,
                            size: 'Just now',
                            size_bytes: file.size,
                            modified: Date.now(),
                            modified_formatted: 'Just now',
                            extension: json.file.extension,
                            type: json.file.type
                        });
                    }
                } catch (err) {
                    this.errorMessage = `Error uploading ${file.name}: ${err.message}`;
                    break;
                }
            }

            this.isUploading = false;
            this.uploadStatus = '';
        },

        async deleteFile(file) {
            if (!confirm(`Are you sure you want to delete "${file.name}"?`)) {
                return;
            }

            try {
                const res = await fetch(config.deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken
                    },
                    body: JSON.stringify({ path: file.path })
                });

                if (!res.ok) {
                    const data = await res.json();
                    throw new Error(data.message || 'Failed to delete file.');
                }

                this.files = this.files.filter(f => f.path !== file.path);
                if (this.selectedFile?.path === file.path) {
                    this.selectedFile = null;
                }
            } catch (err) {
                this.errorMessage = err.message;
            }
        },

        copyUrl(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('URL copied to clipboard!');
            });
        },

        selectAndInsert(file) {
            // Post message for TinyMCE windowManager.openUrl
            if (window.parent && window.parent !== window) {
                window.parent.postMessage({
                    mceAction: 'insertFile',
                    content: file.url,
                    params: {
                        alt: file.name,
                        title: file.name
                    }
                }, '*');
            }

            // Fallback for popups
            if (window.opener) {
                try {
                    window.opener.postMessage({
                        mceAction: 'insertFile',
                        content: file.url,
                        params: {
                            alt: file.name,
                            title: file.name
                        }
                    }, '*');
                } catch (e) {}
                window.close();
            }
        }
    }));
});
</script>
