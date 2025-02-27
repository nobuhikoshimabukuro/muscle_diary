@php 
    
  $route_info = [];
  $route_info []= (object)['url' => route('user.index') ,'display_text' => "top"];
  $route_info []= (object)['url' => route('user.weight_log.index') ,'display_text' => "体重管理"];
  $route_info []= (object)['url' => route('user.gym_m.index') ,'display_text' => "ジム管理"];
  $route_info []= (object)['url' => route('user.exercise_m.index') ,'display_text' => "種目管理"];
  $route_info []= (object)['url' => route('user.training.index') ,'display_text' => "トレーニング管理"];    
  $route_info []= (object)['url' => route('user.logout') ,'display_text' => "ログアウト"];
@endphp


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
  
  .c-header_logo {
    color: #000; /* カスタマイズしてください */
    min-width: 80px; /* カスタマイズしてください */
    text-decoration: none;
  }
  
  .c-header_list {
    box-sizing: border-box;
    display: flex;
    margin: 0;
    padding: 0;
  }
  
  .c-header_list-item {
    list-style: none;
    text-decoration: none;
  }
  
  .c-header_list-link {
    color: #000; /* カスタマイズしてください */
    display: block;
    margin-right: 20px; /* カスタマイズしてください */
    text-decoration: none;
    padding: 10px 0px; /* カスタマイズしてください */
  }
  
  .c-header_list-link:hover {
    filter: opacity(0.6); /* カスタマイズしてください */
  }
  
  .c-hamburger-menu {
    position: relative;
  }
  
  @media screen and (max-width: 750px) {
    .c-hamburger-menu_list {
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
  
    #hamburger:checked ~ .c-hamburger-menu_list {
      transform: translateX(0%);
      transition: 0.3s;
    }
  }
  
  .c-hamburger-menu_input {
    display: none;
  }
  
  .c-hamburger-menu_bg {
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
  
  #hamburger:checked ~ .c-hamburger-menu_bg {
    display: block;
  }
  
  .c-hamburger-menu_button {
    display: none;
  }
  
  @media screen and (max-width: 750px) {
    .c-hamburger-menu_button {
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
  
  .c-hamburger-menu_button-mark {
    background-color: #000; /* カスタマイズしてください */
    display: block;
    height: 1px; /* カスタマイズしてください */
    transition: 0.3s; /* カスタマイズしてください */
    width: 20px; /* カスタマイズしてください */
  }
  
  @media screen and (max-width: 750px) {
    #hamburger:checked
      ~ .c-hamburger-menu_button
      .c-hamburger-menu_button-mark:nth-of-type(1) {
      transform: translate(2px, 1px) rotate(45deg); /* カスタマイズしてください */
      transform-origin: 0%; /* カスタマイズしてください */
    }
    #hamburger:checked
      ~ .c-hamburger-menu_button
      .c-hamburger-menu_button-mark:nth-of-type(2) {
      opacity: 0;
    }
    #hamburger:checked
      ~ .c-hamburger-menu_button
      .c-hamburger-menu_button-mark:nth-of-type(3) {
      transform: translate(2px, 3px) rotate(-45deg); /* カスタマイズしてください */
      transform-origin: 0%; /* カスタマイズしてください */
    }
  }
  
  
  </style>
    
<body>
  <header class="c-header c-hamburger-menu"><!-- 追記 クラスを追記 -->
      <a href="#" class="c-header_logo">ロゴ</a>
      <input type="checkbox" name="hamburger" id="hamburger" class="c-hamburger-menu_input"/><!-- 追記 idはlabelのforと同じにする -->
      <label for="hamburger" class="c-hamburger-menu_bg"></label><!-- 追記 ハンバーガーメニュを開いた時の背景 -->
      <ul class="c-header_list c-hamburger-menu_list"><!-- 追記 クラスを追記 -->
  
        @foreach ($route_info as $info)
          <li class="c-header_list-item">
            <a href="{{$info->url}}" class="c-header_list-link">{{$info->display_text}}</a>
          </li>
        @endforeach
     
      </ul>
      <label for="hamburger" class="c-hamburger-menu_button"><!-- 追記 ハンバーガーメニューのボタン -->
        <span class="c-hamburger-menu_button-mark"></span>
        <span class="c-hamburger-menu_button-mark"></span>
        <span class="c-hamburger-menu_button-mark"></span>
      </label>
    </header>
  </body>


<script type="text/javascript"> 
  $('#hamburger').change(function () {
    if ($(this).is(':checked')) {
        $('.pagination-area').css({
            'position': 'relative',
            'z-index': '-1'
        });
    } else {
        $('.pagination-area').css({
            'position': '',
            'z-index': ''
        });
    }
  });
</script>