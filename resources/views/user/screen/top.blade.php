@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'top')  

@endsection
@section('content')

<style>




</style>

<div class="mt-3 text-center container">

  
  <div class="contents row p-0">

    <button class="btn btn-outline-success page-transition-button1"
    data-url="{{route('user.logout')}}"
    >ログアウト
    </button>         

    <button class="btn btn-outline-success page-transition-button1"
    data-url="{{route('user.weight_log.index')}}"
    >体重管理
    </button>

  </div> 


</div>

@endsection

@section('pagejs')

<script type="text/javascript">

</script>


@endsection

