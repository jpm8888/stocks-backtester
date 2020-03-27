@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><strong>{{ __('Upload') }}</strong></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('process_indices.import') }}" enctype="multipart/form-data">
                            @csrf

                            @if (session('error'))
                                <div class="alert alert-danger text-center">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success text-center">
                                    {{ session('success') }}
                                </div>
                            @endif


                            <div class="form-group row">
                                <label for="current" class="col-md-3 col-form-label text-md-right">{{ __('Select Files Type') }}
                                    <star class="text-danger">*</star>
                                </label>

                                <select class="col-md-4 form-control" name="type" required>
                                    <option value="vix" selected>VIX</option>
                                    <option value="bnf">BANKNIFTY</option>
                                    <option value="nf">NIFTY</option>
                                </select>

                            </div>


                            <div class="form-group row">
                                <label for="current" class="col-md-3 col-form-label text-md-right">{{ __('Upload Files') }}
                                    <star class="text-danger">*</star>
                                </label>

                                <input type="file" class="col-md-4 form-control" name="files[]" multiple required accept=".csv">

                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        {{ __('Process Copies') }}
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
