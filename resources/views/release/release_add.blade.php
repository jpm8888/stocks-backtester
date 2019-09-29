<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><strong>{{ __('Create Release') }}</strong></div>

            <div class="card-body">
                <form method="POST" action="{{ route('releases.create') }}">
                    @csrf

                    @if (session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif


                    <div class="form-group row">
                        <label for="build" class="col-md-4 col-form-label text-md-right">{{ __('Build Version') }}
                            <star class="text-danger">*</star>
                        </label>

                        <div class="col-md-6">
                            <input id="build" type="text" class="input-sm form-control" name="build" required>
                        </div>


                    </div>

                    <div class="form-group row">
                        <label for="msg" class="col-md-4 col-form-label text-md-right">{{ __('Changelog: (HTML)') }}
                            <star class="text-danger">*</star>
                        </label>

                        <div class="col-md-6">
                            <textarea id="msg" type="text" class="input-sm form-control" name="msg" required></textarea>
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
