<?php

namespace App\Http\Controllers;

use App\Message;
use App\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatMessageController extends Controller
{
    public function fetchMessages(Request $request){
        $this->validate($request, 
            [
            'recipient' => 'required']
        );

        $convo = new Conversation;
        if ($convoId = $convo->existsBetweenTwoUsers($request->user()->id, $request->recipient)){
            $messages = Message::where('conversation_id', $convoId)->latest()->limit(30)->with('sender:id,name,email')->get();
            Conversation::find($convoId)->markUnreadMessagesAsRead(auth()->id());
            $messages = array_reverse($messages->toArray());
            return response()->json(['data' => $messages]);
        }
        return response()->json(['data' => []]);
    }

    public function fetchLastMessages(Request $request){
        $this->validate($request, [
            'recipient' => 'required'
        ]);
        $convo = new Conversation;
        if ($convoId = $convo->existsBetweenTwoUsers($request->user()->id, $request->recipient)){
            $conversation = Conversation::find($convoId);
            $messages = $conversation->getUnreadMessagesByConversation(auth()->id());
            $conversation->markUnreadMessagesAsRead(auth()->id());
            return response()->json([
                'data' => $messages
            ]);
        }
    }
    public function store(Request $request){
        $this->validate($request, ['message' => 'required', 'recipient' => 'required|integer']);
        
        $conv = new Conversation;
        if ($convId = $conv->existsBetweenTwoUsers($request->recipient, auth()->id())){
            $message = Message::create(['message' => $request->message, 
            'user_id' => auth()->id(), 
            'type' => $request->type ?? 'text',
            'conversation_id' => $convId]);
            Conversation::find($convId)->update(['updated_at' => now()]);
        }
        else{
            $newConvo = Conversation::create(['user_one' => auth()->id(), 'user_two' => $request->recipient]);
            $message = Message::create(['message' => $request->message, 
            'user_id' => auth()->id(), 
            'type' => $request->type ?? 'text',
            'conversation_id' => $newConvo->id]);
        }
        return response()->json(['data' => $message]);
    }

    public function getConversators($userId=null){
        $userId = auth()->id();
        $conversations = Conversation::isUserParticipating($userId)->get();
        $convos = [];
        
        foreach ($conversations as $conv){
            if ($conv->user_one == $userId){
                
                $user = (object) $conv->usertwo;
                $user->unread_messages_count = $conv->unread_messages_count;
                $convos[] = $user;
                
                // $convos[] = $conv->usertwo;
            }
            else{
                $user = (object) $conv->userone;
                $user->unread_messages_count = $conv->unread_messages_count;
                $convos[] = $user;
                
            }
        }
        return response()->json(['data' => $convos]);
    }

    public function getUnreadMessagesCount(){
        return response()->json(['count' => auth()->user()->countUnreadMessages()]);
    }
}
