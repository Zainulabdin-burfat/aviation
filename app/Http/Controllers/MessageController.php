<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Listing;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with(['sender', 'receiver'])->latest()->get();

        return view('content.messages.index', compact('messages'));
    }

    public function chat(User $user)
    {
        $messages = Message::where('from_user_id', auth()->user()->id)->where('to_user_id', $user->id)->get();
        return view('content.messages.chat',['receiver' => $user, 'messages' => $messages]);
    }

    public function send(Request $request, $receiverId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $senderId = auth()->id();

        $message = Message::create([
            'from_user_id' => $senderId,
            'to_user_id' => $receiverId,
            'content' => $request->input('content'),
        ]);

        broadcast(new NewMessage($message));

        return redirect()->back()->with(['status' => true, 'message' => 'Message sent successfully!']);
    }

    public function receiveMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $receiverId = auth()->id();

        $message = Message::create([
            'from_user_id' => $request->input('sender_id'),
            'to_user_id' => $receiverId,
            'content' => $request->input('message'),
        ]);

        broadcast(new NewMessage($message));

        return response()->json(['success' => true, 'message' => 'Message received successfully!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        //
    }
}
