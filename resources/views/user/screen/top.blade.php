@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'top')  

@endsection
@section('content')

<style>




</style>

<div class="mt-3 text-center container">

  
  <div class="contents row p-0">

    <button class="btn btn-outline-success page-transition-button"
    data-url="{{route('user.logout')}}"
    data-process="1"
    >ログアウト
    </button>         

    <button class="btn btn-outline-success page-transition-button"
    data-url="{{route('user.weight_log.index')}}"
    data-process="1"
    >体重管理
    </button>

    <button class="btn btn-outline-success page-transition-button"
    data-url="{{route('user.gym_m.index')}}"
    data-process="1"
    >ジム管理
    </button>

    <button class="btn btn-outline-success page-transition-button"    
    data-url="{{route('user.exercise_m.index')}}"
    data-process="1"
    >種目管理
    </button>

    <button class="btn btn-outline-success page-transition-button"
    data-url="{{route('user.training.index')}}"
    data-process="1"
    >トレーニング管理
    </button>

    <button class="btn btn-outline-success page-transition-button"
    data-url="{{route('user.training.record_sheet')}}"
    data-process="1"
    >帳票テスト
    </button>

  </div> 


</div>

@endsection

@section('pagejs')

<script type="text/javascript">

</script>


@endsection

