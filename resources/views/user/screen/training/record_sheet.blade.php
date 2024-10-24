@extends('user.common.layouts_app')

@section('pagehead')
@section('title', '求人情報')  
@endsection
@section('content')

<style>


@media screen {
    body {
        background: #eee;
    }
    .sheet {
        background: white; /* 背景を白く */
        box-shadow: 0 .5mm 2mm rgba(0,0,0,.3); /* ドロップシャドウ */
        margin: 5mm;
        padding: 2mm 6mm 2mm 6mm;

    }
}


@page {
    /* 縦 */
    size: A4 portrait;

    /* 横 */
    /* size: A4 landscape;
    margin: 0mm; */
}
*{
    margin: 0mm;
    padding: 0mm;
}


@media print{
    .button-area{
        display: none;
    }
}

.sheet-area{
  display: flex;
  justify-content: center; /* 横方向の中央寄せ */
  align-items: center; /* 縦方向の中央寄せ */
}

@media (max-width: 768px) {
    .sheet-area{
    flex-direction: column; /* 横方向から縦方向に変更 */
  }
}


.sheet {
    /* 縦 */
    height: 291mm;
    width: 210mm;
    /* 横 */
    /* height: 210mm;
    width: 297mm; */
    page-break-after: always;
    box-sizing: border-box;
    padding: 2mm 6mm 2mm 6mm;
    font-size: 15pt;
    line-height: 1em;
}


.button-area{
    position: fixed;
    bottom: 0;
    right: 0;        
    padding: 20px;
    opacity:0.7;  
    z-index: 100;    
}

.button-area:hover{ 
  opacity:1;
}



</style>





@endsection


<div class="button-area">
    <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
        戻る
    </button>

 
    <button class="btn btn-primary print-button">      
        印刷
    </button>
</div>


<div class="sheet-area">
            
    <section class="sheet">

        <div id="" class="row p-0 m-0">

            <table class="table">

            </table>
            
        </div>        

    </section>

</div>
@section('pagejs')

<script type="text/javascript">

$(function(){




    $(document).on("click", ".print-button", function (e) {

        window.print();

    })


});

</script>
@endsection

