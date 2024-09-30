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
    <meta name="csrf-token" content="{{ csrf_token() }}">  {{-- CSRFトークン --}}

    
    @yield('pagehead')
    <title>@yield('title')</title>


</head>

<style>


</style>


<body>

        

@yield('content')


    
<script src="{{ asset('js/app.js') . $system_version}}"></script>
<script src="{{ asset('js/bootstrap.js') . $system_version}}"></script>
<script src="{{ asset('js/common.js') . $system_version}}"></script>
<script src="{{ asset('js/swiper-bundle.min.js') . $system_version}}"></script>
<script src="{{ asset('js/jquery-3.6.0.min.js') . $system_version}}"></script>
<script src="{{ asset('js/fontawesome.js') . $system_version}}"></script>


<!--▽▽jQuery▽▽-->
<script>

 
    

</script>
<!--△△jQuery△△-->




@yield('pagejs')


</body>

</html>