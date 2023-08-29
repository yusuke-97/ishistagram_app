<!-- 投稿のヘッダープロフィール -->
<div class="card-header">
    <a href="{{ route('profile.default') }}" style="text-decoration: none; color: inherit;">
        @if(Auth::user()->profile_image)
        <img class="card-header-profile" src="{{ Storage::disk('s3')->url('profile_images/' . $post->user->profile_image) }}" alt="User's Profile Image">
        @else
        <i class="fas fa-user fa-5x card-header-profile-icon"></i>
        @endif
        <span style="font-weight: bold;">{{ Auth::user()->user_name }}</span>
    </a>
</div>