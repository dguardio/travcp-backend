<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    protected $appends = ['time_sent'];
    public function getTimeSentAttribute(){
        return $this->created_at->diffForHumans();
    }

    public function sender(){
        // return $this->belongsTo(User::class, 'sender_id');
        return $this->belongsTo(User::class, 'user_id');
    }

    public function markAsRead(){
        $this->update(['read_at' => now()]);
    }

    // public function receiver(){
    //     return $this->belongsTo(User::class, 'receiver_id');
    // }

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function scopeGetUserConversations($query, $userId){
        return $query->select('messages.*')
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->groupBy('sender_id')
            ->with('sender')
            ->with('receiver')
            ->latest()
            ->get();
    }
}
