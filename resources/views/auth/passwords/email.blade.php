@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-5">
                <div class="d-flex justify-content-center p-3">
                    <i class="fa-solid fa-lock fa-2xl"></i>
                </div>
                <h3 class="d-flex justify-content-center mt-3 mb-3">ログインできない場合</h3>

                <p>
                    ご登録時のメールアドレスを入力してください。
                    アカウントにアクセスするためのリンクをお送りします。
                </p>

                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif


                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror ishistagram-login-input" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="メールアドレス">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>メールアドレスが正しくない可能性があります。</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn ishistagram-submit-button w-100">
                            送信
                        </button>
                    </div>
                </form>
                <hr>

                <div class="d-flex justify-content-center align-items-center">
                    <a class="btn btn-link ishistagram-login-text" href="{{ route('register') }}">
                        新しいアカウントを作成
                    </a>
                </div>

                <div class="form-group">
                    <a type="button" class="btn btn-link mt-3 d-flex justify-content-center ishistagram-login-back w-100" href="{{ route('login') }}">
                        ログインに戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection