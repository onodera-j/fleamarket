@extends('layouts.app')

@section("css")
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endsection

@section("content")

<div class="content">
    <div class="content-title">
        <h2>住所の変更</h2>
    </div>


    <div class="profile-form">
        <form method="post" action="/updateaddress">
            @method("PATCH")
            @csrf

            <input type="hidden" name="item_id" value="{{$item["id"]}}">
            <div class="form-group">
                <label class="form-label" for="post_code">郵便番号</label>
                <input class="form-text" id="post_code" name="post_code" type="text" value="@if(isset($address["post_code"])){{$address["post_code"]}}@endif">
                @error('post_code')
                {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="address">住所</label>
                <input class="form-text" id="address" name="address" type="text" value="@if(isset($address["address"])){{$address["address"]}}@endif">
                @error('address')
                {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="building">建物名</label>
                <input class="form-text" id="building" name="building" type="text" value="@if(isset($address["building"])){{$address["building"]}}@endif">
                @error('building')
                {{ $message }}
                @enderror
            </div>

            <div class="form-btn">
                <button class="btn-submit">更新する</button>
            </div>
        </form>

        <script>
            function previewImage(obj)
            {
                var fileReader = new FileReader();
                fileReader.onload = (function() {
                document.getElementById('profile-image-preview').src = fileReader.result;
                });
                if (obj.files && obj.files[0]) {
                fileReader.readAsDataURL(obj.files[0]);
                }
            }
        </script>
    </div>
</div>
@endsection
