@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    <p>You're logged in.</p>
{{--                    @if($is_kite_login)--}}
{{--                        You're logged in via Kite <br>--}}
{{--                    @else--}}
{{--                        <a href={{\App\Http\Controllers\Base\AppConstants::KITE_REQUEST_TOKEN_URL}}><button class="btn-sm btn-outline-primary">Login with Zerodha Kite</button></a>--}}
{{--                    @endif--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
