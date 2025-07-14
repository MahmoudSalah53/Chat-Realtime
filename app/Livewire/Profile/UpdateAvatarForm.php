<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateAvatarForm extends Component
{
    use WithFileUploads;

    #[Validate('image|max:2048')] // 2MB Max
    public $avatar;

    public function updateAvatar()
    {
        $this->validate();

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $this->avatar->store('avatars', 'public');

        // Update user avatar
        $user->update(['avatar' => $path]);

        $this->dispatch('avatar-updated');
        
        session()->flash('status', 'Avatar updated successfully!');
        
        // Reset the file input
        $this->reset('avatar');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();
        
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
            
            $this->dispatch('avatar-updated');
            session()->flash('status', 'Avatar deleted successfully!');
        }
    }

    public function render()
    {
        return view('livewire.profile.update-avatar-form');
    }
}