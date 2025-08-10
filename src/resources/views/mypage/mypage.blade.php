@extends('layouts.app')

@section("css")
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
@endsection

@section("content")

<div class="content">
    <div class="profile-view">
        <div class="icon-img">
        @if($user->profile_image == "default.png")
            <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url('profile/default.png') }}" alt="プロフィール画像" width="100" height="100">
        @else
            <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url($user->profile_image) }}" alt="プロフィール画像" width="100" height="100">
        @endif
        </div>
        @if($userRate === 0)
            <div class="user-name">
                {{$user["name"]}}
            </div>
        @else
            <div class="user-status">
                <div class="user-name">
                    {{$user["name"]}}
                </div>
                <div class="user-rate">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $userRate)
                            <span class="rate-yellow">★</span>
                        @else
                            <span class="rate-gray">★</span>
                        @endif
                    @endfor
                </div>
            </div>
        @endif
        <div class="button-img">
            <a class="profile-link" href="profile">
            <span class="select-button">プロフィールを編集</span>
            </a>
        </div>
        </form>
    </div>

    <div class="tab-content">
        <form method="get" action="/mypage/mypage">
        <div class="tab-item">
            <input type="hidden" name="tab" value="sell">
            @if($tab === "sell")
                <button type="submit" class="item-button"><span class="color-red">出品した商品</span></button>
            @else
                <button type="submit" class="item-button">出品した商品</button>
            @endif
        </div>
        </form>
        <form method="get" action="/mypage/mypage">
        <div class="tab-item">
            <input type="hidden" name="tab" value="buy">
            @if($tab === "buy")
                <button type="submit" class="item-button"><span class="color-red">購入した商品</span></button>
            @else
                <button type="submit" class="item-button">購入した商品</button>
            @endif
        </div>
        </form>
        <form method="get" action="/mypage/mypage">
        <div class="tab-item">
            <input type="hidden" name="tab" value="transaction">
            @if($tab === "transaction")
                <button type="submit" class="item-button"><span class="color-red">取引中の商品</span>
                @if($newMessage != 0)
                    <span class=message-icon>{{$newMessage}}</span>
                @endif
                </button>
            @else
                <button type="submit" class="item-button">取引中の商品
                @if($newMessage != 0)
                    <span class=message-icon>{{$newMessage}}</span>
                @endif
                </button>
            @endif
        </div>
        </form>
    </div>

    <div class="item-list">
        @switch($tab)
            @case("sell")
            @foreach($sellItems as $sellItem)
            <div class="item">
                <a class="item-link" href="/item/{{$sellItem["id"]}}">
                <div class="item-image">
                    <img class="img" src="{{ Storage::url($sellItem->item_image) }}" alt="商品画像" width="150" height="150">
                    @if($sellItem->soldout === 1)
                        <div class="soldout">
                            Sold
                        </div>
                    @endif
                </div>
                <div class="item-name">
                    <label class="label-name">{{$sellItem["item_name"]}}</label>
                </div>
                </a>
            </div>
            @endforeach
            @break

            @case("buy")
            @foreach($buyItems as $buyItem)
            <div class="item">
                <a class="item-link" href="/item/{{$buyItem->item_id}}">
                <div class="item-image">
                    <img class="img" src="{{ Storage::url($buyItem->item->item_image) }}" alt="商品画像" width="150" height="150">
                    @if($buyItem->item->soldout === 1)
                        <div class="soldout">
                            Sold
                        </div>
                    @endif
                </div>

                <div class="item-name">
                    <label class="label-name">{{$buyItem->item->item_name}}</label>
                </div>
                </a>
            </div>
            @endforeach
            @break

            @case("transaction")
            @foreach($transactionItems as $transactionItem)
            <div class="item">
                <a class="item-link" href="/mypage/chat/{{$transactionItem->id}}">
                <div class="item-image">
                    <img class="img" src="{{ Storage::url($transactionItem->item->item_image) }}" alt="商品画像" width="150" height="150">
                    @if($transactionItem->unread_count != 0)
                    <div class="newmessage">
                        <span class="new-icon">
                            {{$transactionItem->unread_count}}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="item-name">
                    <label class="label-name">{{$transactionItem->item->item_name}}</label>
                </div>
                </a>
            </div>
            @endforeach
            @break
            @endswitch

@endsection
