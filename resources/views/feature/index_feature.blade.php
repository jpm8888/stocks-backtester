@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><strong>{{ __('Create Request') }}</strong></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('feature.create') }}">
                            @csrf

                            @if (session('success'))
                                <div class="alert alert-success text-center">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <p>Please explain your bug or feature briefly, or you can contact me via telegram : </p>
                            <p><b>(+91) 8800-50-7427</b></p>


                            <div class="form-group row" style="margin-top: 100px">

                                <label for="current" class="col-md-3 col-form-label text-md-right">{{ __('Explain your feature or bug') }}
                                    <star class="text-danger">*</star>
                                </label>

                                <div class="col-md-9">
                                    <textarea id="current" type="password" class="input-sm form-control" name="msg" required></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        {{ __('Create Ticket') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

@endsection
