<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

use App\Models\Setting;
use Livewire\Attributes\Title;

use Livewire\WithFileUploads;

#[Title('General Settings')]
class General extends Component
{
    use WithFileUploads;

    // Mail Settings
    public $mail_mailer;
    public $mail_scheme;
    public $mail_host;
    public $mail_port;
    public $mail_username;
    public $mail_password;
    public $mail_encryption;
    public $mail_from_address;
    public $mail_from_name;

    // Email Notification Settings
    public $email_notification_admin;

    // Robots.txt
    public $robots_txt;

    // SEO Settings
    public $seo_meta_title;
    public $seo_meta_description;
    public $seo_share_image;
    public $seo_share_image_path;
    public $seo_canonical_url;
    public $seo_schema_enabled = false;

    // Integration Settings
    public $integration_ga_measurement_id;
    public $integration_gtm_container_id;
    public $integration_custom_script_header;
    public $integration_custom_script_footer;

    // Site Information
    public $site_title;
    public $site_logo;
    public $site_logo_path;
    public $contact_name;
    public $contact_email;
    public $contact_phone;
    public $contact_address;

    public function mount()
    {
        $this->mail_mailer = Setting::where('key', 'mail_mailer')->value('value');
        $this->mail_scheme = Setting::where('key', 'mail_scheme')->value('value');
        $this->mail_host = Setting::where('key', 'mail_host')->value('value');
        $this->mail_port = Setting::where('key', 'mail_port')->value('value');
        $this->mail_username = Setting::where('key', 'mail_username')->value('value');
        $this->mail_password = Setting::where('key', 'mail_password')->value('value');
        $this->mail_encryption = Setting::where('key', 'mail_encryption')->value('value');
        $this->mail_from_address = Setting::where('key', 'mail_from_address')->value('value');
        $this->mail_from_name = Setting::where('key', 'mail_from_name')->value('value');

        $this->email_notification_admin = Setting::where('key', 'email_notification_admin')->value('value');

        $this->robots_txt = file_exists(public_path('robots.txt'))
            ? file_get_contents(public_path('robots.txt'))
            : '';

        $this->seo_meta_title = Setting::where('key', 'seo_meta_title')->value('value');
        $this->seo_meta_description = Setting::where('key', 'seo_meta_description')->value('value');
        $this->seo_share_image_path = Setting::where('key', 'seo_share_image')->value('value');
        $this->seo_canonical_url = Setting::where('key', 'seo_canonical_url')->value('value');
        $this->seo_schema_enabled = Setting::where('key', 'seo_schema_enabled')->value('value') === '1';

        // Integration Settings
        $this->integration_ga_measurement_id = Setting::where('key', 'integration_ga_measurement_id')->value('value');
        $this->integration_gtm_container_id = Setting::where('key', 'integration_gtm_container_id')->value('value');
        $this->integration_custom_script_header = Setting::where('key', 'integration_custom_script_header')->value('value');
        $this->integration_custom_script_footer = Setting::where('key', 'integration_custom_script_footer')->value('value');

        // Site Information
        $this->site_title = Setting::where('key', 'site_title')->value('value');
        $this->site_logo_path = Setting::where('key', 'site_logo')->value('value');
        $this->contact_name = Setting::where('key', 'contact_name')->value('value');
        $this->contact_email = Setting::where('key', 'contact_email')->value('value');
        $this->contact_phone = Setting::where('key', 'contact_phone')->value('value');
        $this->contact_address = Setting::where('key', 'contact_address')->value('value');
    }

    public function saveMailSettings()
    {
        $settings = [
            'mail_mailer' => $this->mail_mailer,
            'mail_scheme' => $this->mail_scheme,
            'mail_host' => $this->mail_host,
            'mail_port' => $this->mail_port,
            'mail_username' => $this->mail_username,
            'mail_password' => $this->mail_password,
            'mail_encryption' => $this->mail_encryption,
            'mail_from_address' => $this->mail_from_address,
            'mail_from_name' => $this->mail_from_name,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->dispatch('close-modal', 'mail-settings');
        $this->dispatch('notify', message: 'Mail settings updated successfully.');
    }

    public function saveEmailNotificationSettings()
    {
        Setting::updateOrCreate(
            ['key' => 'email_notification_admin'],
            ['value' => $this->email_notification_admin]
        );

        $this->dispatch('close-modal', 'email-notification-settings');
        $this->dispatch('notify', message: 'Email notification settings updated successfully.');
    }

    public function saveRobotsTxt()
    {
        file_put_contents(public_path('robots.txt'), $this->robots_txt);
        $this->dispatch('close-modal', 'robots-txt-editor');
        $this->dispatch('notify', message: 'Robots.txt updated successfully.');
    }

    public function saveSeoSettings()
    {
        $settings = [
            'seo_meta_title' => $this->seo_meta_title,
            'seo_meta_description' => $this->seo_meta_description,
            'seo_canonical_url' => $this->seo_canonical_url,
            'seo_schema_enabled' => $this->seo_schema_enabled ? '1' : '0',
        ];

        if ($this->seo_share_image) {
            $path = $this->seo_share_image->store('seo', 'public');
            $settings['seo_share_image'] = $path;
        }

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->dispatch('close-modal', 'seo-settings');
        $this->dispatch('notify', message: 'SEO settings updated successfully.');
    }

    public function saveIntegrationSettings()
    {
        Setting::updateOrCreate(
            ['key' => 'integration_ga_measurement_id'],
            ['value' => $this->integration_ga_measurement_id]
        );
        Setting::updateOrCreate(
            ['key' => 'integration_gtm_container_id'],
            ['value' => $this->integration_gtm_container_id]
        );
        Setting::updateOrCreate(
            ['key' => 'integration_custom_script_header'],
            ['value' => $this->integration_custom_script_header]
        );
        Setting::updateOrCreate(
            ['key' => 'integration_custom_script_footer'],
            ['value' => $this->integration_custom_script_footer]
        );

        $this->dispatch('close-modal', 'integration-settings');
        $this->dispatch('notify', message: 'Integration settings updated successfully.');
    }

    public function saveSiteInfo()
    {
        $settings = [
            'site_title' => $this->site_title,
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'contact_address' => $this->contact_address,
        ];

        if ($this->site_logo) {
            $path = $this->site_logo->store('site', 'public');
            $settings['site_logo'] = $path;
        }

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->dispatch('close-modal', 'site-information');
        $this->dispatch('notify', message: 'Site information updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings.general');
    }
}
