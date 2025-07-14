<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;
use Livewire\Attributes\Url;

class ChatList extends Component
{
    public $selectedConversation;
    public $query;
    public $search = '';
    protected $listeners = ['refresh' => 'refresh', 'messageReceived' => 'refreshChatList'];

    public function deleteByUser($id)
    {
        $userId = auth()->id();
        $conversation = Conversation::find(decrypt($id));

        if (!$conversation) {
            return redirect(route('chat.index'));
        }

        $deleteTimestamp = now();

        $conversation->messages()->each(function ($message) use ($userId, $deleteTimestamp) {
            if ($message->sender_id === $userId) {
                $message->update(['sender_deleted_at' => $deleteTimestamp]);
            } elseif ($message->receiver_id === $userId) {
                $message->update(['receiver_deleted_at' => $deleteTimestamp]);
            }
        });

        // تحديث عدد الرسائل غير المقروءة في الـ navigation
        $this->dispatch('updateUnreadCount');

        return redirect(route('chat.index'));
    }

    public function refresh()
    {
        // تحديث عدد الرسائل غير المقروءة في الـ navigation
        $this->dispatch('updateUnreadCount');
    }

    public function refreshChatList($data = null)
    {
        // تحديث عدد الرسائل غير المقروءة في الـ navigation
        $this->dispatch('updateUnreadCount');
    }

    public function render()
    {
        $userId = auth()->id();

        $conversations = Conversation::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        })
            ->when($this->search, function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('sender_id', $userId)
                        ->whereHas('receiver', function ($receiverQuery) {
                        $receiverQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                })->orWhere(function ($q) use ($userId) {
                    $q->where('receiver_id', $userId)
                        ->whereHas('sender', function ($senderQuery) {
                        $senderQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->whereNotDeleted()
            ->latest('updated_at')
            ->get();

        return view('livewire.chat.chat-list', compact('conversations'));
    }
}