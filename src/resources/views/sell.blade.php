@extends('layouts.app')

@section("css")
<link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
@endsection

@section("content")

<div class="content">
    <div class="content-title">
        <h2>商品の出品</h2>
    </div>


    <div class="sell-form">
        <form method="post" action="/sell_register" enctype="multipart/form-data">
            @csrf
            <div class="item-img">
                <div class="form-img">
                    <label class="label-img" for="item-image">
                        <div class="icon-img">
                            <img class="img-circle" id="item-image-preview" alt="商品画像" width="100" height="100" style="display: none;">
                            <input id="item-image" name="item_image" type="file" class="form-control @error('profile-image') is-invalid @enderror" style="display:none;" value="" accept="image/png, image/jpeg" onchange="previewImage(this)">
                        </div>
                        <div class="button-img">
                            <span class="select-button" id="select-image-button">画像を選択する</span>
                        </div>
                    </label>
                </div>
                @error('item_image')
                    {{ $message }}
                @enderror
            </div>

            <div class="item-detail">
                <div class="detail-title">
                    <h3 class="title-header">商品の詳細</h3>
                </div>
                <div class="form-group">
                    <label class="form-label">カテゴリー</label>
                    @error('category_id')
                    {{ $message }} <br>
                    @enderror
                    @foreach ($categories as $category)
                    <label class=label-category><input class=input-category type="checkbox" value="{{$category->id}}" name="category_id[]" {{ in_array($category->id, old('category_id', [])) ? "checked" : "" }}><span class=span-category>{{$category->content}}</span></label>
                    @endforeach
                </div>
                <div class="form-group">
                    <label class="form-label" for="condition">商品の状態</label>
                    <select class="form-select" id="condition" name="condition">
                        <option value="" {{ old("condition") == "" ? "selected" : "" }} disabled>選択してください</option>
                        <option value=1  {{ old("condition") == "1" ? "selected" : "" }}>良好</option>
                        <option value=2  {{ old("condition") == "2" ? "selected" : "" }}>目立った傷や汚れなし</option>
                        <option value=3  {{ old("condition") == "3" ? "selected" : "" }}>やや傷や汚れあり</option>
                        <option value=4  {{ old("condition") == "4" ? "selected" : "" }}>状態が悪い</option>
                    </select>
                    @error('condition')
                    {{ $message }}
                    @enderror
                </div>
                <div class="detail-title">
                    <h3 class="title-header">商品名と説明</h3>
                </div>
                <div class="form-group">
                    <label class="form-label" for="name">商品名</label>
                    <input class="form-text" id="name" name="item_name" type="text" value="{{old("item_name")}}">
                    @error('item_name')
                    {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="brand">ブランド名</label>
                    <input class="form-text" id="brand" name="brand_name" type="text" value="{{old("brand_name")}}">
                    @error('brand_name')
                    {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="detail">商品の説明</label>
                    <textarea class="form-textarea" id="detail" name="item_detail" rows="4" cols="30">{{old("item_detail")}}</textarea>
                    @error('item_detail')
                    {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="price">販売価格</label>
                    <input class="form-text" id="price" name="price" type="text" value="{{old("price")}}">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>

                <div class="form-btn">
                    <button class="btn-submit">出品する</button>
                </div>
            </div>
        </form>


    </div>

    <script>
        function previewImage(obj)
        {
            var fileReader = new FileReader();
            var preview = document.getElementById('item-image-preview');
            var button = document.getElementById('select-image-button');
            fileReader.onload = (function() {
                document.getElementById('item-image-preview').src = fileReader.result;
            });
            if (obj.files && obj.files[0]) {
                fileReader.readAsDataURL(obj.files[0]);
                preview.style.display = 'block';
                button.style.display = 'none';
            } else {
                preview.style.display = 'none';
                button.style.display = 'block';
            }
        }
    </script>
</div>
@endsection
