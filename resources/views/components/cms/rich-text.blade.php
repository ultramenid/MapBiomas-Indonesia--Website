@props([
    'field', // Livewire property to sync with
    'label' => null,
    'height' => 420,
])

@php
$id = 'richtext-' . $field;
@endphp

<div>
    @if ($label)
        <span class="mb-1.5 block text-xs font-medium text-ink">{{ $label }}</span>
    @endif
    <div wire:ignore
         x-init="const initEditor = () => {
            try {
                const existing = tinymce.get('{{ $id }}');
                if (existing) { existing.save(); existing.remove(); }
            } catch (e) {}
            const dark = document.documentElement.classList.contains('dark');
            tinymce.init({
                selector: '#{{ $id }}',
                height: {{ $height }},
                promotion: false,
                branding: false,
                license_key: 'gpl',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                highlight_on_focus: false,
                skin: dark ? 'oxide-dark' : 'oxide',
                content_css: 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
                content_style: dark
                    ? 'body { background-color: #151515; color: #ededed; font-family: Open Sans, ui-sans-serif, system-ui, sans-serif; font-size: 14px; line-height: 1.6; } a { color: #86b696; }'
                    : 'body { background-color: #ffffff; color: #171717; font-family: Open Sans, ui-sans-serif, system-ui, sans-serif; font-size: 14px; line-height: 1.6; } a { color: #4e7a5e; }',
                plugins: 'lists advlist autolink link image charmap anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons help',
                toolbar: 'undo redo | bold italic underline forecolor backcolor | link image | bullist numlist alignleft aligncenter alignright alignjustify outdent indent | removeformat | fullscreen help',
                menubar: 'file edit view insert format tools table help',
                images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route('cms.media.upload') }}');
                    if (token) xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    xhr.upload.onprogress = (e) => {
                        progress(e.loaded / e.total * 100);
                    };
                    xhr.onload = () => {
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('Upload failed with HTTP ' + xhr.status);
                            return;
                        }
                        try {
                            const json = JSON.parse(xhr.responseText);
                            if (!json || typeof json.location !== 'string') {
                                reject('Invalid response from server');
                                return;
                            }
                            resolve(json.location);
                        } catch (err) {
                            reject('Failed to parse response: ' + err.message);
                        }
                    };
                    xhr.onerror = () => reject('Image upload network error.');
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                }),
                file_picker_callback: function(callback, value, meta) {
                    const x = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                    const y = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                    const typeParam = meta.filetype === 'image' ? 'image' : (meta.filetype === 'media' ? 'file' : 'all');
                    const pickerUrl = '{{ route('cms.media.index') }}?picker=1&type=' + typeParam;

                    tinymce.activeEditor.windowManager.openUrl({
                        url: pickerUrl,
                        title: meta.filetype === 'image' ? 'Media Manager - Images' : 'Media Manager - Files',
                        width: Math.min(x * 0.92, 1100),
                        height: Math.min(y * 0.88, 750),
                        resizable: 'yes',
                        close_previous: 'no',
                        onMessage: (api, message) => {
                            if (message.mceAction === 'insertFile' || message.mceAction === 'customAction') {
                                callback(message.content, message.params || { alt: '' });
                                api.close();
                            }
                        }
                    });
                },
                setup: function(editor) {
                    editor.on('change keyup', function(e) {
                        @this.set('{{ $field }}', editor.getContent());
                    });
                }
            });
        };
        initEditor();
        window.addEventListener('cms-theme-changed', initEditor);">
        <textarea id="{{ $id }}" name="{{ $field }}" rows="12" style="visibility:hidden">{{ $slot }}</textarea>
    </div>
</div>