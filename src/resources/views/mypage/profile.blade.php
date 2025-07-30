@extends('layouts.app')

@section("css")
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endsection

@section("content")

<div class="update-alert">
     @if(session('message'))
<div class="update_alert--success">
  {{ session('message') }}
</div>
     @endif
     @if ($errors->any())
  <div class="todo__alert--danger">
    <ul>
      @foreach ($errors->all() as $error)
       <li>{{ $error }}</li>
       @endforeach
       </ul>
     </div>
   @endif
</div>

<div class="content">
    <div class="content-title">
        <h2>プロフィール設定</h2>
    </div>


    <div class="profile-form">
        <form method="post" action="/address" enctype="multipart/form-data">
            @csrf

            <div class="form-img">
                <label class="label-img" for="profile-image">
                    <div class="icon-img">
                        @if($user->profile_image == "default.png")
                        <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url('profile/default.png') }}" alt="プロフィール画像" width="100" height="100">

                        <input id="profile-image" name="profile_image" type="file" class="form-control @error('profile-image') is-invalid @enderror" style="display:none;" value="" accept="image/png, image/jpeg" onchange="previewImage(this)">
                        @else
                        <img class="rounded-circle" id="profile-image-preview" src="{{ Storage::url($user->profile_image) }}" alt="プロフィール画像" width="100" height="100">
                        <input id="profile-image" name="profile_image" type="file" class="form-control @error('profile-image') is-invalid @enderror" style="display:none;" value="" accept="image/png, image/jpeg" onchange="previewImage(this)">
                        @endif
                    </div>
                    <div class="button-img">
                        <span class="select-button">画像を選択する</span>
                    </div>
                    </label>
            @error('profile_image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>



            <div class="form-group">
                <label class="form-label" for="name">ユーザー名</label>
                <input class="form-text" id="name" name="name" type="text" value="{{old("name", $user["name"])}}">
            </div>
            <div class="form-group">
                <label class="form-label" for="post_code">郵便番号</label>
                <input class="form-text" id="post_code" name="post_code" type="text" value="@if(isset($address["post_code"])){{old("post_code", $address["post_code"])}}@endif">
                @error('post_code')
                {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="address">住所</label>
                <input class="form-text" id="address" name="address" type="text" value="@if(isset($address["address"])){{old("address", $address["address"])}}@endif">
                @error('address')
                {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="building">建物名</label>
                <input class="form-text" id="building" name="building" type="text" value="@if(isset($address["building"])){{old("address", $address["building"])}}@endif">
                @error('building')
                {{ $message }}
                @enderror
            </div>
            <div class="form-hidden">
                <input name="user_id" type="hidden" value="{{$user["id"]}}">
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
