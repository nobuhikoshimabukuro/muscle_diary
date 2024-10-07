@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'user login')  

@endsection
@section('content')

<style>
  * {
box-sizing: border-box;
}

*:focus {
 outline: none;
}
body {
   font-family: Arial;
   background-color: #3498DB;
}
.login {
margin: 20px auto;

}
.login-screen {
background-color: #FFF;
padding: 20px;
border-radius: 5px
}

.app-title {
text-align: center;
color: #777;
}

.login-form {
text-align: center;
}
.control-group {
margin-bottom: 10px;
}

input {
   text-align: left;
   background-color: #ECF0F1;
   border: 2px solid transparent;
   border-radius: 3px;
   font-size: 16px;
   font-weight: 200;
   padding: 5px;
   transition: border .5s;
   width: 100%;
}

input:focus {
border: 2px solid #3498DB;
box-shadow: none;
}


.login-link {
 font-size: 12px;
 color: #444;
 display: block;
 margin-top: 12px;
}
</style>



   <div class="row m-0 p-0 justify-content-center">

       <div class="col-12 col-md-8 col-lg-7 col-xl-5">

           <form action="{{ route('user.login_check') }}" id='approve_form' method="post" enctype="multipart/form-data"
           autocomplete="off"
           >
               @csrf
               <div class="login">

                   <div class="login-screen">

                       <div class="app-title">
                           <h1>Login</h1>
                       </div>
                   
                       <div class="login-form">
                           <div class="control-group">                
                           <input type="text" class="login-field" name="mailaddress" value="" placeholder="login id" id="mailaddress" >
                           <label class="login-field-icon fui-user" for="mailaddress"></label>
                           </div>
                   
                           <div class="control-group">
                           <input type="password" class="login-field" name="password" placeholder="password" id="password">
                           <label class="login-field-icon fui-lock" for="password"></label>
                           </div>
                   
                           
                       </div>

                       <div class="col-12 m-0 p-0 text-end">
                           <button type="button" id='approve_button' class="btn btn-primary" style="width: auto;">login</button>                
                       </div>

                 

                       <div class="col-12 m-0 p-0">
                           
                       </div>
                           
                   </div>
               </div>

           </form>

       </div>
    </div>


@endsection

@section('pagejs')

<script type="text/javascript">

$(function(){

   
   $(document).ready(function () {        
       $('#mailaddress').focus();
   });

   
   $("#approve_form").keypress(function(e) {

       if(e.which == 13) {            
           // 判定
           if( document.getElementById("approve_button") == document.activeElement ){
               
               login_process();
           
           }else if( document.getElementById("mailaddress") == document.activeElement ){

               $('#password').focus();
               return false;

           }else if( document.getElementById("password") == document.activeElement ){

               $('#approve_button').focus();
               return false;

           }else{
               return false;
           }            
       }
   });    
   
   $('#approve_button').click(function () {        
       login_process();
   });


   function login_process(){

      //{{-- メッセージクリア --}}
      $('.ajax-msg').html('');
       $('.is-invalid').removeClass('is-invalid');

       var mailaddress = $("#mailaddress").val();
       var password = $("#password").val();
       var Judge = true;

       if(password == ""){
           $('#password').focus();
           Judge = false;
           $("#password").addClass("is-invalid");            
       }

       if(mailaddress == ""){
           $('#mailaddress').focus();
           Judge = false;
           $("#mailaddress").addClass("is-invalid");                              
       }


       if(!Judge){
           return false;
       }
       
       //{{-- マウスカーソルを待機中に --}}         
       document.body.style.cursor = 'wait';

       // ２重送信防止
       // 保存tを押したらdisabled, 10秒後にenable
       $(this).prop("disabled", true);

       // 確認画面へ画面遷移
       $('#approve_form').submit(); 

   }

       


});

</script>
@endsection

