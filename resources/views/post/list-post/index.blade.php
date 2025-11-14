@extends('layout')

@section('title', __('message.blog'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/list-post.css') }}">
@endpush

@section('content')
    <div class="list-post">
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
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5">
            @if (!$list_post->isEmpty())
                @foreach ($list_post->chunk(3) as $chunk)
                    <div class="row" id="product-list">
                        @foreach ($chunk as $post)
                            <div class="col-md-4 post-card">
                                <div class="card">
                                    <img src="{{route('get.file', ['filename' => $post->image_path])}}" class="card-img-top" alt="{{ $post->image_alt }}">
                                    <div class="card-body">
                                        <h5 class="post-title">{{ $post->name }}</h5>
                                        <div class="author"><i class="fas fa-user icon"></i>{{ $post->author }}</div>
                                        <div class="release"><i
                                                class="fa-solid fa-clock"></i>{{ $post->release->format('Y-m-d H:i:s') }}
                                        </div>
                                    </div>
                                    <a href="{{ url(App::getLocale() . '/blog' . '/' . $post->slug) }}" class="btn btn-primary">{{ __('message.viewDetailPost') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="empty-list">
                    <img src="{{ asset('images/empty-box-2.jpg') }}" alt="" srcset="">
                    <div class="text-center empty-text">{{ __('message.listIsEmpty') }}</div>
                </div>
            @endif


            <!-- Phân trang -->
            @if (!$list_post->isEmpty())
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        @if ($current_page != 1)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ url(App::getLocale() . '/product-post' . '?page=' . $current_page - 1) }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif
                        @for ($i = 1; $i <= $total_page; $i++)
                            @if ($current_page == $i)
                                <li class="page-item active"><a class="page-link"
                                        href="{{ url(App::getLocale() . '/product-post' . '?page=' . $i) }}">{{ $i }}</a>
                                </li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        href="{{ url(App::getLocale() . '/product-post' . '?page=' . $i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page != $total_page)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ url(App::getLocale() . '/product-post' . '?page=' . $current_page + 1) }}"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/list-post.js') }}"></script>
@endpush
