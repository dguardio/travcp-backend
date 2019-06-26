<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $guarded = [];

    public function messages(){
        return $this->hasMany(Message::class, 'conversation_id')->with('sender');
    }

    public function userone(){
        return $this->belongsTo(User::class, 'user_one');
    }

    public function usertwo(){
        return $this->belongsTo(User::class, 'user_two');
    }

    public function existsBetweenTwoUsers($userOne, $userTwo){
        $conversation = $this->whereIn('user_one', [$userOne, $userTwo])->whereIn('user_two', [$userOne, $userTwo]);
        if ($conversation->exists()){
            return $conversation->first()->id;
        }
    }

    public function isUserParticipant($userId){
        return $this->where('user_one',$userId)->orWhere('user_two', $userId)->exists();
    }

    public function conversationThreads($userId, $offset=1, $limit=20){
        $conversations = $this->isUserParticipating($userId);
        return $conversations;
    }

    public function scopeIsUserParticipating($query, $userId){
        return $query->where('user_one', $userId)
            ->orWhere('user_two', $userId)
            ->latest('updated_at')
            ->with('userone:id,name,email')
            ->with('usertwo:id,name,email')
            ->withCount(['messages as unread_messages_count' => function($q) use($userId){
                $q->where('user_id', '!=', $userId);
                $q->whereNull('read_at');
        }]);
    }

    public function scopeExistingBetween($query, $userOne, $userTwo){
        return $query->whereIn('user_one', [$userOne, $userTwo])->whereIn('user_two', [$userOne, $userTwo]);
    }

    public function getUnreadMessagesByConversation($userId){
        // $conversations = $this->isUserParticipating($userId)->get();
        $receivedMessages = [];
        
        $receivedMessages = $this->messages()->where('user_id', '<>', $userId)->oldest()->whereNull('read_at')->get();
        
        return $receivedMessages;
    }

    public function markUnreadMessagesAsRead($userId){
        $this->messages()->where('user_id', '<>', $userId)->whereNull('read_at')->update(['read_at' => now()]);
    }
    public function countUnreadMessages($userId){
        $conversations = $this->isUserParticipating($userId)->get();
        $receivedMessages = 0;
        foreach ($conversations as $conversation){
            $receivedMessages += $conversation->messages()->where('user_id', '<>', $userId)->whereNull('read_at')->count();
        }
        return $receivedMessages;
    }
}
