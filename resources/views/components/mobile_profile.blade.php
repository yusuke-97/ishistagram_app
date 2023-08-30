<!-- モバイル用プロフィール -->
<div class="row">

    <!-- プロフィール画像とユーザー名のセクション -->
    <div class="col-4 text-center d-flex flex-column justify-content-center align-items-center">

        <!-- ユーザーのプロフィール画像が存在する場合 -->
        @if($user->profile_image)
        <img class="mobile-profile-image rounded-circle" src="{{ Storage::disk('s3')->url('profile_images/' . $user->profile_image) }}" alt="プロフィール画像">
        @else

        <!-- ユーザーのプロフィール画像が存在しない場合のデフォルトアイコン -->
        <i class="fas fa-user fa-5x mobile-profile-icon mt-auto mb-auto"></i>
        @endif

        <!-- ユーザー名 -->
        <p class="user-name mt-2" style="font-weight: bold; font-size: 90%">{{ $user->user_name }}</p>
    </div>

    <!-- ユーザー詳細情報のセクション -->
    <div class="col-8">

        <!-- フォロー, フォロワー, 投稿数のリンクセクション -->
        <div class="stats-links">

            <!-- フォロー情報のセクション -->
            <div class="text-center">
                <a href="#" id="show-following" data-bs-toggle="modal" data-bs-target="#followingModal{{ $user->id }}" style="color: black; text-decoration: none;">
                    <div><strong style="font-size: 80%;">{{ $user->following->count() }}</strong></div>
                    <div style="font-size: 80%;">フォロー</div>
                </a>
            </div>

            <!-- フォロワー情報のセクション -->
            <div class="text-center">
                <a href="#" id="show-followers" data-bs-toggle="modal" data-bs-target="#followersModal{{ $user->id }}" style="color: black; text-decoration: none;">
                    <div><strong style="font-size: 80%;">{{ $user->followers->count() }}</strong></div>
                    <div style="font-size: 80%;">フォロワー</div>
                </a>
            </div>

            <!-- 投稿数のセクション -->
            <div class="text-center">
                <a href="#" style="color: black; text-decoration: none;">
                    <div><strong style="font-size: 80%;">{{ $user->posts->count() }}</strong></div>
                    <div style="font-size: 80%;">投稿数</div>
                </a>
            </div>
        </div>

        <!-- ユーザーの本名とプロフィール編集・フォローボタン -->
        <div class="mt-2 mb-4 d-flex align-items-center justify-content-between">

            <!-- ユーザーの本名 -->
            <p class="bold larger-text mb-0 mr-2" style="font-size: 90%;">{{ $user->name }}</p>

            <!-- ログイン中のユーザーがプロフィールのユーザーと同一である場合の編集リンク -->
            @if (auth()->user()->id == $user->id)
            <a href="{{ route('profile.edit', ['profile' => $user->id]) }}" title="プロフィールを編集" class="profile-link" style="color: black; text-decoration: none; padding: 5px 10px; background-color: #e0e0e0; border-radius: 4px; font-size: 70%;">
                <i class="fa-solid fa-gear"></i>
                <span style="font-weight: bold;">プロフィールを編集</span>
            </a>

            <!-- 他のユーザーのプロフィールの場合 -->
            @else

            <!-- ログイン中のユーザーがこのユーザーをフォローしている場合 -->
            @if (auth()->user()->isFollowing($user))
            <form action="{{ route('unfollow.profile', ['user' => $user->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="profile-link" style="border: none; color: black; padding: 5px 10px; background-color: #e0e0e0; border-radius: 4px;">フォロー中</button>
            </form>

            <!-- フォローしていない場合 -->
            @else
            <form action="{{ route('follow.profile', ['user' => $user->id]) }}" method="POST">
                @csrf
                <button type="submit" class="profile-link" style="border: none; color: black; padding: 5px 10px; background-color: #e0e0e0; border-radius: 4px;">フォローする</button>
            </form>
            @endif
            @endif
        </div>

        <!-- フォローモーダルの読み込み -->
        @include('modals.show_follow')

        <!-- ユーザーの自己紹介文 -->
        <p style="font-size: 80%;">{{ $user->bio }}</p>
    </div>
</div>