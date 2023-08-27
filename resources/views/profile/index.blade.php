@extends('layouts.app')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-8 col-12">
        <div class="container">
            <div class="row">

                <!-- プロフィール情報 -->
                <div class="col-12 mb-4">
                    <div class="row">
                        @php
                        use Jenssegers\Agent\Agent;

                        $agent = new Agent();

                        $isMobile = $agent->isMobile();
                        @endphp

                        @if($isMobile)
                        <!-- モバイル用のプロフィール表示 -->
                        <div class="col-12 mb-4">
                            @include('components.mobile_profile', ['user' => $user])
                        </div>
                        @else
                        <!-- デスクトップ用のプロフィール表示 -->
                        <div class="col-12 mb-4">
                            @include('components.desktop_profile', ['user' => $user])
                        </div>
                        @endif
                    </div>
                </div>

                <!-- フラッシュメッセージ表示 -->
                @if (session('flash_message'))
                <p class="col-12">{{ session('flash_message') }}</p>
                @endif

                <!-- 投稿とラベルの選択部分 -->
                <div class="col-12 mb-3 p-0">
                    @include('components.label_selection', ['user' => $user, 'labels' => $labels])
                </div>

            </div>

            <!-- 投稿の表示部分 -->
            <div class="row justify-content-start">
                @foreach($posts as $post)

                @include('components.post_display', ['post' => $post])

                @endforeach
            </div>

        </div>
    </div>

</div>

<!-- JavaScript部分 -->
<script>
    var userId = "{{ $user->id }}"; // LaravelでユーザーIDを取得
</script>
<script src="{{ asset('js/follow_script.js') }}"></script>
<script>
    function getPostShowRoute(id) {
        return "{{ route('posts.show', '') }}/" + id;
    }
</script>
<script src="{{ asset('js/post_modal.js') }}"></script>

@endsection