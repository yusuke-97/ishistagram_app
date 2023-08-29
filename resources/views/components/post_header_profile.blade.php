<!-- 投稿のヘッダープロフィール -->
<div class="card-header">
    @if (Auth::id() === $post->user->id)
    <a href="{{ route('profile.default') }}" style="text-decoration: none; color: inherit;">
        @if($post->user->profile_image)
        <img class="card-header-profile" src="{{ Storage::disk('s3')->url('profile_images/' . $post->user->profile_image) }}" alt="User's Profile Image">
        @else
        <i class="fas fa-user fa-5x card-header-profile-icon"></i>
        @endif
    </a>
    @else
    <a href="{{ route('profile.show', ['profile' => $post->user->id]) }}" style="text-decoration: none; color: inherit;">
        @if($post->user->profile_image)
        <img class="card-header-profile" src="{{ Storage::disk('s3')->url('profile_images/' . $post->user->profile_image) }}" alt="User's Profile Image">
        @else
        <i class="fas fa-user fa-5x card-header-profile-icon"></i>
        @endif
    </a>
    @endif
    <span style="font-weight: bold;">{{ $post->user->user_name }}</span>
</div>