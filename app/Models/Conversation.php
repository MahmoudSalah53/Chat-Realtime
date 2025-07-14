<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'sender_id'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getReceiver()
    {
        if ($this->sender_id === auth()->id()) {
            return User::firstWhere('id', $this->receiver_id);
        } else {
            return User::firstWhere('id', $this->sender_id);
        }
    }

    public function scopeWhereNotDeleted($query)
    {
        $userId = auth()->id();

        return $query->where(function ($query) use ($userId) {
            $query->whereHas('messages', function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->whereNull('sender_deleted_at');
                })->orWhere(function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->whereNull('receiver_deleted_at');
                });
            });
        });
    }

    public function isReadMessage()
    {
        $user = auth()->user();
        $userId = $user->id;

        // الحصول على آخر رسالة لم يحذفها المستخدم
        $lastMessage = $this->messages()
            ->where(function ($query) use ($userId) {
                $query->where(function ($subQuery) use ($userId) {
                    $subQuery->where('sender_id', $userId)
                        ->whereNull('sender_deleted_at');
                })->orWhere(function ($subQuery) use ($userId) {
                    $subQuery->where('receiver_id', $userId)
                        ->whereNull('receiver_deleted_at');
                });
            })
            ->latest()
            ->first();

        if ($lastMessage) {
            return $lastMessage->read_at !== null && $lastMessage->sender_id == $user->id;
        }

        return false;
    }

    public function unreadMessagesCount(): int
    {
        $userId = auth()->user()->id;

        return Message::where('conversation_id', $this->id)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->whereNull('receiver_deleted_at') // لا نحسب الرسائل المحذوفة
            ->count();
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

}