<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\MessageRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Item;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Rating;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    public function index(Chat $chat)
    {
        $user = Auth::user();
        $myId = $user->id;


        if ($chat->seller_id !== $myId && $chat->purchaser_id !== $myId) {
            abort(403); // アクセス禁止
        }

        $otherUser = $chat->seller_id === $myId ?
                        $chat->purchaser : $chat->seller;

        $itemData = Item::where('id', $chat->item_id)->first();

        $chatDatas = Message::with('sender')
                        ->where('chat_id', $chat->id)
                        ->orderBy('created_at', 'asc')->get();

        $tradingDatas = Chat::where(function ($query) use ($myId) {
                            $query->where('seller_id', $myId)
                            ->where('seller_status', 0);
                            })
                            ->orWhere(function ($query) use ($myId) {
                            $query->where('purchaser_id', $myId)
                            ->where('purchaser_status', 0);
                            })
                            ->where('id', '!=', $chat->id)
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return view('mypage.chat', compact('user', 'otherUser', 'chat', 'itemData', 'chatDatas', 'tradingDatas'));
    }

    public function message(MessageRequest $request)
    {
        DB::beginTransaction();
        try {
            $messageData = $request->only('chat_id','sender_id','receiver_id', 'content');


            if ($request->hasFile('item_image')) {
                $file = $request->file('item_image');
                $file_name = Str::random(10) . '.' . $file->getClientOriginalExtension(); // ユニークなファイル名を生成
                $path = $file->storeAs('chats', $file_name, 'public'); // storage/app/public/chatsに保存
                $messageData["image_path"] = 'chats/' . $file_name; // データベースに保存するパス
            }

            Message::create($messageData);

            $chat = Chat::find($messageData['chat_id']);
            $chat->touch();

            DB::commit();
            return redirect()->back();

        }catch (\Exception $e) {
            DB::rollback();
            Log::error("Error: " . $e->getMessage());
            return back()->withErrors(["error", "メッセージの送信に失敗しました"]);
        }

    }

    public function edit(MessageRequest $request)
    {
        $user = Auth::user();

        $message = Message::find($request->input('message_id'));
        if ($message->sender_id !== $user->id) {
            abort(403, 'このメッセージは編集できません。');
        }
        $message->content = $request->input('content');
        $message->save();

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $message = Message::find($request->input('message_id'));
        if ($message->sender_id !== $user->id) {
            abort(403, 'このメッセージは編集できません。');
        }
        $message->delete();

        return redirect()->back();
    }
}
