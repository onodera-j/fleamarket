@extends('layouts.app')

@section("css")
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection

@section("content")

<div class="content">
    <div class="tab-content">
        <div class="tab-item">
            <a href="/">
            @if($tab === "index")
                <button type="submit" class="item-button"><span class="color-red">おすすめ</span></button>
            @else
                <button type="submit" class="item-button">おすすめ</button>
            @endif
            </a>
        </div>
        <form method="get" action="/">
        <div class="tab-item">
            <input type="hidden" name="tab" value="mylist">
            @if(isset($keyword))
            <input type="hidden" name="keyword" value="{{$keyword}}">
            @endif

            @if($tab === "mylist")
                <button type="submit" class="item-button"><span class="color-red">マイリスト</span></button>
            @else
                <button type="submit" class="item-button">マイリスト</button>
            @endif
        </div>
        </form>

    </div>

    <div class="item-list">

        @switch($tab)
            @case("index")
            @foreach($Items as $Item)
            <div class="item">
                <a class="item-link" href="/item/{{$Item["id"]}}">
                <div class="item-image">
                    <img class="img" src="{{ Storage::url($Item->item_image) }}" alt="商品画像" width="150" height="150">
                    @if($Item->soldout === 1)
                        <div class="soldout">
                            Sold
                        </div>
                    @endif
                </div>
                <div class="item-name">
                    <label class="label-name">{{$Item["item_name"]}}</label>
                </div>
                </a>
            </div>
            @endforeach
            @break

            @case("mylist")
            @foreach($mylistItems as $mylistItem)
            <div class="item">
                <a class="item-link" href="/item/{{$mylistItem->item_id}}">
                <div class="item-image">
                    <img class="img" src="{{ Storage::url($mylistItem->item->item_image) }}" alt="商品画像" width="150" height="150">
                    @if($mylistItem->item->soldout === 1)
                    <div class="soldout">
                        Sold
                    </div>
                    @endif
                </div>


                <div class="item-name">
                    <label class="label-name">{{$mylistItem->item->item_name}}</label>
                </div>
                </a>
            </div>
            @endforeach
            @break
            @endswitch

        
    </div>
</div>

@endsection
