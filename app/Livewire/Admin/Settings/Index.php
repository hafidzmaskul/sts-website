<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

#[Title('General Settings')]
class Index extends Component
{
    use WithFileUploads;

    public $adminEmail;
    public $heroBanner;
    public $vatPercentage; // [NEW]
    public $robotsTxtContent; // [NEW]
    public $showModal = false;
    public $editingKey = null;
    public $editingLabel = null;
    public $editingValue = null;
    public $heroBannerUpload;

    public function mount()
    {
        $this->loadSettings();
    }

    protected function loadSettings()
    {
        $this->adminEmail = Setting::where('key', 'admin_email')->first()?->value;
        $this->heroBanner = Setting::where('key', 'hero_banner')->first()?->value;
        $this->vatPercentage = Setting::where('key', 'vat_percentage')->first()?->value ?? '0';

        $this->robotsTxtContent = Setting::where('key', 'robots_txt_content')->first()?->value;
        if (!$this->robotsTxtContent && file_exists(public_path('robots.txt'))) {
            $this->robotsTxtContent = file_get_contents(public_path('robots.txt'));
        }
    }

    public function edit($key)
    {
        if (Gate::denies('settings.update')) {
            // --- UPDATED THIS LINE ---
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to edit settings.');
            return;
        }

        $this->editingKey = $key;
        $this->resetErrorBag();
        $this->heroBannerUpload = null;

        if ($key === 'admin_email') {
            $this->editingLabel = 'Admin Notification Email';
            $this->editingValue = $this->adminEmail;
        } elseif ($key === 'hero_banner') {
            $this->editingLabel = 'Hero Banner Image';
            $this->editingValue = null;
        } elseif ($key === 'vat_percentage') { // [NEW]
            $this->editingLabel = 'VAT Percentage';
            $this->editingValue = $this->vatPercentage;
        } elseif ($key === 'robots_txt_content') { // [NEW]
            $this->editingLabel = 'Robots.txt Content';
            $this->editingValue = $this->robotsTxtContent;
        }

        $this->showModal = true;
    }

    public function save()
    {
        if (Gate::denies('settings.update')) {
            // --- UPDATED THIS LINE ---
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to save settings.');
            return;
        }

        $message = 'Setting saved successfully.'; // Default success message

        if ($this->editingKey === 'admin_email') {
            $validated = $this->validate(
                ['editingValue' => 'required|email'],
                ['editingValue.required' => 'The value cannot be empty.', 'editingValue.email' => 'Please provide a valid email address.']
            );

            Setting::updateOrCreate(
                ['key' => 'admin_email'],
                ['value' => $validated['editingValue']]
            );
            $message = 'Admin email updated.';

        } elseif ($this->editingKey === 'hero_banner') {
            $validated = $this->validate(
                ['heroBannerUpload' => 'required|image|max:2048'],
                ['heroBannerUpload.required' => 'Please select an image.', 'heroBannerUpload.image' => 'The file must be an image.']
            );

            if ($this->heroBanner && Storage::disk('public')->exists($this->heroBanner)) {
                Storage::disk('public')->delete($this->heroBanner);
            }

            $path = $this->heroBannerUpload->store('banners', 'public');

            Setting::updateOrCreate(
                ['key' => 'hero_banner'],
                ['value' => $path]
            );
            $message = 'Hero banner updated.';

        } elseif ($this->editingKey === 'vat_percentage') { // [NEW]
            $validated = $this->validate(
                ['editingValue' => 'required|numeric|min:0|max:100'],
                ['editingValue.required' => 'The VAT percentage is required.', 'editingValue.numeric' => 'Must be a number.', 'editingValue.min' => 'Cannot be negative.', 'editingValue.max' => 'Cannot exceed 100.']
            );

            Setting::updateOrCreate(
                ['key' => 'vat_percentage'],
                ['value' => $validated['editingValue']]
            );
            $message = 'VAT percentage updated.';

        } elseif ($this->editingKey === 'robots_txt_content') { // [NEW]
            $validated = $this->validate(
                ['editingValue' => 'required|string'],
                ['editingValue.required' => 'Content cannot be empty.']
            );

            Setting::updateOrCreate(
                ['key' => 'robots_txt_content'],
                ['value' => $validated['editingValue']]
            );

            file_put_contents(public_path('robots.txt'), $validated['editingValue']);

            $message = 'Robots.txt updated.';
        }

        $this->loadSettings();
        $this->closeModal();

        // --- UPDATED THIS LINE ---
        $this->dispatch('alert', type: 'success', message: $message);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingKey = null;
        $this->editingLabel = null;
        $this->editingValue = null;
        $this->heroBannerUpload = null;
        $this->resetErrorBag();
    }


    public function render()
    {
        return view('livewire.admin.settings.index');
    }
}