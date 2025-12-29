<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">General Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Click a panel to update its settings.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Site Information Panel -->
        <flux:modal.trigger name="site-information">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-gray-900">Site Information Panel</h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-500">
                    <li>Site Title</li>
                    <li>Site Logo</li>
                    <li>Contact Information</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Integration Panel -->
        <flux:modal.trigger name="integration-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-gray-900">Integration Panel</h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-500">
                    <li>GA / GTM tags</li>
                    <li>Header & Footer scripts</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- SEO Settings -->
        <flux:modal.trigger name="seo-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-gray-900">SEO Settings</h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-500">
                    <li>Default meta</li>
                    <li>Canonical</li>
                    <li>Schema & sitemap</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Robots.txt Editor -->
        <flux:modal.trigger name="robots-txt-editor">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-gray-900">Robots.txt Editor</h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-500">
                    <li>Edit robots.txt</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Mail Settings (System Mailer) -->
        <flux:modal.trigger name="mail-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-gray-900">Mail Settings (System Mailer)</h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-500">
                    <li>Mailer / Host / Port</li>
                    <li>Username / Password</li>
                    <li>Encryption</li>
                    <li>From address / name</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Email Notifications (Admin) -->
        <flux:modal.trigger name="email-notification-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-gray-900">Email Notifications (Admin)</h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-500">
                    <li>Admin Email</li>
                </ul>
            </div>
        </flux:modal.trigger>
    </div>

    <!-- Robots.txt Editor Modal -->
    <flux:modal name="robots-txt-editor" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">robots.txt</flux:heading>
        </div>

        <div class="space-y-4">
            <flux:textarea wire:model="robots_txt" label="robots.txt" rows="15" />
            <p class="text-sm text-gray-500">Live at: <a href="/robots.txt" target="_blank"
                    class="underline hover:text-gray-700">/robots.txt</a></p>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="saveRobotsTxt">Save</flux:button>
        </div>
    </flux:modal>

    <!-- SEO Settings Modal -->
    <flux:modal name="seo-settings" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">SEO Defaults</flux:heading>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="seo_meta_title" label="Default Meta Title"
                    placeholder="GlobalFire Equipment UK" />
                <flux:input wire:model="seo_meta_description" label="Default Meta Description" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Share Image</label>
                    <input type="file" wire:model="seo_share_image" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100
                    " />
                </div>
                <flux:input wire:model="seo_canonical_url" label="Canonical URL"
                    placeholder="https://www.example.com" />
            </div>

            <flux:checkbox wire:model="seo_schema_enabled" label="Enable Schema Markup (JSON-LD)" />
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="saveSeoSettings">Save</flux:button>
        </div>
    </flux:modal>

    <!-- Integration Settings Modal -->
    <flux:modal name="integration-settings" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">Integration</flux:heading>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="integration_ga_measurement_id" label="Google Analytics (Measurement ID)"
                    placeholder="G-XXXXXXXXXX" />
                <flux:input wire:model="integration_gtm_container_id" label="Google Tag Manager (Container ID)"
                    placeholder="GTM-XXXXXXX" />
            </div>

            <flux:textarea wire:model="integration_custom_script_header" label="Custom Script (Header)" rows="6" />
            <flux:textarea wire:model="integration_custom_script_footer" label="Custom Script (Footer)" rows="6" />
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="saveIntegrationSettings">Save</flux:button>
        </div>
    </flux:modal>

    <!-- Site Information Modal -->
    <flux:modal name="site-information" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">Site Information</flux:heading>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="site_title" label="Site Title" placeholder="GlobalFire Equipment UK" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                    <div class="flex items-center gap-4">
                        <input type="file" wire:model="site_logo" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                        " />
                    </div>
                    @if($site_logo_path)
                        <div class="mt-2 text-xs">
                            Current: <a href="{{ Storage::url($site_logo_path) }}" target="_blank"
                                class="underline">Open</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="contact_name" label="Contact Name" placeholder="Global Fire" />
                <flux:input wire:model="contact_email" label="Contact Email"
                    placeholder="info@globalfire-equipment.com" />
            </div>

            <flux:input wire:model="contact_phone" label="Contact Phone" placeholder="20 3404 6790" />

            <flux:textarea wire:model="contact_address" label="Contact Address" rows="4" />
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="saveSiteInfo">Save</flux:button>
        </div>
    </flux:modal>

    <!-- Email Notification Settings Modal -->
    <flux:modal name="email-notification-settings" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">Email Notifications (Admin)</flux:heading>
        </div>

        <div class="space-y-4">
            <flux:input wire:model="email_notification_admin" label="Admin Notification (Admin Only)"
                placeholder="admin@example.com" />
            <p class="text-sm text-gray-500">This email will receive all administrative notifications.</p>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="saveEmailNotificationSettings">Save</flux:button>
        </div>
    </flux:modal>

    <!-- Mail Settings Modal -->
    <flux:modal name="mail-settings" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">Mail Settings (System Mailer)</flux:heading>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="mail_mailer" label="Mailer" />
                <flux:input wire:model="mail_scheme" label="Scheme" placeholder="smtp / null" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="mail_host" label="Host" />
                <flux:input wire:model="mail_port" label="Port" placeholder="587" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="mail_username" label="Username" />
                <flux:input wire:model="mail_password" label="Password" type="password" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                    <select wire:model="mail_encryption"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3">
                        <option value="">None</option>
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                    </select>
                </div>
                <flux:input wire:model="mail_from_address" label="From Address" />
            </div>

            <flux:input wire:model="mail_from_name" label="From Name" />
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="saveMailSettings">Save</flux:button>
        </div>
    </flux:modal>
</div>