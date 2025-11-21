<?php

namespace App\Livewire\Admin\OurTeam;

use App\Models\TeamMember;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

#[Title('Our Team')]
class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $name;
    public $job_title;
    public $email;
    public $phone;
    public $linkedin;
    public $description;
    public $status; // Changed default to null or handle in mount/reset
    public $sequence = 0;
    public $image; 
    public $existingImage; 

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'linkedin' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'sequence' => 'integer',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function create()
    {
        if (Gate::denies('our-team.create')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to create team members.');
            return;
        }
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        if (Gate::denies('our-team.edit')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to edit team members.');
            return;
        }

        $member = TeamMember::findOrFail($id);
        $this->editingId = $member->id;
        $this->name = $member->name;
        $this->job_title = $member->job_title;
        $this->email = $member->email;
        $this->phone = $member->phone;
        $this->linkedin = $member->linkedin;
        $this->description = $member->description;
        
        // FIX: Cast to integer (1 or 0) to match the dropdown values
        // This ensures "Visible" or "Hidden" is auto-selected
        $this->status = (int) $member->status;
        
        $this->sequence = $member->sequence;
        $this->existingImage = $member->image;
        $this->image = null; 
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'job_title' => $this->job_title,
            'email' => $this->email,
            'phone' => $this->phone,
            'linkedin' => $this->linkedin,
            'description' => $this->description,
            'status' => $this->status,
            'sequence' => $this->sequence,
            'user_id' => auth()->id(),
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('team', 'public');

            if ($this->editingId && $this->existingImage) {
                if (Storage::disk('public')->exists($this->existingImage)) {
                    Storage::disk('public')->delete($this->existingImage);
                }
            }
        }

        if ($this->editingId) {
            if (Gate::denies('our-team.edit')) {
                $this->dispatch('alert', type: 'error', message: 'You do not have permission to edit team members.');
                return;
            }
            TeamMember::findOrFail($this->editingId)->update($data);
            $this->dispatch('alert', type: 'success', message: 'Team member updated successfully.');
        } else {
            if (Gate::denies('our-team.create')) {
                $this->dispatch('alert', type: 'error', message: 'You do not have permission to create team members.');
                return;
            }
            TeamMember::create($data);
            $this->dispatch('alert', type: 'success', message: 'Team member created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        if (Gate::denies('our-team.delete')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to delete team members.');
            return;
        }

        $member = TeamMember::findOrFail($id);

        if ($member->image) {
            if (Storage::disk('public')->exists($member->image)) {
                Storage::disk('public')->delete($member->image);
            }
        }

        $member->delete();
        $this->dispatch('alert', type: 'success', message: 'Team member deleted successfully.');
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->job_title = '';
        $this->email = '';
        $this->phone = '';
        $this->linkedin = '';
        $this->description = '';
        $this->status = 0; // Default to Hidden
        $this->sequence = 0;
        $this->image = null;
        $this->existingImage = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $team = TeamMember::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('job_title', 'like', '%' . $this->search . '%')
            ->orderBy('sequence', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.admin.our-team.index', [
            'team' => $team,
        ]);
    }
}