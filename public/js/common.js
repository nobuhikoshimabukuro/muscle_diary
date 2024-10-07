
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
  

function standby_processing(process_branch ,button ,target = 'body'){

  if(process_branch == 1){

    button.prop("disabled", true);
    document.body.style.cursor = 'wait';

    // 処理中のローディングcss
    let Html = '<div class="processing-area">';
    Html += '<div class="processing"></div>';
    Html += '</div>';

    // 対象要素に作成したhtmlを追加
    $(Html).appendTo(target);


  }else{

    button.prop("disabled", false);
    document.body.style.cursor = 'auto';

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
  
}

$(document).on("click", ".page-transition-button1", function (e) {

  var url = $(this).data('url');
  window.location.href = url;

});

//画面遷移ボタン別タブ
$(document).on("click", ".page-transition-button2", function (e) {

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


