@extends('layouts.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
    <div class="content">
        <nav class="nav-left">
            <div class="left-title">その他の取引</div>

            <div class="trading-list">
                @foreach($tradingDatas as $tradingData)
                <a class="trading-link" href="/mypage/chat/{{$tradingData->id}}">
                    {{$tradingData->item->item_name}}
                </a>
                @endforeach
                <div class="trading-link">もきち</div>
            </div>
        </nav>
        <div class="nav-content">

            {{-- ヘッダー --}}
            <div class="content-header">
                <div class="header-title">
                    @if ($otherUser->profile_image == 'default.png')
                        <img class="rounded-circle" id="profile-image-preview"
                            src="{{ Storage::url('profile/default.png') }}" alt="プロフィール画像" width="50" height="50">
                    @else
                        <img class="rounded-circle" id="profile-image-preview"
                            src="{{ Storage::url($otherUser->profile_image) }}" alt="プロフィール画像" width="50"
                            height="50">
                    @endif
                    {{ $otherUser->name }}さんとの取引画面
                </div>
                <div class="trading-close">
                    <button class="button-rating" onclick='location.href="#rating"'>
                        取引を完了する
                    </button>
                </div>
            </div>

            {{-- 商品表示 --}}
            <div class="content-item">
                <div class="item-image">
                    <img class="img" src="{{ Storage::url($itemData->item_image) }}" alt="{{ $itemData['item_name'] }}"
                        width="150" height="150">
                </div>
                <div class="item-detail">
                    <div class="item-name">
                        {{ $itemData->item_name }}
                    </div>
                    <div class="item-price">
                        ￥{{ number_format($itemData['price']) }}
                    </div>
                </div>
            </div>

            {{-- チャット表示 --}}
            <div class="content-chat" id="chat-container">
                @foreach ($chatDatas as $chatData)
                    <div class="chat-group {{ $chatData->sender_id === $user->id ? 'my' : 'other' }}">
                        <div class="chat-sender">
                            @if ($chatData->sender->profile_image == 'default.png')
                                <img class="rounded-circle" id="profile-image-preview"
                                    src="{{ Storage::url('profile/default.png') }}" alt="プロフィール画像" width="40" height="40">
                            @else
                                <img class="rounded-circle" id="profile-image-preview"
                                    src="{{ Storage::url($chatData->sender->profile_image) }}" alt="プロフィール画像" width="40"
                                    height="40">
                            @endif
                            <span class="font-bold">{{ $chatData->sender->name }}</span>
                        </div>


                        <div class="comment-content">
                            <div class="content-text" >
                                {{ $chatData['content'] }}
                            </div>
                            @if ($chatData['image_path'])
                            <div class="content-image">
                                <img id="image-preview" src="{{ Storage::url($chatData->image_path) }}" alt="画像"width="100" height="100">
                            </div>
                            @endif
                        </div>
                        @if($chatData->sender_id === $user->id)
                            <div class="content-edit">
                                <div class="message-edit">
                                    <button class="edit-button" onclick='location.href="#edit{{$chatData->id}}"'>編集</button>
                                </div>
                                <form method="post" action="/chat/message/delete">
                                    @csrf
                                    @method('delete')
                                    <div class="message-delete">
                                        <input type="hidden" name="message_id" value="{{$chatData->id}}">
                                        <button class="edit-button" onclick="return confirm('本当に削除しますか？')">削除</button>
                                    </div>
                                </form>
                            </div>
                            {{-- 編集用モーダルウィンドウ --}}
                            <div class="modal" id="edit{{$chatData->id}}">
                                <a href="#!" class="modal-overlay"></a>
                                <div class="modal__inner">
                                    <form method="post" action="/chat/message/edit">
                                        @csrf
                                        @method('patch')
                                        <div class="modal__content">
                                            <div class="edit-message">
                                                メッセージの編集
                                            </div>
                                            <div class="edit">
                                            <textarea class="edit-form" name="content" rows="3">{{$chatData->content}}</textarea>
                                            <input type="hidden" name="message_id" value="{{$chatData->id}}">
                                            </div>
                                            <div class="update-button">
                                                <button class="update">更新</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                @endforeach
            </div>
        </div>
    </div>
    @if($errors->has('content') || $errors->has('item_image'))
    <div class="message-error">
        @error('content')
            <div class="error-message">{{ $message }}</div>
        @enderror
        @error('item_image')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>
    @endif
    {{-- メッセージ入力欄 --}}
    <form method="post" action="/chat/message" enctype="multipart/form-data">
        @csrf
        <div class="content-message">
            <div class="message-form">
                <input class="form-text" name="content" type="text" value="{{ old('content') }}"
                    placeholder="取引メッセージを入力してください">
            </div>
            <div class="button-img">
                <div class="add-image">
                    <span class="select-button" id="select-image-button">画像を追加</span>
                    <input id="item-image" name="item_image" type="file"
                        class="form-control @error('profile-image') is-invalid @enderror" style="display:none;"
                        value="" accept="image/png, image/jpeg">
                </div>
                <div class="message-send">
                    <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                    <input type="hidden" name="sender_id" value="{{ $user->id }}">
                    <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                    <button class="send"><img class="logo" width="32px" height="32px"
                            src="{{ asset('storage/send.jpg') }}"></button>
                </div>
            </div>

        </div>
    </form>

    {{-- モーダルウィンドウ --}}
    <div class="modal" id="rating">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal__inner">
            <div class="modal__content">
                <div class="modal-message">
                    取引が完了しました。
                </div>
                <form class="modal__detail" action="/delete" method="post">
                    @csrf
                    <div class="modal-rating">
                        <div>
                            <span class="rating-message">今回の取引相手はどうでしたか？
                            </span>
                        </div>

                        <div class="stars">

                            <input class="star star-5" id="star-5" type="radio" name="star" />
                            <label class="star star-5" for="star-5"></label>
                            <input class="star star-4" id="star-4" type="radio" name="star" />
                            <label class="star star-4" for="star-4"></label>
                            <input class="star star-3" id="star-3" type="radio" name="star" />
                            <label class="star star-3" for="star-3"></label>
                            <input class="star star-2" id="star-2" type="radio" name="star" />
                            <label class="star star-2" for="star-2"></label>
                            <input class="star star-1" id="star-1" type="radio" name="star" />
                            <label class="star star-1" for="star-1"></label>

                        </div>
                    </div>
                    <div class="submit-button">
                        <button class="modal-submit" type="submit">送信する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectButton = document.getElementById('select-image-button');
            const fileInput = document.getElementById('item-image');

            // ボタンクリックでファイル選択を開く
            selectButton.addEventListener('click', function() {
                fileInput.click();
            });

            // ファイルが選択されたら、ボタンに "selected" クラスを追加
            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    selectButton.classList.add('selected');
                } else {
                    selectButton.classList.remove('selected');
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const chatContainer = document.getElementById("chat-container");
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });

    </script>

    </div>
@endsection
