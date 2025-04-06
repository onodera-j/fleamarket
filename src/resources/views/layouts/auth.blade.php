<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fleamarket_App</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
  @yield('css')

</head>
<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-logo">
                <a href="/"><img class="logo" src="{{asset("storage/logo.svg")}}"></a>
            </div>

        </div>

    </header>
    <main>
        
        @yield("content")

    </main>
</body>
</html>
