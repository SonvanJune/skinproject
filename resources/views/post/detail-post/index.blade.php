@php
    $postImage = !empty($post->image_path) ? route('get.file', ['filename' => $post->image_path]) : asset('images/logo.png');
    $cleanDesc = !empty($post->content)
        ? \Illuminate\Support\Str::limit(strip_tags($post->content), 160)
        : ($post->name ?? 'PPFTEMPLATE Blog');
@endphp

@section('title', ($post->name ?? __('message.postDetail')) . ' | PPFTEMPLATE Blog')
@section('meta_title', ($post->name ?? __('message.postDetail')) . ' | PPFTEMPLATE Blog')
@section('meta_description', $cleanDesc)
@section('og_image', $postImage)
@section('og_type', 'article')

@isset($post)
@push('schema')
    @include('component.schema.index', [
        'type' => 'article',
        'post' => $post,
        'image' => $postImage,
        'description' => $cleanDesc,
        'breadcrumbs' => [
            ['name' => __('message.home'), 'url' => url(App::getLocale())],
            ['name' => __('message.blog'), 'url' => url(App::getLocale() . '/blog')],
            ['name' => $post->name, 'url' => url()->current()],
        ]
    ])
@endpush
@endisset

@push('css')
    <link rel="stylesheet" href="{{ asset('css/post-detail.css') }}">
@endpush

@section('content')
    <div class="post-detail">
        <div class="title">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <h4>{{ __('message.blog') }}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12 path">
                        <a href="/"><i class="fa-solid fa-house"></i></a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{ url(App::getLocale() . '/blog') }}">{{ __('message.blog') }}</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        @isset($post)
                            <a href="{{ url(App::getLocale() . '/blog' . '/' . $post->slug) }}"
                                class="post-name">{{ $post->name }}</a>
                        @else
                            <a href="">{{ __('message.postDetail') }}</a>
                        @endisset
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-5">
            <div class="row">
                <div class="col-md-8">
                    @isset($post)
                        <div class="card mb-4 shadow-sm">
                            <img src="{{ route('get.file', ['filename' => $post->image_path]) }}" class="card-img-top"
                                alt="{{ $post->image_alt }}" loading="lazy">
                            <div class="card-body">
                                <h1 class="card-title">{{ $post->name }}</h1>
                                <p class="text-muted">{{ __('message.published') }} <span
                                        id="publish-date">{{ $post->release->format('Y-m-d H:i:s') }}</span>
                                    {{ __('message.by') }} <span id="author-name">{{ $post->author }}</span></p>
                                <div id="post-container" class="card-text post-body-content">
                                    {!! $post->content !!}
                                </div>
                                <noscript>
                                    <div class="post-body-fallback">
                                        {!! $post->content !!}
                                    </div>
                                </noscript>
                            </div>
                        </div>
                    @else
                        <p class="text-center align-item-center">{{ __('message.noPost') }}</p>
                    @endisset
                    {{-- <div class="comments-section">
                        <h3>Bình luận (<span id="comments-count">0</span>)</h3>
                        <div id="comments-container"></div>

                        <form id="comment-form" class="mt-4">
                            <div class="form-group">
                                <label for="comment">Thêm Bình luận:</label>
                                <textarea class="form-control" id="comment" name="content" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success mt-2">Gửi</button>
                        </form>
                    </div> --}}
                </div>

                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('message.relatedPost') }}</h5>
                            {{-- <ul class="list-group" id="related-posts">
                                <li class="list-group-item">Bài viết 1</li>
                                <li class="list-group-item">Bài viết 2</li>
                                <li class="list-group-item">Bài viết 3</li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commentForm = document.getElementById('comment-form');
            const commentsContainer = document.getElementById('comments-container');
            const commentsCount = document.getElementById('comments-count');

            commentForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const commentInput = document.getElementById('comment');
                const newComment = document.createElement('div');
                newComment.classList.add('card', 'mb-2');
                newComment.innerHTML = `
                <div class="card-body">
                    <h5 class="card-title">Người dùng</h5>
                    <p class="card-text">${commentInput.value}</p>
                    <p class="text-muted">${new Date().toLocaleString()}</p>
                </div>
            `;
                commentsContainer.appendChild(newComment);

                // Cập nhật số lượng bình luận
                commentsCount.textContent = parseInt(commentsCount.textContent) + 1;

                // Xóa nội dung ô nhập
                commentInput.value = '';
            });
        });
        const rawHtml = @json($post->content);
        renderSmartContent(rawHtml, "post-container");
    </script>
@endpush
