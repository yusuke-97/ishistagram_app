@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="mt-3 mb-3">ログイン</h3>
            <div class="card p-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group mb-2">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror ishistagram-login-input" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="メールアドレス">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>メールアドレスが正しくない可能性があります。</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror ishistagram-login-input" name="password" required autocomplete="current-password" placeholder="パスワード">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>パスワードが正しくない可能性があります。</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label ishistagram-check-label w-100" for="remember">
                                <span style="font-size: 80%;">次回から自動的にログインする</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="mt-3 btn ishistagram-submit-button w-100">
                            ログイン
                        </button>

                        <a class="btn btn-link mt-3 d-flex justify-content-center ishistagram-login-text" style="font-size: 80%;" href="{{ route('password.request') }}">
                            パスワードを忘れた場合
                        </a>
                    </div>
                </form>
            </div>

            <div class="form-group card mt-3 p-2">
                <div class="d-flex justify-content-center align-items-center p-2">
                    <span class="ishistagram-login-span" style="font-size: 80%;">アカウントをお持ちでない場合</span>
                    <a class="btn btn-link ishistagram-login-text" style="font-size: 80%;" href="{{ route('register') }}">
                        登録
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection