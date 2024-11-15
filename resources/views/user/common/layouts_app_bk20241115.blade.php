@php 
    $system_version = "?system_version=" . env('system_version');
@endphp

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ asset('css/all.css') . $system_version}}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('css/swiper-bundle.min.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('css/width.css') . $system_version }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">  {{-- CSRFトークン --}}

    
    @yield('pagehead')
    <title>@yield('title')</title>


</head>

<style>


.c-header {
  align-items: center;
  background-color: #eeeeee; /* カスタマイズしてください */
  box-sizing: border-box;
  display: flex;
  justify-content: space-between;
  padding: 1rem 2rem; /* カスタマイズしてください */
  width: 100%;
}

.c-header__logo {
  color: #000; /* カスタマイズしてください */
  min-width: 80px; /* カスタマイズしてください */
  text-decoration: none;
}

.c-header__list {
  box-sizing: border-box;
  display: flex;
  margin: 0;
  padding: 0;
}

.c-header__list-item {
  list-style: none;
  text-decoration: none;
}

.c-header__list-link {
  color: #000; /* カスタマイズしてください */
  display: block;
  margin-right: 20px; /* カスタマイズしてください */
  text-decoration: none;
  padding: 10px 0px; /* カスタマイズしてください */
}

.c-header__list-link:hover {
  filter: opacity(0.6); /* カスタマイズしてください */
}

.c-hamburger-menu {
  position: relative;
}

@media screen and (max-width: 750px) {
  .c-hamburger-menu__list {
    background-color: #eeeeee; /* カスタマイズしてください */
    align-items: flex-start;
    display: flex;
    flex-direction: column;
    left: 0;
    padding: 2rem; /* カスタマイズしてください */
    position: absolute;
    transform: translateX(-100%);
    transition: 0.3s; /* カスタマイズしてください */
    top: 100%;
    width: 100%;
  }

  #hamburger:checked ~ .c-hamburger-menu__list {
    transform: translateX(0%);
    transition: 0.3s;
  }
}

.c-hamburger-menu__input {
  display: none;
}

.c-hamburger-menu__bg {
  background-color: #000; /* カスタマイズしてください */
  cursor: pointer;
  display: none;
  height: 100vh;
  left: 0;
  opacity: 0.4; /* カスタマイズしてください */
  position: absolute;
  top: 0;
  width: 100%;
  z-index: -1;
}

#hamburger:checked ~ .c-hamburger-menu__bg {
  display: block;
}

.c-hamburger-menu__button {
  display: none;
}

@media screen and (max-width: 750px) {
  .c-hamburger-menu__button {
    align-items: center;
    appearance: none;
    background-color: transparent;
    border: none;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 5px; /* カスタマイズしてください */
    height: 32px; /* カスタマイズしてください */
    justify-content: center;
    width: 32px; /* カスタマイズしてください */
  }
}

.c-hamburger-menu__button-mark {
  background-color: #000; /* カスタマイズしてください */
  display: block;
  height: 1px; /* カスタマイズしてください */
  transition: 0.3s; /* カスタマイズしてください */
  width: 20px; /* カスタマイズしてください */
}

@media screen and (max-width: 750px) {
  #hamburger:checked
    ~ .c-hamburger-menu__button
    .c-hamburger-menu__button-mark:nth-of-type(1) {
    transform: translate(2px, 1px) rotate(45deg); /* カスタマイズしてください */
    transform-origin: 0%; /* カスタマイズしてください */
  }
  #hamburger:checked
    ~ .c-hamburger-menu__button
    .c-hamburger-menu__button-mark:nth-of-type(2) {
    opacity: 0;
  }
  #hamburger:checked
    ~ .c-hamburger-menu__button
    .c-hamburger-menu__button-mark:nth-of-type(3) {
    transform: translate(2px, 3px) rotate(-45deg); /* カスタマイズしてください */
    transform-origin: 0%; /* カスタマイズしてください */
  }
}


</style>


<header class="c-header c-hamburger-menu"><!-- 追記 クラスを追記 -->
    <a href="#" class="c-header__logo">ロゴ</a>
    <input type="checkbox" name="hamburger" id="hamburger" class="c-hamburger-menu__input"/><!-- 追記 idはlabelのforと同じにする -->
    <label for="hamburger" class="c-hamburger-menu__bg"></label><!-- 追記 ハンバーガーメニュを開いた時の背景 -->
    <ul class="c-header__list c-hamburger-menu__list"><!-- 追記 クラスを追記 -->
      <li class="c-header__list-item">
        <a href="#" class="c-header__list-link">About</a>
      </li>
      <li class="c-header__list-item">
        <a href="#" class="c-header__list-link">Service</a>
      </li>
      <li class="c-header__list-item">
        <a href="#" class="c-header__list-link">Company</a>
      </li>
      <li class="c-header__list-item">
        <a href="#" class="c-header__list-link">Recruit</a>
      </li>
      <li class="c-header__list-item">
        <a href="#" class="c-header__list-link">Contact</a>
      </li>
    </ul>
    <label for="hamburger" class="c-hamburger-menu__button"><!-- 追記 ハンバーガーメニューのボタン -->
      <span class="c-hamburger-menu__button-mark"></span>
      <span class="c-hamburger-menu__button-mark"></span>
      <span class="c-hamburger-menu__button-mark"></span>
    </label>
  </header>


<div class="loader-area">
    <div class="loader">
    </div>
</div>

<body>

        

@yield('content')


    
<script src="{{ asset('js/app.js') . $system_version}}"></script>
<script src="{{ asset('js/bootstrap.js') . $system_version}}"></script>
<script src="{{ asset('js/common.js') . $system_version}}"></script>
<script src="{{ asset('js/swiper-bundle.min.js') . $system_version}}"></script>
<script src="{{ asset('js/jquery-3.6.0.min.js') . $system_version}}"></script>
<script src="{{ asset('js/fontawesome.js') . $system_version}}"></script>
<script src="{{ asset('js/Chart.bundle.min.js') . $system_version}}"></script>
<script src="{{ asset('js/chartjs-plugin-annotation.min.js') . $system_version}}"></script>


<!--▽▽jQuery▽▽-->
<script>

    $(function(){
        setTimeout(function(){
            end_loader();
        }, 1000);
    });
    

</script>
<!--△△jQuery△△-->




@yield('pagejs')


</body>

</html>