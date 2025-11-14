@if (count($paginatedDTO->data) > 0)
    <!-- Pagination -->
    <div class="dataTables_paginate paging_simple_numbers">
        <ul class="pagination">

            {{-- Previous button --}}
            @if ($paginatedDTO->current_page > 1)
                <li class="paginate_item page-item">
                    <a class="paginate_button previous page-link"
                       href="{{ url(App::getLocale() . '/admin/' . $subject . '?page=' . ($paginatedDTO->current_page - 1) . '&per_page=' . $paginatedDTO->per_page . '&key=' . $paginatedDTO->key) }}">
                        <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                    </a>
                </li>
            @endif

            {{-- Page numbers --}}
            @php
                $totalPages = $paginatedDTO->last_page;
                $currentPage = $paginatedDTO->current_page;
                $pageLinks = [];

                // Always show first 3
                for ($i = 1; $i <= min(3, $totalPages); $i++) {
                    $pageLinks[] = $i;
                }

                // Add middle range (current page -1, current page, current page +1)
                for ($i = max(4, $currentPage - 1); $i <= min($totalPages - 3, $currentPage + 1); $i++) {
                    $pageLinks[] = $i;
                }

                // Always show last 3
                for ($i = max($totalPages - 2, 4); $i <= $totalPages; $i++) {
                    $pageLinks[] = $i;
                }

                // Remove duplicates and sort
                $pageLinks = array_unique($pageLinks);
                sort($pageLinks);

                $lastPage = 0;
            @endphp

            @foreach ($pageLinks as $page)
                @if ($lastPage + 1 < $page)
                    <li class="paginate_item page-item disabled"><span class="page-link">…</span></li>
                @endif
                <li class="paginate_item page-item {{ $currentPage == $page ? 'active' : '' }}">
                    <a class="paginate_button page-link"
                       href="{{ url(App::getLocale() . '/admin/' . $subject . '?page=' . $page . '&per_page=' . $paginatedDTO->per_page . '&key=' . $paginatedDTO->key) }}">
                        {{ $page }}
                    </a>
                </li>
                @php $lastPage = $page; @endphp
            @endforeach

            {{-- Next button --}}
            @if ($paginatedDTO->current_page < $paginatedDTO->last_page)
                <li class="paginate_item page-item">
                    <a class="paginate_button next page-link"
                       href="{{ url(App::getLocale() . '/admin/' . $subject . '?page=' . ($paginatedDTO->current_page + 1) . '&per_page=' . $paginatedDTO->per_page . '&key=' . $paginatedDTO->key) }}">
                        <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif

