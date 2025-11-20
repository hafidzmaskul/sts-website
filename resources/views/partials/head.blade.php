<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@41.4.2/build/ckeditor.js"></script>

    <script>
        (function () {
            const INPUT_ID='overview_input';
            const EDITOR_ID='overview_editor';
            const LOCK_KEY='ck-lock:'+EDITOR_ID;
            window.__ckLocks=window.__ckLocks||{};
            let editorInstance=null,submitBound=false;
            function setLWProp(elInsideComponent,key,value){
                const root=elInsideComponent.closest('[wire\\:id]'); if(!root) return;
                const cmp=window.Livewire?.find?.(root.getAttribute('wire:id')); if(!cmp) return;
                if(typeof cmp.set==='function') cmp.set(key,value);
                else if(cmp.$wire?.set) cmp.$wire.set(key,value);
            }
            function push(input,editor,host){
                const html=editor.getData()||'';
                if(input.value!==html){
                    input.value=html;
                    input.dispatchEvent(new Event('input',{bubbles:true}));
                    setLWProp(host,'content',html);
                }
            }
            function pull(input,editor){
                const html=input.value||'';
                if(editor.getData()!==html) editor.setData(html);
            }
            async function createEditor(host){
                if(host.querySelector('.ck-editor')) return;
                if(window.__ckLocks[LOCK_KEY]===true) return;
                window.__ckLocks[LOCK_KEY]=true;
                try{
                    if(!window.ClassicEditor){
                        window.__ckLocks[LOCK_KEY]=false;
                        return setTimeout(()=>createEditor(host),60);
                    }
                    if(editorInstance){
                        try{await editorInstance.destroy();}catch(_){}
                        editorInstance=null;
                    }
                    editorInstance=await ClassicEditor.create(host,{
                        placeholder:'Describe the product…',
                        toolbar:['undo','redo','|','heading','|','bold','italic','underline','strikethrough','|','bulletedList','numberedList','|','blockQuote','link','insertTable'],
                        removePlugins:['CKBox','CKFinder','EasyImage','RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges','RealTimeCollaborativeReview'],
                    });
                    const input=document.getElementById(INPUT_ID);
                    pull(input,editorInstance);
                    editorInstance.model.document.on('change:data',()=>push(input,editorInstance,host));
                    if(window.Livewire?.hook){
                        Livewire.hook('message.processed',()=>pull(input,editorInstance));
                    }
                    const form=host.closest('form');
                    if(form&&!submitBound){
                        form.addEventListener('submit',()=>push(input,editorInstance,host),{capture:true});
                        submitBound=true;
                    }
                } finally {
                    window.__ckLocks[LOCK_KEY]=false;
                }
            }
            function init(){
                const input=document.getElementById(INPUT_ID);
                const host=document.getElementById(EDITOR_ID);
                if(!input||!host) return;
                if(host.querySelector('.ck-editor')) return;
                createEditor(host);
            }
            async function cleanup(){
                try{if(editorInstance) await editorInstance.destroy();}catch(_){}
                editorInstance=null;
                const host=document.getElementById(EDITOR_ID);
                if(host) host.innerHTML='';
                window.__ckLocks[LOCK_KEY]=false;
            }
            window.downloadSampleSerialCsv=function(){
                const rows=['serial','ABC12345','ABC12346','ABC12347'];
                const csv=rows.join('\n');
                const blob=new Blob([csv],{type:'text/csv'});
                const url=URL.createObjectURL(blob);
                const a=document.createElement('a');
                a.href=url; a.download='sample-serials.csv';
                document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
            };

            // Tiny toast system for browser events
            function showToast(type, message){
                const root = document.getElementById('toast-root');
                const item = document.createElement('div');
                item.className = 'pointer-events-auto min-w-[260px] max-w-sm rounded-md px-4 py-3 shadow border text-sm ' +
                    (type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' :
                        type === 'error' ? 'bg-red-50 border-red-200 text-red-800' :
                            'bg-gray-50 border-gray-200 text-gray-800');
                item.textContent = message;
                root.appendChild(item);
                setTimeout(()=>{ item.style.opacity='0'; item.style.transition='opacity .3s'; }, 3800);
                setTimeout(()=>{ item.remove(); }, 4200);
            }
            window.addEventListener('notify', (e) => {
                const { type='info', message='' } = e.detail || {};
                if (message) showToast(type, message);
            });

            document.addEventListener('DOMContentLoaded',init);
            window.addEventListener('livewire:navigated',init);
            window.addEventListener('livewire:navigating',cleanup);
            window.addEventListener('beforeunload',cleanup);
        })();
    </script>

@vite(['resources/css/app.css', 'resources/js/app.jsx'])
@fluxAppearance
