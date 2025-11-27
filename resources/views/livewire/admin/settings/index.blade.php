<div>
    <header
        class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                General Settings
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">
                            Admin Notification Email
                        </label>
                        <p class="text-lg font-semibold dark:text-white truncate">{{ $adminEmail }}</p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('admin_email')"
                            class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">
                            Setting
                        </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">
                            Hero Banner Image
                        </label>
                        @if($heroBanner)
                            <img src="{{ Storage::url($heroBanner) }}" alt="Hero Banner"
                                class="mt-2 h-20 w-auto rounded-lg object-cover">
                        @else
                            <p class="text-lg font-semibold dark:text-white">No image set</p>
                        @endif
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('hero_banner')"
                            class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">
                            Setting
                        </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">
                            VAT Percentage
                        </label>
                        <p class="text-lg font-semibold dark:text-white truncate">{{ $vatPercentage }}%</p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('vat_percentage')"
                            class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">
                            Setting
                        </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">
                            Robots.txt Content
                        </label>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">Manage search engine indexing rules
                        </p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('robots_txt_content')"
                            class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">
                            Setting
                        </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">
                            Integration Panel
                        </label>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">Manage Google Analytics, GTM, and
                            custom scripts</p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('integrations')"
                            class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">
                            Setting
                        </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">
                            Landing Page Settings
                        </label>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">Manage footer, address, contact,
                            and operational hours</p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('landing_page')"
                            class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">
                            Setting
                        </button>
                    @endcan
                </div>
            </div>

        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 dark:bg-zinc-900">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingLabel }}</h2>

                <form wire:submit.prevent="save" class="space-y-5">

                    @if($editingKey === 'admin_email')
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Value</label>
                            <input type="email" wire:model="editingValue"
                                class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                            @error('editingValue') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($editingKey === 'hero_banner')
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Upload Image</label>
                            <input type="file" wire:model="heroBannerUpload"
                                class="w-full text-sm dark:text-zinc-100 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white file:dark:bg-white file:dark:text-zinc-900">

                            <div wire:loading wire:target="heroBannerUpload" class="mt-2 text-sm dark:text-zinc-100">
                                Uploading...</div>

                            @if ($heroBannerUpload)
                                <p class="mt-4 text-sm font-medium dark:text-zinc-100">New Image Preview:</p>
                                <img src="{{ $heroBannerUpload->temporaryUrl() }}" class="mt-2 h-32 w-auto rounded-lg object-cover">
                            @endif

                            @error('heroBannerUpload') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($editingKey === 'vat_percentage')
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Percentage (%)</label>
                            <input type="number" step="0.01" wire:model="editingValue"
                                class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                            @error('editingValue') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($editingKey === 'robots_txt_content')
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Content</label>
                            <textarea wire:model="editingValue" rows="10"
                                class="w-full rounded-lg border px-3 py-2 font-mono text-sm dark:bg-zinc-800 dark:text-white dark:border-zinc-700"></textarea>
                            @error('editingValue') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($editingKey === 'integrations')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Google Analytics ID</label>
                                <input type="text" wire:model="integrationForm.google_analytics_id"
                                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                    placeholder="e.g., G-XXXXXXXXXX">
                                @error('integrationForm.google_analytics_id') <p
                                class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Google Tag Manager ID</label>
                                <input type="text" wire:model="integrationForm.google_tag_manager_id"
                                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                    placeholder="e.g., GTM-XXXXXXX">
                                @error('integrationForm.google_tag_manager_id') <p
                                class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Custom Script (Header)</label>
                                <textarea wire:model="integrationForm.custom_script_header" rows="5"
                                    class="w-full rounded-lg border px-3 py-2 font-mono text-sm dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                    placeholder="<script>...</script>"></textarea>
                                @error('integrationForm.custom_script_header') <p
                                class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Custom Script (Footer)</label>
                                <textarea wire:model="integrationForm.custom_script_footer" rows="5"
                                    class="w-full rounded-lg border px-3 py-2 font-mono text-sm dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                    placeholder="<script>...</script>"></textarea>
                                @error('integrationForm.custom_script_footer') <p
                                class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    @if($editingKey === 'landing_page')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Footer Description</label>
                                <textarea wire:model="landingPageForm.footer_description" rows="3"
                                    class="w-full rounded-lg border px-3 py-2 text-sm dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                    placeholder="Absolutely Human Resources Limited support clients in their Human Resource and Employment Law needs. We ensure that our clients are regularly updated with the current, frequent and ongoing changes to UK Employment Law."></textarea>
                                @error('landingPageForm.footer_description') <p
                                class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Address Label 1</label>
                                    <input type="text" wire:model="landingPageForm.address_label_1"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                        placeholder="16 Society Road">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Address Label 2</label>
                                    <input type="text" wire:model="landingPageForm.address_label_2"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                        placeholder="South Queensferry, Edinburgh EH30 9RX">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Contact 1</label>
                                    <input type="text" wire:model="landingPageForm.contact_1"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                        placeholder="0131 331 2735">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Contact 2</label>
                                    <input type="text" wire:model="landingPageForm.contact_2"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                        placeholder="07970 797 544">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Operational Time Label
                                        1</label>
                                    <input type="text" wire:model="landingPageForm.time_operational_label_1"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                        placeholder="24/7">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Operational Time Label
                                        2</label>
                                    <input type="text" wire:model="landingPageForm.time_operational_label_2"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                        placeholder="Monday to Sunday">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Email</label>
                                <input type="email" wire:model="landingPageForm.landing_page_email"
                                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                    placeholder="info@absolutelyhumanresources.co.uk">
                                @error('landingPageForm.landing_page_email') <p
                                class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>