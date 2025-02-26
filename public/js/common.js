
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

function clear_error_message(target){

  $(target).html("");    
  $('.is-invalid').removeClass('is-invalid');
  $('.invalid-feedback').removeClass('invalid-feedback');
      
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

$(document).on("click", ".page-transition-button", function (e) {

  var process = $(this).data('process');
  var url = $(this).data('url');
  if(process == 1){
    window.location.href = url;
  }else{
    window.open(url, '_blank');    
  }  

});



//モーダルを開いた時の共通イベント
$('.modal').on('show.bs.modal',function(e){  
  $('body').css('overflow-y', 'none');
});

//モーダルを閉じた時の共通イベント
$('.modal').on('hidden.bs.modal', function() {
  $('body').css('overflow-y', 'auto');
});


$(document).on("click", ".page-link", function (e) {

  var button = $(this);

  standby_processing(1,button,"body");

  var add_url = "";

  // search-areaを取得
  var search_area = $(".search-area");

  // search_area内のinput, select, textareaを取得
  var search_inputs = search_area.find('input, select, textarea');

  
  // 各要素のnameと値を取得してオブジェクトに追加
  search_inputs.each(function (index) {

      var input_name = $(this).data("target");
      var input_value = $(this).val().trim();
      
      // ラジオボタンの場合、選択された値を取得
      if ($(this).is(":radio")) {

          if($(this).is(":checked")) {
          input_value = $(this).val().trim();
          }else{
          input_value = "";
          }    
      }

      // チェックボックスの場合、選択された値を取得
      if ($(this).is(":checkbox")) {

        if($(this).is(":checked")) {
          input_value = $(this).val().trim();
        }else{
          input_value = "";
        }    
      }

      // numericクラスが存在し、input_valueにカンマが含まれている場合、カンマを除去
      if($(this).hasClass("numeric")){
          input_value = input_value.replace(/,/g, "");
      }

      if (input_value != null && input_value != "") {     

        add_url += "&" + input_name + "=" + input_value;
          
      }

  });



  var current_url = window.location.href;

  // URLからクエリパラメータを取り除く
  var current_url = current_url.split('?')[0];

  // 新しいURLを作成
  var new_url = current_url + add_url;
  // ページを新しいURLでリロード
  window.location.href = new_url;

  setTimeout(function() {
    standby_processing(2,button,"body");
  }, 1000);



});

$(document).on("click", ".search-table .search-button", function (e) {

  var button = $(this);

  standby_processing(1,button,"body");

  var add_url = "";

  // search-areaを取得
  var search_area = $(".search-area");

  // search_area内のinput, select, textareaを取得
  var search_inputs = search_area.find('input, select, textarea');

  // 最初のクエリパラメータかどうかを示すフラグ
  var isFirstParameter = true;

  // 各要素のnameと値を取得してオブジェクトに追加
  search_inputs.each(function (index) {

      var input_name = $(this).data("target");
      var input_value = $(this).val().trim();
      
      // ラジオボタンの場合、選択された値を取得
      if ($(this).is(":radio")) {

          if($(this).is(":checked")) {
          input_value = $(this).val().trim();
          }else{
          input_value = "";
          }    
      }

      // チェックボックスの場合、選択された値を取得
      if ($(this).is(":checkbox")) {

        if($(this).is(":checked")) {
          input_value = $(this).val().trim();
        }else{
          input_value = "";
        }    
      }

      // numericクラスが存在し、input_valueにカンマが含まれている場合、カンマを除去
      if($(this).hasClass("numeric")){
          input_value = input_value.replace(/,/g, "");
      }

      if (input_value != null && input_value != "") {     

          // inputValueが空でない場合の処理
          if (isFirstParameter) {
          add_url += "?" + input_name + "=" + input_value;

          // 最初のクエリパラメータを追加したのでフラグを更新
          isFirstParameter = false; 

          } else {

          add_url += "&" + input_name + "=" + input_value;
          
          }
          
      }

  });



  var current_url = window.location.href;

  // URLからクエリパラメータを取り除く
  var current_url = current_url.split('?')[0];

  // 新しいURLを作成
  var new_url = current_url + add_url;
  
  // ページを新しいURLでリロード
  window.location.href = new_url;  

  setTimeout(function() {
    standby_processing(2,button,"body");
  }, 1000);

});
