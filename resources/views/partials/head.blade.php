<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|inter:300,400,500,600,700"
    rel="stylesheet" />
<script defer src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@41.4.2/build/ckeditor.js"></script>

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function() {
        window.__ckLocks = window.__ckLocks || {};
        window.editors = window.editors || {};

        function setLWProp(elInsideComponent, key, value) {
            const root = elInsideComponent.closest('[wire\\:id]');
            if (!root) return;
            const cmp = window.Livewire?.find?.(root.getAttribute('wire:id'));
            if (!cmp) return;
            if (typeof cmp.set === 'function') cmp.set(key, value);
            else if (cmp.$wire?.set) cmp.$wire.set(key, value);
        }

        async function createEditor(host, inputId, wireModel) {
            if (host.querySelector('.ck-editor')) return;
            const lockKey = 'ck-lock:' + host.id;

            if (window.__ckLocks[lockKey] === true) return;
            window.__ckLocks[lockKey] = true;
            try {
                if (!window.ClassicEditor) {
                    window.__ckLocks[lockKey] = false;
                    return setTimeout(() => createEditor(host, inputId, wireModel), 60);
                }

                // Destroy existing instance if any (though unlikely with unique IDs)
                if (window.editors[host.id]) {
                    try {
                        await window.editors[host.id].destroy();
                    } catch (_) {}
                    delete window.editors[host.id];
                }

                const newEditor = await ClassicEditor.create(host, {
                    placeholder: 'Type content here...',
                    toolbar: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', 'underline',
                        'strikethrough', '|', 'bulletedList', 'numberedList', '|', 'blockQuote',
                        'link', 'insertTable'
                    ],
                    removePlugins: ['CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments',
                        'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeReview'
                    ],
                });

                window.editors[host.id] = newEditor;

                const input = document.getElementById(inputId);
                if (input) {
                    pull(input, newEditor);
                    newEditor.model.document.on('change:data', () => push(input, newEditor, host, wireModel));

                    // Hook into Livewire updates
                    if (window.Livewire?.hook) {
                        Livewire.hook('message.processed', () => pull(input, newEditor));
                    }

                    const form = host.closest('form');
                    if (form) {
                        form.addEventListener('submit', () => push(input, newEditor, host, wireModel), {
                            capture: true
                        });
                    }
                }
            } finally {
                window.__ckLocks[lockKey] = false;
            }
        }

        function push(input, editor, host, wireModel) {
            const html = editor.getData() || '';
            if (input.value !== html) {
                input.value = html;
                input.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
                setLWProp(host, wireModel, html);
            }
        }

        function pull(input, editor) {
            const html = input.value || '';
            if (editor && editor.getData() !== html) {
                editor.setData(html);
            }
        }

        function init(editorId = 'overview_editor', inputId = 'overview_input', wireModel = 'content') {
            const input = document.getElementById(inputId);
            const host = document.getElementById(editorId);
            if (!input || !host) return;
            if (host.querySelector('.ck-editor')) return;
            createEditor(host, inputId, wireModel);
        }
        window.initCKEditor = init;

        async function cleanup() {
            for (const id in window.editors) {
                try {
                    await window.editors[id].destroy();
                } catch (_) {}
                const host = document.getElementById(id);
                if (host) host.innerHTML = '';
            }
            window.editors = {};
            window.__ckLocks = {};
        }

        window.downloadSampleSerialCsv = function() {
            const rows = ['serial', 'ABC12345', 'ABC12346', 'ABC12347'];
            const csv = rows.join('\n');
            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'sample-serials.csv';
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        };

        window.addEventListener('notify', (e) => {
            const {
                type = 'success', message = ''
            } = e.detail || {};
            // Map 'info' to 'success' for better UX if needed, or stick to passed type
            const icon = type === 'info' ? 'success' : type;

            if (message) {
                Swal.fire({
                    icon: icon,
                    title: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        });

        // Auto-init for default news editor if present on load
        document.addEventListener('DOMContentLoaded', () => init());
        window.addEventListener('livewire:navigated', () => init());
        window.addEventListener('livewire:navigating', cleanup);
        window.addEventListener('beforeunload', cleanup);
    })();
</script>

@vite(['resources/css/app.css'])
@fluxAppearance
