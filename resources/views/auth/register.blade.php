@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-primary px-3 py-2">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row justify-content-center">
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
                                <h5 class="text-center" style="color: rgb(1, 1, 85);">Sign Up</h5>
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" class="mb-2 form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div id="email-phone-content">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="email" type="email" class="mb-2 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control mb-2 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">Password must include at least one uppercase, one lowercase, one number and least 8 letter.</div>

                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control mb-3" name="password_confirmation" required autocomplete="new-password">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    {{ __('Sign Up') }}
                                </button>
                                <button type="button" onclick="changeEmailAndPhone()" id="changeEAndPButton" class="btn w-100 border">Continue with Phone Number</button>
                                <script>
                                    function changeEmailAndPhone() {
                                        const text = document.querySelector('#changeEAndPButton').textContent;
                                        const emailPhoneContent = document.querySelector('#email-phone-content');
                                        if(text == 'Continue with Phone Number') {
                                            document.querySelector('#changeEAndPButton').textContent = 'Continue with Email';
                                            emailPhoneContent.innerHTML = `
                                            <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                            <input id="phone" type="number" class="mb-2 form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            `;
                                        } else {
                                            document.querySelector('#changeEAndPButton').textContent = 'Continue with Phone Number';
                                            emailPhoneContent.innerHTML = `
                                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                            <input id="email" type="email" class="mb-2 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                            @error('email')
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
