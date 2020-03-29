<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ShivaFNO') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css?family=Exo+2:400,600,700,800&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="{{asset('datafeeds/udf/dist/polyfills.js'). '?random=' . \App\Http\Controllers\Base\AppConstants::BUILD_VERSION}}"></script>
    <script src="{{asset('datafeeds/udf/dist/bundle.js'). '?random=' . \App\Http\Controllers\Base\AppConstants::BUILD_VERSION}}"></script>
    <script src="{{ asset('js/app.js') . '?random=' . \App\Http\Controllers\Base\AppConstants::BUILD_VERSION}}"></script>
</head>
<body>
    <div id="{{$div_id}}" data="{{isset($id) ? $id : ''}}"></div>
</body>

</html>
