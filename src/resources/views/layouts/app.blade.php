<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fleamarket_App</title>
  <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />

  @yield('css')

</head>
<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-logo">
                <a href="/"><img class="logo" src="{{ asset("storage/logo.svg") }}"></a>
            </div>
            <div class="header-form">
                <form action="/search" method="get">
                    @if(isset($tab))
                        <input type="hidden" name="tab" value="{{$tab}}">
                    @endif
                    <input class="search-form" type="text" name="keyword" placeholder="何をお探しですか？" value="{{old("keyword")}}">
                </form>
            </div>

            <div class="member-nav">
                <ul class="member-menu">
                    @if (Auth::check())
                    <form class="form" action="/logout" method="post">
                    @csrf
                    <li class="member-content">
                        <button class="button-logout">ログアウト</button>
                    </li>
                    </form>

                    @else
                    <li class="member-content">
                        <a class="link" href="/login">ログイン</a>
                    </li>
                    @endif
                    <li class="member-content"><a class="link" href="/mypage/mypage">マイページ</a></li>
                    <li class="member-content member-listing">
                        <a class="link-sell" href="/sell">出品</a>
                    </li>
                </ul>
            </div>

        </div>

    </header>
    <main>

        @yield("content")


    </main>
</body>
</html>
