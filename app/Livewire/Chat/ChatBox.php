<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use App\Notifications\MessageRead;
use Livewire\Component;
use App\Notifications\MessageSent;
use Livewire\WithFileUploads;

class ChatBox extends Component
{
    use WithFileUploads;

    public $selectedConversation;
    public $body;
    public $loadedMessages;
    public $paginate_var = 10;
    public $image;

    public $unreadCount = 0;


    protected $listeners = [
        'loadMore'
    ];

    public function getListeners()
    {
        $auth_id = auth()->user()->id;

        return [
            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastedNotifications'
        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($event['type'] == MessageSent::class) {
            if ($event['conversation_id'] == $this->selectedConversation->id) {
                
                $messageExists = $this->loadedMessages->contains('id', $event['message_id']);
                
                if (!$messageExists) {
                    $this->dispatch('scroll-bottom');

                    $newMessage = Message::find($event['message_id']);
                    
                    if ($newMessage) {
                        $this->loadedMessages->push($newMessage);

                        if ($newMessage->sender_id !== auth()->id()) {
                            $newMessage->read_at = now();
                            $newMessage->save();

                            $this->dispatch('messageRead');

                            $this->selectedConversation->getReceiver()
                                ->notify(new MessageRead(
                                    $this->selectedConversation->id,
                                    $newMessage->sender_id 
                                ));
                        }
                    }
                }

                $this->dispatch('refresh')->to('chat.chat-list');
            }
        }

        if ($event['type'] == MessageRead::class) {
            if ($event['conversation_id'] == $this->selectedConversation->id) {
                $this->dispatch('refresh');
            }
        }
    }

    public function refreshChatList($data = null)
    {
        $this->render();
    }

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMore()
    {
        $this->dispatch('store-scroll-height');
        $this->paginate_var += 10;
        $this->loadMessages();
        $this->dispatch('update-chat-height');
    }

    public function loadMessages()
    {
        $userId = auth()->id();

        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            ->where(function ($query) use ($userId) {
                $query->where(function ($subQuery) use ($userId) {
                    $subQuery->where('sender_id', $userId)
                        ->whereNull('sender_deleted_at');
                })->orWhere(function ($subQuery) use ($userId) {
                    $subQuery->where('receiver_id', $userId)
                        ->whereNull('receiver_deleted_at');
                });
            })
            ->orderBy('created_at', 'desc')
            ->take($this->paginate_var)
            ->get()
            ->reverse();

        $this->allMessagesCount = $this->loadedMessages->count();

        return $this->loadedMessages;
    }

    public function sendMessage()
    {
        $this->validate([
            'body' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('chat-images', 'public');
        }

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body,
            'image' => $imagePath,
        ]);

        $this->reset(['body', 'image']);
        
        $this->loadedMessages->push($createdMessage);
        
        $this->dispatch('scroll-bottom');
        $this->selectedConversation->touch();
        $this->dispatch('refresh')->to('chat.chat-list');

        $this->selectedConversation->getReceiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id
            ));
    }

   public function render()
{
    $this->unreadCount = Message::where('receiver_id', auth()->id())
        ->whereNull('read_at')
        ->count();

    return view('livewire.chat.chat-box');
}

}