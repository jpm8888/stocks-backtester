@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($is_kite_login)
                        You're logged in via Kite
                    @else
                        <a href={{\App\Http\Controllers\Base\AppConstants::KITE_REQUEST_TOKEN_URL}}><button class="btn-sm btn-primary">Login with Zerodha Kite</button></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
