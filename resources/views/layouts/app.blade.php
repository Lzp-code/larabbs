<!DOCTYPE html>

{{--获取config/app.php里面的Locale--}}
<html lang="{{app()->getLocale()}}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token 为了方便前端的js脚本获取csrf令牌-->
    <meta name="csrf-token" content="{{csrf_token()}}">

    {{--继承此模板的页面，如果没有设置title的话，则title自动设置为LaraBBS--}}
    <title>@yield('title', 'LaraBBS') - Laravel 进阶教程</title>

    <!-- Styles 根据webpack.mix.js来生成css链接-->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('styles')

</head>

<body>
{{--route_class是我们在helpers.php中的辅助方法--}}
<div id="app" class="{{ route_class() }}-page">

    @include('layouts._header')

    <div class="container">

        @include('shared._messages')

        @yield('content')

    </div>

    @include('layouts._footer')
</div>

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
@yield('scripts')
</body>

</html>