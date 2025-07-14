<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Conversation;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;
    public $search = '';

    protected $paginationTheme = 'tailwind';

    protected $updatesQueryString = ['page'];

    public function message($userId)
    {

        $authenticatedUserId = auth()->id();

        # Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
            $query->where('sender_id', $authenticatedUserId)
                ->where('receiver_id', $userId);
        })
            ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $authenticatedUserId);
            })->first();

        if ($existingConversation) {
            # Conversation already exists, redirect to existing conversation
            return redirect()->route('chat', $existingConversation->id);
        }

        # Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $authenticatedUserId,
            'receiver_id' => $userId,
        ]);

        return redirect()->route('chat', $createdConversation->id);

    }


    public function render()
    { 
        $users = User::where('id', '!=', auth()->id())
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(8);
            
        return view('livewire.users', compact('users'))->layout('layouts.app');
    }
}
