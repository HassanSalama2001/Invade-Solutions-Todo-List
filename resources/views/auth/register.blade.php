@extends('app')

@section('content')
    <div class="row justify-content-center mt-5 pt-5">
        <div class="col-md-5 my-50">
            <div class="card">
                <div class="card-header bg-dark text-center">
                    <h5 class="text-light">Register</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group my-4">
                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                autofocus
                                placeholder="Name"
                                required
                            />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group my-4">
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
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
                                class="form-control"
                                value="{{ old('password') }}"
                                placeholder="Password"
                                autocomplete="new-password"
                                required
                            />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group my-4">
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Confirm Password"
                                autocomplete="new-password"
                                required
                            />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group my-4">
                            <button type="submit" class="btn btn-success float-start">Register</button>
                            <a href="{{ route('login') }}" class="btn btn-dark float-end">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
