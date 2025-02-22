@extends('app')

@section('content')
    <div class="row justify-content-center mt-5 pt-5">
        <div class="col-md-5 my-50">
            <div class="card">
                <div class="card-header bg-dark text-center">
                    <h5 class="text-light">Login</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group my-4">
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                autofocus
                                placeholder="Email Address"
                                required
                            />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group my-4">
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                value="{{ old('password') }}"
                                placeholder="Password"
                                required
                            />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group my-4">
                            <button type="submit" class="btn btn-success float-start">Login</button>
                            <a href="{{ route('register') }}" class="btn btn-dark float-end">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
