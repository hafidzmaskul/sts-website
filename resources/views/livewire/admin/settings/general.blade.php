<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-black">General Settings</h1>
        <p class="mt-1 text-sm text-black">Click a panel to update its settings.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Site Information Panel -->
        <flux:modal.trigger name="site-information">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-black">Site Information Panel</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
                    <li>Site Title</li>
                    <li>Site Logo</li>
                    <li>Contact Information</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Shipping Settings -->
        <flux:modal.trigger name="shipping-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-black">Shipping Settings</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
                    <li>Method 1</li>
                    <li>Method 2</li>
                    <li>Method 3</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Payment Settings -->
        <flux:modal.trigger name="payment-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-black">Payment Settings</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
                    <li>Method 1</li>
                    <li>Method 2</li>
                    <li>Method 3</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Tax Settings -->
        <flux:modal.trigger name="tax-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-black">Tax Settings</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
                    <li>Transaction Tax %</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Integration Panel -->
        <flux:modal.trigger name="integration-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-black">Integration Panel</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
                    <li>GA / GTM tags</li>
                    <li>Header & Footer scripts</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- SEO Settings -->
        <flux:modal.trigger name="seo-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-black">SEO Settings</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
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
                <h3 class="text-base font-semibold text-black">Robots.txt Editor</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
                    <li>Edit robots.txt</li>
                </ul>
            </div>
        </flux:modal.trigger>

        <!-- Mail Settings (System Mailer) -->
        <flux:modal.trigger name="mail-settings">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                <h3 class="text-base font-semibold text-black">Mail Settings (System Mailer)</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
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
                <h3 class="text-base font-semibold text-black">Email Notifications (Admin)</h3>
                <ul class="mt-4 space-y-2 text-sm text-black">
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
            <p class="text-sm text-black">Live at: <a href="/robots.txt" target="_blank"
                    class="underline hover:text-black">/robots.txt</a></p>
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
                    <label class="block text-sm font-medium text-black mb-2">Default Share Image</label>
                    <input type="file" wire:model="seo_share_image" class="block w-full text-sm text-black
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-black
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
                    <label class="block text-sm font-medium text-black mb-2">Site Logo</label>
                    <div class="flex items-center gap-4">
                        <input type="file" wire:model="site_logo" class="block w-full text-sm text-black
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-black
                            hover:file:bg-indigo-100
                        " />
                    </div>
                    @if($site_logo_path)
                        <div class="mt-2 text-xs">
                            Current: <a href="{{ Storage::url($site_logo_path) }}" target="_blank"
                                class="underline text-black">Open</a>
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
            <p class="text-sm text-black">This email will receive all administrative notifications.</p>
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
                    <label class="block text-sm font-medium text-black mb-2">Encryption</label>
                    <select wire:model="mail_encryption"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 text-black">
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
    <!-- Shipping Settings Modal -->
    <flux:modal name="shipping-settings" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">Shipping Settings</flux:heading>
        </div>

        <div class="space-y-6">
            <!-- Method 1 -->
            <div class="space-y-4 border-b border-gray-200 pb-4">
                <h3 class="font-medium text-black">Shipping Method 1</h3>
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="shipping_method_1_name" label="Name" placeholder="Standard Shipping" />
                    <flux:input wire:model="shipping_method_1_price" label="Price" placeholder="0.00" />
                </div>
                <flux:input wire:model="shipping_method_1_desc" label="Description"
                    placeholder="Delivery in 5-7 business days" />
            </div>

            <!-- Method 2 -->
            <div class="space-y-4 border-b border-gray-200 pb-4">
                <h3 class="font-medium text-black">Shipping Method 2</h3>
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="shipping_method_2_name" label="Name" placeholder="Express Shipping" />
                    <flux:input wire:model="shipping_method_2_price" label="Price" placeholder="9.99" />
                </div>
                <flux:input wire:model="shipping_method_2_desc" label="Description"
                    placeholder="Delivery in 2-3 business days" />
            </div>

            <!-- Method 3 -->
            <div class="space-y-4">
                <h3 class="font-medium text-black">Shipping Method 3</h3>
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="shipping_method_3_name" label="Name" placeholder="Overnight Shipping" />
                    <flux:input wire:model="shipping_method_3_price" label="Price" placeholder="19.99" />
                </div>
                <flux:input wire:model="shipping_method_3_desc" label="Description" placeholder="Next day delivery" />
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="saveShippingSettings">Save</flux:button>
        </div>
    </flux:modal>
    <!-- Payment Settings Modal -->
    <flux:modal name="payment-settings" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">Payment Settings</flux:heading>
        </div>

        <div class="space-y-6">
            <!-- Method 1 -->
            <div class="space-y-4 border-b border-gray-200 pb-4">
                <h3 class="font-medium text-black">Payment Method 1</h3>
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="payment_method_1_name" label="Name" placeholder="Credit / Debit Card" />
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Icon</label>
                        <div class="flex items-center gap-4">
                            <input type="file" wire:model="payment_method_1_icon" class="block w-full text-sm text-black
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-black
                                hover:file:bg-indigo-100
                            " />
                        </div>
                        @if($payment_method_1_icon_path)
                            <div class="mt-2 text-xs">
                                Current: <a href="{{ Storage::url($payment_method_1_icon_path) }}" target="_blank"
                                    class="underline text-black">Open</a>
                            </div>
                        @endif
                    </div>
                </div>
                <flux:input wire:model="payment_method_1_desc" label="Description"
                    placeholder="Pay securely with your credit or debit card" />
            </div>

            <!-- Method 2 -->
            <div class="space-y-4 border-b border-gray-200 pb-4">
                <h3 class="font-medium text-black">Payment Method 2</h3>
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="payment_method_2_name" label="Name" placeholder="PayPal" />
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Icon</label>
                        <div class="flex items-center gap-4">
                            <input type="file" wire:model="payment_method_2_icon" class="block w-full text-sm text-black
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-black
                                hover:file:bg-indigo-100
                            " />
                        </div>
                        @if($payment_method_2_icon_path)
                            <div class="mt-2 text-xs">
                                Current: <a href="{{ Storage::url($payment_method_2_icon_path) }}" target="_blank"
                                    class="underline text-black">Open</a>
                            </div>
                        @endif
                    </div>
                </div>
                <flux:input wire:model="payment_method_2_desc" label="Description"
                    placeholder="You will be redirected to PayPal to complete payment" />
            </div>

            <!-- Method 3 -->
            <div class="space-y-4">
                <h3 class="font-medium text-black">Payment Method 3</h3>
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="payment_method_3_name" label="Name" placeholder="Bank Transfer" />
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Icon</label>
                        <div class="flex items-center gap-4">
                            <input type="file" wire:model="payment_method_3_icon" class="block w-full text-sm text-black
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-black
                                hover:file:bg-indigo-100
                            " />
                        </div>
                        @if($payment_method_3_icon_path)
                            <div class="mt-2 text-xs">
                                Current: <a href="{{ Storage::url($payment_method_3_icon_path) }}" target="_blank"
                                    class="underline text-black">Open</a>
                            </div>
                        @endif
                    </div>
                </div>
                <flux:input wire:model="payment_method_3_desc" label="Description"
                    placeholder="Transfer funds directly from your bank account" />
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="savePaymentSettings">Save</flux:button>
        </div>
    </flux:modal>
    <!-- Tax Settings Modal -->
    <flux:modal name="tax-settings" class="min-w-[40rem] space-y-6">
        <div>
            <flux:heading size="lg">Tax Settings</flux:heading>
        </div>

        <div class="space-y-6">
            <flux:input wire:model="transaction_tax" label="Transaction Tax (%)" placeholder="20" />
            <p class="text-sm text-gray-500">Default tax percentage applied to transactions.</p>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="saveTaxSettings">Save</flux:button>
        </div>
    </flux:modal>
</div>