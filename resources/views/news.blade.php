<x-layout>
    <!-- Begin bread crumbs -->
    <nav class="bread-crumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="bread-crumbs-list">
                        <li>
                            <a href="/home">{{ __('messages.home') }}</a>
                            <i class="material-icons md-18">chevron_right</i>
                        </li>
                        <li><a href="#!">{{ __('messages.news') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav><!-- End bread crumbs -->

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">{{ __('messages.stay_updated') }}</div>
                        <h1>{{ __('messages.news') }}</h1>
                    </div>
                </div>

                @foreach ($news as $item)
                    @php
                        $new = '';
                        if (\Carbon\Carbon::parse($item->create_date)->isToday()) {
                            $new = '<img src="' . checkImageUrl('/storage/images/new-stamp.png') . '" width="10%">';
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 item">
                        <article class="news-item item-style">
                            <a href="/content/{{ $item->id }}" class="news-item-img">
                                <img src="{{ checkImageUrl('/storage/images/content/news/' . $item->cover_img) }}"
                                    alt="{{ $item['title_' . $lang] }}">
                            </a>
                            <div class="news-item-info">
                                <div class="news-item-date">
                                    {{ __('messages.posted_date') }}
                                    {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/') }}
                                    {{ \Carbon\Carbon::parse($item->start_date)->year + 543 }}
                                </div>
                                <h2 class="news-item-heading item-heading">
                                    <a href="/content/{{ $item->id }}">
                                        {!! $item['title_' . $lang] !!}
                                    </a>
                                    {!! $new !!}
                                </h2>
                                <p>{{ Str::limit(strip_tags($item['content_' . $lang]), 100) }}</p>
                            </div>
                        </article>
                    </div>
                @endforeach

                {{-- Pagination --}}
                <div class="col-12">
                    <!-- Begin pagination -->
                    <nav class="pagination">
                        @if ($news->hasPages())
                            <ul class="pagination-list">
                                {{-- Previous Page Link --}}
                                @if ($news->onFirstPage())
                                    <li
                                        class="pagination-item-arrow pagination-item-arrow-prev pagination-item-disabled">
                                        <a href="#!"><i class="material-icons md-24">chevron_left</i></a>
                                    </li>
                                @else
                                    <li class="pagination-item-arrow pagination-item-arrow-prev">
                                        <a href="{{ $news->previousPageUrl() }}"><i
                                                class="material-icons md-24">chevron_left</i></a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @for ($i = 1; $i <= $news->lastPage(); $i++)
                                    <li class="{{ $i == $news->currentPage() ? 'active' : '' }}">
                                        <a
                                            href="{{ $i == $news->currentPage() ? '#!' : $news->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($news->hasMorePages())
                                    <li class="pagination-item-arrow pagination-item-arrow-next">
                                        <a href="{{ $news->nextPageUrl() }}"><i
                                                class="material-icons md-24">chevron_right</i></a>
                                    </li>
                                @else
                                    <li
                                        class="pagination-item-arrow pagination-item-arrow-next pagination-item-disabled">
                                        <a href="#!"><i class="material-icons md-24">chevron_right</i></a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </nav>
                    <!-- End pagination -->
                </div>
            </div>
        </div>
    </div>
</x-layout>
