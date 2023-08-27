@extends('layouts.app')

<!-- スクリプトの追加 -->
@push('scripts')
<script src="{{ asset('js/comment_script.js') }}"></script>
<script src="{{ asset('js/like_script.js') }}"></script>
<script src="{{ asset('js/carousel_control.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <!-- 各投稿をループで表示 -->
            @foreach($posts as $post)
            <script>
                window.postCarouselId = 'postImagesCarousel{{ $post->id }}';
                var postId = "{{ $post->id }}";
            </script>

            <div class="card mb-4 shadow-sm">

                <!-- 投稿ヘッダーのユーザープロフィールセクション -->
                @include('components.post_header_profile', ['post' => $post])

                <div class="card-body p-0">

                    <!-- 投稿の画像カルーセルセクション -->
                    @include('components.post_image_carousel', ['post' => $post])

                    @php
                    $like = $post->likes->where('user_id', Auth::id())->first();
                    @endphp
                    <div style="padding-left: 16px; padding-right: 16px">
                        <!-- アクションボタンセクション (いいね & コメント) -->
                        @include('components.action_buttons', ['post' => $post, 'like' => $like])

                        <!-- 投稿日時を表示 -->
                        <p class="modal-posted-date" style="margin-bottom: 0px;">{{ $post->created_at->format('Y年m月d日 H:i') }}</p>

                        <!-- 投稿文とユーザー名を表示 -->
                        <div class="mb-2">
                            <strong style="font-weight: bold;">{{ $post->user->user_name }}</strong>
                            <span>{!! $post->content !!}</span>
                        </div>

                        <!-- コメントに関するセクション -->
                        @include('components.post_comment', ['post' => $post])
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection