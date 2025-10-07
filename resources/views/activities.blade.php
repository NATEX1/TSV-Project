<x-layout>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'สำเร็จ!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    <!-- Bread crumbs -->
    <nav class="bread-crumbs">
        <div class="container">
            <ul class="bread-crumbs-list">
                <li>
                    <a href="{{ url('/') }}">{{ __('messages.home') }}</a>
                    <i class="material-icons md-18">chevron_right</i>
                </li>
                <li><a href="#!">{{ __('messages.activities') }}</a></li>
            </ul>
        </div>
    </nav>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1>{{ __('messages.join_activities') }}</h1>
                    <p class="section-subheading">{{ __('messages.volunteers') }}</p>
                </div>
                <div>
                    <div id='calendar'></div>
                    <br><br>
                </div>

                @forelse ($activities as $activity)
                    @php
                        $new = '';
                        if (\Carbon\Carbon::parse($activity->create_date)->isToday()) {
                            $new =
                                '<img src="' .
                                asset('images/new-stamp.png') .
                                '" width="10%" alt="' .
                                __('messages.new') .
                                '">';
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 item">
                        <article class="news-item item-style">
                            <a href="/content/{{ $activity->id }}" class="news-item-img">
                                <img src="{{ checkImageUrl('/storage/images/content/activities/' . $activity->cover_img) }}"
                                    alt="{{ $activity['title_' . $lang] }}">
                            </a>
                            <div class="news-item-info">
                                <div class="news-item-date">
                                    {{ __('messages.start') }}
                                    {{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/') }}
                                    {{ $lang === 'th' ? \Carbon\Carbon::parse($activity->start_date)->year + 543 : \Carbon\Carbon::parse($activity->start_date)->year }}
                                </div>
                                <h2 class="news-item-heading item-heading">
                                    <a href="/content/{{ $activity->id }}">
                                        {!! $activity['title_' . $lang] !!}
                                    </a>
                                    {!! $new !!}
                                </h2>
                                <p>{{ Str::limit(strip_tags($activity['content_' . $lang]), 100) }}</p>
                                <p><b>{{ __('messages.quota') }}:</b> {{ $activity->quota }}</p>

                                @if (session('user') && session('user.register_status'))
                                    @if (!$activity->joined)
                                        <form action="{{ route('activities.join') }}" method="POST"
                                            class="text-center">
                                            @csrf
                                            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('messages.join_activity') }}
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-success text-center">
                                            <i class="bi bi-check-circle"></i>
                                            {{ __('messages.already_joined') }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>{{ __('messages.no_activities_found') }}</p>
                    </div>
                @endforelse

                @if ($paginator->hasPages())
                    <div class="col-12">
                        <nav class="pagination d-flex justify-content-center mt-4">
                            <ul class="pagination-list">
                                <!-- Previous Page -->
                                <li
                                    class="pagination-item-arrow pagination-item-arrow-prev {{ $paginator->onFirstPage() ? 'pagination-item-disabled' : '' }}">
                                    <a href="{{ $paginator->previousPageUrl() }}"
                                        aria-label="{{ __('messages.previous') }}">
                                        <i class="material-icons md-24">chevron_left</i>
                                    </a>
                                </li>

                                <!-- First Page -->
                                @if ($paginator->currentPage() > 3)
                                    <li>
                                        <a href="{{ $paginator->url(1) }}">1</a>
                                    </li>
                                    @if ($paginator->currentPage() > 4)
                                        <li class="pagination-ellipsis">
                                            <span>{{ __('messages.ellipsis') }}</span>
                                        </li>
                                    @endif
                                @endif

                                <!-- Page Numbers -->
                                @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                                    <li class="{{ $paginator->currentPage() == $page ? 'active' : '' }}">
                                        <a href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                <!-- Last Page -->
                                @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                                    @if ($paginator->currentPage() < $paginator->lastPage() - 3)
                                        <li class="pagination-ellipsis">
                                            <span>{{ __('messages.ellipsis') }}</span>
                                        </li>
                                    @endif
                                    <li>
                                        <a
                                            href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                                    </li>
                                @endif

                                <!-- Next Page -->
                                <li
                                    class="pagination-item-arrow pagination-item-arrow-next {{ $paginator->hasMorePages() ? '' : 'pagination-item-disabled' }}">
                                    <a href="{{ $paginator->nextPageUrl() }}" aria-label="{{ __('messages.next') }}">
                                        <i class="material-icons md-24">chevron_right</i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.10/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: @json($events),
                locale: @json($lang),
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                dayMaxEvents: 3,
                buttonText: {
                    prev: '{{ __('messages.previous') }}',
                    next: '{{ __('messages.next') }}',
                    today: '{{ __('messages.today') }}',
                    month: '{{ __('messages.month') }}',
                    week: '{{ __('messages.week') }}',
                    day: '{{ __('messages.day') }}'
                },
                eventContent: function(arg) {
                    let title = arg.event.title;
                    let time = arg.timeText ?
                        `<span class="fc-event-time">{{ __('messages.time') }}: ${arg.timeText}</span>` :
                        '';
                    let html = `
                        <div class="fc-event-custom p-1 rounded d-flex align-items-center gap-1" style="cursor: pointer;">
                            <div class="fc-event-title fw-bold">${title}</div>${time ? ` - <div class="fc-event-time small">${time}</div>` : ''}
                        </div>
                    `;
                    return {
                        html: html
                    };
                },
                eventClick: function(arg) { 
                    arg.jsEvent.preventDefault();
                    if (arg.event.id) {
                        window.open("/content/" + arg.event.id, "_blank");
                    }
                },
            });
            calendar.render();
        });
    </script>
</x-layout>
