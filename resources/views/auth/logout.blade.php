@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Logout') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <p>
                                {{ __('Are you sure you want to log out?') }}
                            </p>

                            <div class="form-group row mb-0">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Logout') }}
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
