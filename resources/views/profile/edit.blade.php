@extends('layouts.app')

<!-- スクリプトの追加 -->
@push('scripts')
<script src="{{ asset('/js/profile_script.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- プロフィール編集カード -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="font-weight: bold;">プロフィール編集</h5>
                </div>

                <div class="card-body">

                    <!-- プロフィール更新フォーム -->
                    <form method="POST" action="{{ route('profile.update', ['profile' => $user->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- フラッシュメッセージの表示 -->
                        @if (session('flash_message'))
                        <div class="alert alert-success">
                            {{ session('flash_message') }}
                        </div>
                        @endif

                        <!-- プロフィール画像 -->
                        <div class="form-group mb-4">
                            <label for="profile_image" style="font-weight: bold;">プロフィール画像</label>
                            <div class="d-flex align-items-center mb-2">
                                @if ($user->profile_image)
                                <img class="edit-profile-image" id="currentProfileImage" src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}" alt="プロフィール画像" width="100">
                                @else
                                <i class="fas fa-user fa-2x edit-profile-icon"></i>
                                @endif
                                <input type="file" class="custom-file-input" id="profile_image" name="profile_image" style="display: none;">
                                <a id="customProfile" href="#" class="text-primary" style="text-decoration: none; font-size: 80%; margin-left: 16px; font-weight: bold;">プロフィール画像を変更</a>
                                <a id="deleteProfileImage" href="#" class="text-danger" style="text-decoration: none; font-size: 80%; margin-left: 16px; font-weight: bold;">プロフィール画像を削除</a>
                            </div>
                        </div>

                        <!-- 名前 -->
                        <div class="form-group mb-4">
                            <label for="name" style="font-weight: bold;">名前</label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control">
                        </div>

                        <!-- ユーザー名 -->
                        <div class="form-group mb-4">
                            <label for="user_name" style="font-weight: bold;">ユーザー名</label>
                            <input type="text" id="user_name" name="user_name" value="{{ $user->user_name }}" class="form-control">
                        </div>

                        <!-- 自己紹介 -->
                        <div class="form-group mb-4">
                            <label for="bio" style="font-weight: bold;">自己紹介</label>
                            <textarea id="bio" name="bio" class="form-control" style="resize: none;">{{ $user->bio }}</textarea>
                        </div>

                        <!-- メールアドレス -->
                        <div class="form-group mb-4">
                            <label for="email" style="font-weight: bold;">メール</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control">
                        </div>

                        <!-- 隠しフィールドを追加 -->
                        <input type="hidden" name="delete_image_flag" id="delete_image_flag" value="0">

                        <!-- 保存ボタン -->
                        <button type="submit" class="btn share-btn">保存</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection