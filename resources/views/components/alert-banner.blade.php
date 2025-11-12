@props(['on'])

<div
    x-data="{ show: false, type: 'success', message: '' }"
    x-on:alert.window="show = true; type = $event.detail.type; message = $event.detail.message; setTimeout(() => show = false, 5000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-2"
    class="fixed top-4 right-4 z-50 rounded-lg p-4"
    x-bind:class="{
        'bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:border-green-700 dark:text-green-200': type === 'success',
        'bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-200': type === 'error'
    }"
    style="display: none;"
>
    <div class="flex items-center">
        <svg x-show="type === 'success'" class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l3 3m6-6.75l-9 9L3 10.5" />
        </svg>
        <svg x-show="type === 'error'" class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        
        <span x-text="message"></span>

        <button type="button" @click="show = false" class="ml-4 -mr-1.5 -my-1.5 p-1.5 rounded-full"
            x-bind:class="{
                'hover:bg-green-200 dark:hover:bg-green-800': type === 'success',
                'hover:bg-red-200 dark:hover:bg-red-800': type === 'error'
            }"
        >
            <span class="sr-only">Close</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>