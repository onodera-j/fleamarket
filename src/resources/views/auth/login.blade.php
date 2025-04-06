@extends("layouts.auth")

@section("css")
<link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection

@section("content")

<div class="content">
    <div class="content-title">
        <h2>ログイン</h2>
    </div>

    <div class="register-form">
        <form action="/login" method="post">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">ユーザー名/メールアドレス</label>
                <input class="form-text" id="name" type="email" name="email" value="{{old("email")}}"/>
                @error('email')
                {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password">パスワード</label>
                <input class="form-text" id="password" type="password" name="password">
                @error('password')
                {{ $message }}
                @enderror
            </div>



            <div class="form-btn">
                <button class="btn-submit">ログインする</button>
            </div>
        </form>

        <div class="link">
            <a class="link-register" href="/register">会員登録はこちら</a>
        </div>

    </div>


@endsection
