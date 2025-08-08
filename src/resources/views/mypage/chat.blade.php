@extends('layouts.auth')

@section("css")
<link rel="stylesheet" href="{{ asset('css/chat.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section("content")

<div class="content">
    <nav class="nav-left">
        <h2 class="left-title">その他の取引</h2>
        <ul>
            <li>item</li>
            <li>item</li>
            <li>item</li>
        </ul>
    </nav>
    <div class="nav-content">
        <div class="content-header">
            <div class="header-title">
                {{-- @if($comment->user->profile_image == "default.png") --}}
                                <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url('profile/default.png') }}" alt="プロフィール画像" width="50" height="50">
                            {{-- @else
                                <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url($comment->user->profile_image) }}" alt="プロフィール画像" width="40" height="40">
                            @endif --}}
                            {{-- <span class="font-bold">{{$comment->user->name}}</span> --}}
                ユーザー名さんとの取引画面
            </div>
            <div class="trading-close">
                <button class="button-rating" onclick='location.href="#rating"'>
                    取引を完了する
                </button>
            </div>
        </div>
        <div class="content-item">
            <div class="item-image">
                {{-- <img class="img" src="{{ Storage::url($item->item_image) }}" alt="{{$item["item_name"]}}" width="300" height="300"> --}}
                <img class="img" src="{{ Storage::url('profile/default.png') }}" alt="" width="150" height="150">
            </div>
            <div class="item-detail">
                <div class="item-name">
                    商品名
                </div>
                <div class="item-price">
                    商品価格
                </div>
            </div>
        </div>
        <div class="content-chat">
            <div class="comment-group">
                        <div class="comment-user">
                            {{-- @if($comment->user->profile_image == "default.png") --}}
                                <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url('profile/default.png') }}" alt="プロフィール画像" width="40" height="40">
                            {{-- @else
                                <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url($comment->user->profile_image) }}" alt="プロフィール画像" width="40" height="40">
                            @endif --}}
                            {{-- <span class="font-bold">{{$comment->user->name}}</span> --}}
                            <span class="font-bold">ユーザー名</span>
                        </div>
                        <div class="comment-content">
                            {{-- {{$comment["comment"]}} --}}チャット内容
                        </div>
                    </div>
        </div>
    </div>
    <div class="content-message">
        <div class="message-form">
                <input class="form-text" id="brand" name="brand_name" type="text" value="{{old("brand_name")}}" placeholder="取引メッセージを入力してください">
        </div>
        <div class="button-img">
            <div>
                <span class="select-button" id="select-image-button">画像を追加</span>
            </div>
            <div class="message-send">
                <input type="hidden" name="item_id" value="">
                <button class="send"><img class="logo" width="32px" height="32px" src="{{ asset("storage/send.jpg") }}"></button>
            </div>
        </div>
    </div>

    {{--モーダルウィンドウ--}}
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

                                    <input class="star star-5" id="star-5" type="radio" name="star"/>
                                    <label class="star star-5" for="star-5"></label>
                                    <input class="star star-4" id="star-4" type="radio" name="star"/>
                                    <label class="star star-4" for="star-4"></label>
                                    <input class="star star-3" id="star-3" type="radio" name="star"/>
                                    <label class="star star-3" for="star-3"></label>
                                    <input class="star star-2" id="star-2" type="radio" name="star"/>
                                    <label class="star star-2" for="star-2"></label>
                                    <input class="star star-1" id="star-1" type="radio" name="star"/>
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

</div>


@endsection
