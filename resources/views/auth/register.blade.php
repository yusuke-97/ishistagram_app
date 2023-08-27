@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="mt-3 mb-3">新規会員登録</h3>
            <div class="card" style="padding: 2rem;">
                <h5 style="font-weight: bold;">登録して友達の投稿を</h5>
                <h5 class="mb-3" style="font-weight: bold;">チェックしよう!</h5>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group row mb-2">
                        <label for="email" class="col-md-5 col-form-label text-md-left">メールアドレス<span class="ml-1 ishistagram-require-input-label"></span></label>

                        <div class="col-md-7">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror ishistagram-login-input" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="samurai@samurai.com">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>メールアドレスを入力してください</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label for="name" class="col-md-5 col-form-label text-md-left">氏名<span class="ml-1 ishistagram-require-input-label"></span></label>

                        <div class="col-md-7">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror ishistagram-login-input" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="侍 太郎">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>氏名を入力してください</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label for="user_name" class="col-md-5 col-form-label text-md-left">ユーザーネーム<span class="ml-1 ishistagram-require-input-label"></span></label>

                        <div class="col-md-7">
                            <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror ishistagram-login-input" name="user_name" required autocomplete="user_name" placeholder="samurai0101">

                            @error('user_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>ユーザーネームを入力してください</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label for="password" class="col-md-5 col-form-label text-md-left">パスワード<span class="ml-1 ishistagram-require-input-label"></span></label>

                        <div class="col-md-7">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror ishistagram-login-input" name="password" required autocomplete="new-password" placeholder="パスワード">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="password-confirm" class="col-md-5 col-form-label text-md-left">パスワード(確認)</label>

                        <div class="col-md-7">
                            <input id="password-confirm" type="password" class="form-control ishistagram-login-input" name="password_confirmation" required autocomplete="new-password" placeholder="パスワード(確認)">
                        </div>
                    </div>

                    <div class="mb-3 ishistagram-register-span">
                        <p>登録することで、Ishistagramの利用規約、プライバシーポリシー、Cookieポリシーに同意するものとします。</p>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn ishistagram-submit-button w-100">
                            登録する
                        </button>
                    </div>
                </form>
            </div>

            <div class="form-group card mt-3">
                <div class="d-flex justify-content-center align-items-center p-2">
                    <span class="ishistagram-login-span">アカウントをお持ちの場合</span>
                    <a class="btn btn-link ishistagram-login-text" href="{{ route('login') }}">
                        ログイン
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection