@extends('layouts.app')

@section("css")
<link rel="stylesheet" href="{{ asset('css/item.css') }}" />
@endsection

@section("content")
<div class="content">
    <div class="flex-content">
        <div class="flex-item">
            <div class="item-image">
                <img class="img" src="{{ Storage::url($item->item_image) }}" alt="{{$item["item_name"]}}" width="300" height="300">
                @if($item->soldout === 1)
                    <div class="soldout">
                        Sold
                    </div>
                @endif
            </div>
        </div>
        <div class="flex-item">
            <div class="item-name">
                {{$item["item_name"]}}<br>
            </div>
            <div class="brand-name">
                {{$item["brand_name"]}}
            </div>
            <div class="price">
                ￥<span class="font-price">{{ number_format($item["price"]) }}</span> (税込)

            </div>
            <div class="icon">
                <div class="icon-star">
                    @if($favorite != null)
                    <form method="post" action="/favoritedelete">
                        @method("DELETE")
                        @csrf
                        <input type="hidden" name="item_id" value="{{$item["id"]}}">
                        <button class="favorite"><img class="logo" width="32px" height="32px" src="{{ asset("storage/star2.svg") }}"></button>
                        <div class="count">{{$counts->count()}}</div>
                    </form>
                    @else
                    <form method="post" action="/favorite">
                        @csrf
                        <input type="hidden" name="item_id" value="{{$item["id"]}}">
                        <button class="favorite"><img class="logo" width="32px" height="32px" src="{{ asset("storage/star1.svg") }}"></button>
                        <div class="count">{{$counts->count()}}</div>
                    @endif
                </div>
                <div class="icon-comment">
                    <img class="logo" width="32px" height="32px" src="{{ asset("storage/comment.svg") }}"></button>
                    <div class="count">{{$comments->count()}}</div>
                </div>
            </div>

            <div class="purchase">
                @switch($item["soldout"])
                @case(0)
                    <a href="/purchase/{{$item["id"]}}">
                    <button class="form-button">購入手続きへ</button>
                    </a>
                @break
                @case(1)
                    <button class="soldout-button">Sold Out</button>
                @break
                @endswitch
            </div>

            <div>
                <h3>商品説明</h3>
                {{$item["item_detail"]}}
            </div>
            <div class="item-information">
                <h3>商品の情報</h3>
                <div class="information-group">
                    <div class="information-title">
                    カテゴリー
                    </div>
                    <div class="information-content">
                        @foreach($categories as $category)
                        <div class="category">
                            <span class="span-category">{{$category->category->content}}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="information-group">
                    <div class="information-title">
                        商品の状態
                    </div>
                    <div class="information-content">
                        <div class="condition">
                        @switch($item["condition"])
                        @case(1)
                            良好
                            @break
                        @case(2)
                            目立った傷や汚れなし
                            @break
                        @case(3)
                            やや傷や汚れあり
                            @break
                        @case(4)
                            状態が悪い
                            @break
                        @endswitch
                        </div>
                    </div>

                </div>
            </div>

            <div class="comment">
                <h3><span class="fontcolor-gray">コメント({{$comments->count()}})</span></h3>
                <div>
                    @foreach($comments as $comment)
                    <div class="comment-group">
                        <div class="comment-user">
                            @if($comment->user->profile_image == "default.png")
                                <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url('profile/default.png') }}" alt="プロフィール画像" width="40" height="40">
                            @else
                                <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url($comment->user->profile_image) }}" alt="プロフィール画像" width="40" height="40">
                            @endif
                            <span class="font-bold">{{$comment->user->name}}</span>
                        </div>
                        <div class="comment-content">
                            {{$comment["comment"]}}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <form method="post" action="/comment">
                @csrf
                <div class="form-coment">
                    <div class="comment-title">商品へのコメント</div>
                    <div>
                    <textarea class="textarea" name="comment" rows="10">{{old("comment")}}</textarea>
                    @error('comment')
                    <div>
                    {{ $message }}
                    </div>
                    @enderror
                    </div>

                    <input name="item_id" type="hidden" value="{{$item["id"]}}">
                    <button class="form-button">コメントを送信する</button>
                </div>
            </form>








        </div>

    </div>
</div>



@endsection
