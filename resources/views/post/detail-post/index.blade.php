@extends('layout')

@section('title', $post->name ?? __('message.postDetail'))

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
                        <a href="">{{ __('message.blog') }}</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        @isset($post)
                            <a href="{{ url(App::getLocale() . '/blog' . '/' . $post->slug) }}" class="post-name">{{ $post->name }}</a>
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
                            <img src="{{route('get.file', ['filename' => $post->image_path]) }}"
                                class="card-img-top" alt="{{ $post->image_alt }}">
                            <div class="card-body">
                                <h1 class="card-title">{{ $post->name }}</h1>
                                <p class="text-muted">{{ __('message.published') }} <span id="publish-date">{{ $post->release->format('Y-m-d H:i:s') }}</span> {{ __('message.by') }} <span
                                        id="author-name">{{ $post->author }}</span></p>
                                <p class="card-text">
                                    {!! $post->content !!}
                                </p>
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
    </script>
@endpush
