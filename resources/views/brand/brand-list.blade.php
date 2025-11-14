<div class="brand-list">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12">
                @if (count($categories) > 0)
                    @foreach ($categories->chunk(6) as $chunk)
                        <div class="d-flex flex-md-nowrap flex-wrap list">
                            @foreach ($chunk as $category)
                                @include('component.brand.brand-item', [
                                    'category' => $category,
                                ])
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <div class="empty-list">
                        <img src="{{ asset('images/empty-box-2.jpg') }}" alt="" srcset="">
                        <div class="text-center empty-text">{{ __('message.listIsEmpty') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
