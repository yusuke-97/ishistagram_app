<!-- デスクトップ用プロフィール -->
<div class="row">

    <!-- 左側のカラム：プロフィール画像とユーザー名表示エリア -->
    <div class="col-sm-4 text-center">
        @if($user->profile_image)
        <!-- プロフィール画像の表示 -->
        <img class="profile-image" src="{{ Storage::disk('s3')->url('profile_images/' . $user->profile_image) }}" alt="プロフィール画像">
        @else
        <!-- プロフィール画像がない場合のアイコン表示 -->
        <i class="fas fa-user fa-5x profile-icon"></i>
        @endif

        <!-- ユーザー名の表示 -->
        <p class="user-name mt-3" style="font-weight: bold;">{{ $user->user_name }}</p>
    </div>

    <!-- 右側のカラム：ユーザーの詳細情報表示エリア -->
    <div class="col-sm-8">
        <div class="d-flex align-items-center justify-content-between">

            <!-- ユーザーの本名表示 -->
            <p class="bold larger-text mb-0 mr-2">{{ $user->name }}</p>

            <!-- 現在のユーザーが表示中のユーザー本人かどうかで表示を切り替え -->
            @if (auth()->user()->id == $user->id)

            <!-- プロフィール編集リンク -->
            <a href="{{ route('profile.edit', ['profile' => $user->id]) }}" title="プロフィールを編集" class="profile-link" style="color: black; text-decoration: none; padding: 5px 10px; background-color: #e0e0e0; border-radius: 4px;">
                <i class="fa-solid fa-gear"></i>
                プロフィールを編集
            </a>
            @else

            <!-- フォロー関連のボタン -->
            @if (auth()->user()->isFollowing($user))

            <!-- フォロー解除ボタン -->
            <form action="{{ route('unfollow.profile') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="profile-link" style="border: none; color: black; padding: 5px 10px; background-color: #e0e0e0; border-radius: 4px;">フォロー中</button>
            </form>

            @else

            <!-- フォローボタン -->
            <form action="{{ route('follow.profile') }}" method="POST">
                @csrf
                <button type="submit" class="profile-link" style="border: none; color: black; padding: 5px 10px; background-color: #e0e0e0; border-radius: 4px;">フォローする</button>
            </form>
            @endif
            @endif
        </div>

        <!-- ユーザーのフォロー、フォロワー、投稿数情報 -->
        <div class="d-flex justify-content-between mt-2">

            <!-- フォロー数 -->
            <a href="#" id="show-following" data-bs-toggle="modal" data-bs-target="#followingModal{{ $user->id }}" style="color: black; text-decoration: none;">
                フォロー <strong>{{ $user->following->count() }}</strong>人
            </a>

            <!-- フォロワー数 -->
            <a href="#" id="show-followers" data-bs-toggle="modal" data-bs-target="#followersModal{{ $user->id }}" style="color: black; text-decoration: none;">
                フォロワー <strong>{{ $user->followers->count() }}</strong>人
            </a>

            <!-- 投稿数表示 -->
            <p>投稿数 <strong>{{ $user->posts->count() }}</strong>件</p>

        </div>

        <!-- フォローモーダルの読み込み -->
        @include('modals.show_follow')

        <!-- ユーザーのプロフィール -->
        <p>{{ $user->bio }}</p>
    </div>
</div>