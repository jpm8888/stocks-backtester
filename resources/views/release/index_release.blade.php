@extends('layouts.app')
@section('content')

    @if($is_super_admin)
    <div class="container">
        @include('release.release_add')
    </div>
    @endif

    @foreach($rows as $r)
    <div class="container" style="margin-top: 10px">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><strong>{{ __('Build : ' . $r->build) }}</strong></div>

                    <div class="card-body">
                        <p>{!!$r->msg!!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('script')
    <script>
        $(document).ready(function () {

            // $('#table_id').DataTable();
        });
    </script>
@endsection
