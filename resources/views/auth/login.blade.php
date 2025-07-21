@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-primary px-3 py-2">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-7 d-none d-lg-flex text-white justify-content-center align-items-center">
                                <div class="text-center">
                                    <h1>Welcome</h1>
                                    <h2>To</h2>
                                    <h3>My Wallet</h3>
                                    <p>Securely manage your balance, transactions, </p>
                                    <p>and daily top-ups — all in one place.</p>
                                </div>
                            </div>
                            <div class="col-lg-5 bg-white rounded-4 px-4 py-3">
                                <div id="change-email-phone-content">
                                    <label for="login" class="form-label mb-0">{{ __('Email Address') }}</label>
                                    <input id="login" type="email" class="form-control @error('login') is-invalid @enderror mb-2" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>
                                    @error('login')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    {{ __('Login') }}
                                </button>
                                <button onclick="changeEmailAndPhone(this)" type="button" class="btn border w-100">
                                    Continue with Phone Number
                                </button>
                                <script>
                                    function changeEmailAndPhone(x) {
                                        const changeEmailPhoneContent = document.querySelector('#change-email-phone-content');
                                        if(x.textContent == 'Continue with Email') {
                                            x.textContent = 'Continue with Phone Number';
                                            changeEmailPhoneContent.innerHTML = `
                                            <label for="login" class="form-label mb-0">{{ __('Email Address') }}</label>
                                            <input id="login" type="email" class="form-control @error('login') is-invalid @enderror mb-2" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>
                                            @error('login')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            `;
                                        } else {
                                            x.textContent = 'Continue with Email';
                                            changeEmailPhoneContent.innerHTML = `
                                            <label for="login" class="form-label mb-0">{{ __('Phone Number') }}</label>
                                            <input id="login" type="number" class="form-control @error('login') is-invalid @enderror mb-2" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>
                                            @error('login')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            `;
                                        }
                                    }
                                </script>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
