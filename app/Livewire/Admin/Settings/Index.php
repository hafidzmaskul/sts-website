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