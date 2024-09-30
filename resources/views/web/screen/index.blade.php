@extends('web.common.layouts_app')

@section('pagehead')
@section('title', 'test')  

@endsection
@section('content')

<style>




</style>

<div class="mt-3 text-center container">

  
  <div class="contents row p-0">
    

  </div> 


</div>









@endsection

@section('pagejs')

<script type="text/javascript">


  document.addEventListener('DOMContentLoaded', function() {

        const swiper1 = new Swiper(".swiper1", {
            loop: true, // ループ
            speed: 900, // 少しゆっくり(デフォルトは300)
            slidesPerView: 1, // 一度に表示する枚数
            spaceBetween: 0, // スライド間の距離
            centeredSlides: true, // アクティブなスライドを中央にする
            autoplay: {
                delay: 8000, // 8秒後に次のスライド                
                disableOnInteraction: false, // 矢印をクリックしても自動再生を止めない
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints:{       
                768: {
                    slidesPerView: 1.2,
                    spaceBetween: 15,
                    speed: 1100,
                },
                1024: {
                    slidesPerView: 1.3,
                    spaceBetween: 20,
                    speed: 1200,
                },
                1200: {
                    slidesPerView: 1.5,
                    spaceBetween: 30,
                    speed: 1300,
                }
            },
            on: {
                init: adjustSwiperHeight,
                resize: adjustSwiperHeight,
                slideChange: adjustSwiperHeight // スライドが変更されたときに高さを調整
            }
        });

        function adjustSwiperHeight() {
            var swiperSlide = document.querySelector('.swiper-slide');
            if (swiperSlide) {
                var slideWidth = swiperSlide.offsetWidth;
                var swiperContainer = document.querySelector('.swiper');
                var goldenRatio = 1.618;
                swiperContainer.style.height = (slideWidth / goldenRatio) + 'px';
            }
        }

        // 初期ロード時に高さを調整
        adjustSwiperHeight();

        // ウィンドウのリサイズ時に高さを調整
        window.addEventListener('resize', adjustSwiperHeight);

        const swiper2 = new Swiper(".swiper2", {
          loop: true, // ループ
          slidesPerView: 1.1, // 一度に表示する枚数
          speed: 8000, // ループの時間
          allowTouchMove: true, // スワイプ有効
          autoplay: { //自動再生
            delay: 0, // 途切れなくループ
            disableOnInteraction: false, // 矢印をクリックしても自動再生を止めない
          },
          breakpoints:{       
                768: {
                    slidesPerView: 2,                    
                },
                1024: {
                    slidesPerView: 2.5,
                },
                1200: {
                    slidesPerView: 3,                    
                }
            }
        });

  });






</script>


@endsection

