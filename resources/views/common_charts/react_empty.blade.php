@extends('layouts.app')

@section('content')
    <section class="content">
        <div id="{{$div_id}}"></div>
    </section>
@endsection

@section('script')
    <script src="{{asset('datafeeds/udf/dist/polyfills.js'). '?random=' . \App\Http\Controllers\Base\AppConstants::BUILD_VERSION}}"></script>
    <script src="{{asset('datafeeds/udf/dist/bundle.js'). '?random=' . \App\Http\Controllers\Base\AppConstants::BUILD_VERSION}}"></script>
    <script src="{{ asset('js/app.js') . '?random=' . \App\Http\Controllers\Base\AppConstants::BUILD_VERSION}}"></script>
@endsection
