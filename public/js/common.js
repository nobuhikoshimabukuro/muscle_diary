

function set_main_minheight() {

  const header1 = document.querySelector('.header1');
  const header2 = document.querySelector('.header2');
  const footer = document.querySelector('footer');
  const main = document.getElementById('main');

  // ヘッダーとフッターの高さを取得
  const header1Height = header1.offsetHeight;
  const header2Height = header2.offsetHeight;        
  const footerHeight = footer.offsetHeight;

  // 画面の高さを取得
  const windowHeight = window.innerHeight;



  // メインコンテンツのmin-heightを計算して設定
  const mainMinHeight = windowHeight - header1Height - header2Height - footerHeight - getHeightWithMargin(main);
  main.style.minHeight = mainMinHeight + 'px';

}

function getHeightWithMargin(element) {
      const styles = window.getComputedStyle(element);
      const margin = parseFloat(styles['marginTop']) + parseFloat(styles['marginBottom']);
      return Math.ceil(margin);
}


  // 引数は操作制御したいセレクタ
  function start_loader(target){

    // 処理中のローディングcss
    let Html = '<div class="loader-area">';
    Html += '<div class="loader"></div>';
    Html += '</div>'; 

    // 対象要素に作成したhtmlを追加
    $(Html).appendTo(target); 
  
  }



function end_loader() {

  var elements = document.querySelectorAll('.loader-area');

  // 取得した要素を削除
  elements.forEach(function(element) {
    element.remove();
  });


  var elements = document.querySelectorAll('.loader');

  // 取得した要素を削除
  elements.forEach(function(element) {
    element.remove();
  });
}
  
  // 引数は操作制御したいセレクタ
function start_processing(target){

  // 処理中のローディングcss
  let Html = '<div class="processing-area">';
  Html += '<div class="processing"></div>';
  Html += '</div>';

  // 対象要素に作成したhtmlを追加
  $(Html).appendTo(target);


}

function end_processing(){   


  var elements = document.querySelectorAll('.processing-area');

  // 取得した要素を削除
  elements.forEach(function(element) {
    element.remove();
  });


  var elements = document.querySelectorAll('.processing');

  // 取得した要素を削除
  elements.forEach(function(element) {
    element.remove();
  });

}


//画面遷移ボタン別タブ
$(document).on("click", ".page-transition-button", function (e) {

  var url = $(this).data('url');
  window.open(url, '_blank');

});


//モーダルを開いた時の共通イベント
$('.modal').on('show.bs.modal',function(e){  
  $('body').css('overflow-y', 'none');
});

//モーダルを閉じた時の共通イベント
$('.modal').on('hidden.bs.modal', function() {
  $('body').css('overflow-y', 'auto');
});
