@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><strong>{{ __('Update Password') }}</strong></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('user.password.update') }}">
                            @csrf

                            @if (session('success'))
                                <div class="alert alert-success text-center">
                                    {{ session('success') }}
                                </div>
                            @endif


                            <div class="form-group row">
                                <label for="current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}
                                    <star class="text-danger">*</star>
                                </label>

                                <div class="col-md-6">
                                    <input id="current" type="password"
                                           class="input-sm form-control {{ $errors->has('current') ? ' is-invalid' : '' }}"
                                           name="current" required>
                                    @if ($errors->has('current'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('current') }}</strong>
                                    </span>
                                    @endif
                                </div>


                            </div>

                            <div class="form-group row">
                                <label for="new" class="col-md-4 col-form-label text-md-right">{{ __('New') }}
                                    <star class="text-danger">*</star>
                                </label>

                                <div class="col-md-6">
                                    <input id="new" type="password"
                                           class="input-sm form-control {{ $errors->has('new') ? ' is-invalid' : '' }}"
                                           name="new" required>
                                    @if ($errors->has('new'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="confirm_new"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Re-type New') }}
                                    <star class="text-danger">*</star>
                                </label>

                                <div class="col-md-6">
                                    <input id="confirm_new" type="password"
                                           class="input-sm form-control {{ $errors->has('confirm_new') ? ' is-invalid' : '' }}"
                                           name="confirm_new" required>
                                    @if ($errors->has('confirm_new'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('confirm_new') }}</strong>
                                    </span>
                                    @endif
                                </div>


                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        {{ __('Update') }}
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
    <script>
        $(document).ready(function () {

            // $('#table_id').DataTable();
        });
    </script>
@endsection
