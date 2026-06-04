<div>
    <header
        class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold text-black">
                General Settings
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="rounded-2xl bg-white p-6 border">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 text-black">
                            Admin Notification Email
                        </label>
                        <p class="text-lg font-semibold text-black truncate">{{ $adminEmail }}</p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('admin_email')" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 border">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 text-black">
                            Hero Banner Image
                        </label>
                        @if($heroBanner)
                            <img src="{{ Storage::url($heroBanner) }}" alt="Hero Banner"
                                class="mt-2 h-20 w-auto rounded-lg object-cover">
                        @else
                            <p class="text-lg font-semibold text-black">No image set</p>
                        @endif
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('hero_banner')" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 border">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 text-black">
                            VAT Percentage
                        </label>
                        <p class="text-lg font-semibold text-black truncate">{{ $vatPercentage }}%</p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('vat_percentage')" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 border">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 text-black">
                            Robots.txt Content
                        </label>
                        <p class="text-sm text-zinc-500 truncate">Manage search engine indexing rules
                        </p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('robots_txt_content')" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 border">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 text-black">
                            Integration Panel
                        </label>
                        <p class="text-sm text-zinc-500 truncate">Manage Google Analytics, GTM, and
                            custom scripts</p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('integrations')" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 border">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 text-black">
                            Landing Page Settings
                        </label>
                        <p class="text-sm text-zinc-500 truncate">Manage footer, address, contact,
                            and operational hours</p>
                    </div>
                    @can('settings.update')
                        <button wire:click="edit('landing_page')" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                    @endcan
                </div>
            </div>

        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6">
                <h2 class="text-xl font-semibold mb-4 text-black">{{ $editingLabel }}</h2>

                <form wire:submit.prevent="save" class="space-y-5">

                    @if($editingKey === 'admin_email')
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Value</label>
                            <input type="email" wire:model="editingValue"
                                class="w-full rounded-lg border px-3 py-2">
                            @error('editingValue') <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($editingKey === 'hero_banner')
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Upload Image</label>
                            <input type="file" wire:model="heroBannerUpload"
                                class="w-full text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white">

                            <div wire:loading wire:target="heroBannerUpload" class="mt-2 text-sm">
                                Uploading...</div>

                            @if ($heroBannerUpload)
                                <p class="mt-4 text-sm font-medium text-black">New Image Preview:</p>
                                <img src="{{ $heroBannerUpload->temporaryUrl() }}" class="mt-2 h-32 w-auto rounded-lg object-cover">
                            @endif

                            @error('heroBannerUpload') <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($editingKey === 'vat_percentage')
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Percentage (%)</label>
                            <input type="number" step="0.01" wire:model="editingValue"
                                class="w-full rounded-lg border px-3 py-2">
                            @error('editingValue') <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($editingKey === 'robots_txt_content')
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Content</label>
                            <textarea wire:model="editingValue" rows="10"
                                class="w-full rounded-lg border px-3 py-2 font-mono text-sm"></textarea>
                            @error('editingValue') <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($editingKey === 'integrations')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-black">Google Analytics ID</label>
                                <input type="text" wire:model="integrationForm.google_analytics_id"
                                    class="w-full rounded-lg border px-3 py-2"
                                    placeholder="e.g., G-XXXXXXXXXX">
                                @error('integrationForm.google_analytics_id') <p
                                class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 text-black">Google Tag Manager ID</label>
                                <input type="text" wire:model="integrationForm.google_tag_manager_id"
                                    class="w-full rounded-lg border px-3 py-2"
                                    placeholder="e.g., GTM-XXXXXXX">
                                @error('integrationForm.google_tag_manager_id') <p
                                class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 text-black">Custom Script (Header)</label>
                                <textarea wire:model="integrationForm.custom_script_header" rows="5"
                                    class="w-full rounded-lg border px-3 py-2 font-mono text-sm"
                                    placeholder="<script>...</script>"></textarea>
                                @error('integrationForm.custom_script_header') <p
                                class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 text-black">Custom Script (Footer)</label>
                                <textarea wire:model="integrationForm.custom_script_footer" rows="5"
                                    class="w-full rounded-lg border px-3 py-2 font-mono text-sm"
                                    placeholder="<script>...</script>"></textarea>
                                @error('integrationForm.custom_script_footer') <p
                                class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    @if($editingKey === 'landing_page')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-black">Footer Description</label>
                                <textarea wire:model="landingPageForm.footer_description" rows="3"
                                    class="w-full rounded-lg border px-3 py-2 text-sm"
                                    placeholder="Absolutely Human Resources Limited support clients in their Human Resource and Employment Law needs. We ensure that our clients are regularly updated with the current, frequent and ongoing changes to UK Employment Law."></textarea>
                                @error('landingPageForm.footer_description') <p
                                class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Address Label 1</label>
                                    <input type="text" wire:model="landingPageForm.address_label_1"
                                        class="w-full rounded-lg border px-3 py-2"
                                        placeholder="16 Society Road">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Address Label 2</label>
                                    <input type="text" wire:model="landingPageForm.address_label_2"
                                        class="w-full rounded-lg border px-3 py-2"
                                        placeholder="South Queensferry, Edinburgh EH30 9RX">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Contact 1</label>
                                    <input type="text" wire:model="landingPageForm.contact_1"
                                        class="w-full rounded-lg border px-3 py-2"
                                        placeholder="0131 331 2735">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Contact 2</label>
                                    <input type="text" wire:model="landingPageForm.contact_2"
                                        class="w-full rounded-lg border px-3 py-2"
                                        placeholder="07970 797 544">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Operational Time Label
                                        1</label>
                                    <input type="text" wire:model="landingPageForm.time_operational_label_1"
                                        class="w-full rounded-lg border px-3 py-2"
                                        placeholder="24/7">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Operational Time Label
                                        2</label>
                                    <input type="text" wire:model="landingPageForm.time_operational_label_2"
                                        class="w-full rounded-lg border px-3 py-2"
                                        placeholder="Monday to Sunday">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 text-black">Email</label>
                                <input type="email" wire:model="landingPageForm.landing_page_email"
                                    class="w-full rounded-lg border px-3 py-2"
                                    placeholder="info@absolutelyhumanresources.co.uk">
                                @error('landingPageForm.landing_page_email') <p
                                class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 rounded-lg border">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Save
        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
