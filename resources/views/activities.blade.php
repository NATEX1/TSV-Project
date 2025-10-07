<x-layout>

    <!-- Bread crumbs -->
    <nav class="bread-crumbs">
        <div class="container">
            <ul class="bread-crumbs-list">
                <li><a href="{{ url('/') }}">{{ __('messages.home') }}</a><i
                        class="material-icons md-18">chevron_right</i></li>
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

                @foreach ($activities as $activity)
                    @php
                        $new = '';
                        if (\Carbon\Carbon::parse($activity->create_date)->isToday()) {
                            $new = '<img src="' . asset('images/new-stamp.png') . '" width="10%">';
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 item">
                        <article class="news-item item-style">
                            <a href="/content/{{ $activity->id }}" class="news-item-img">
                                <img src="{{ checkImageUrl('/storage/images/content/activities/' . $activity->cover_img) }}"
                                    alt="{{ $activity['title_' . $lang] }}" >
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
                                <p>{{ Str::limit(strip_tags($activity['content_'.$lang]), 100) }}</p>
                                <p><b>{{ __('messages.quota') }}:</b> {{ $activity->quota }}</p>



                                @if (session('user') && session('user.register_status'))
                                    @if (!$activity->joined)
                                        <form action="{{ route('activities.join') }}" method="POST"
                                            class="text-center">
                                            @csrf
                                            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                            <button type="submit" class="btn btn-primary">
                                                เข้าร่วมกิจกรรม
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-success text-center"><i class="bi bi-check-circle"></i>
                                            คุณได้สมัครเข้าร่วมกิจกรรมนี้แล้ว</p>
                                    @endif
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach

                {{-- Pagination --}}
                <div class="col-12">
                    <!-- Begin pagination -->
                    <nav class="pagination">
                        <ul class="pagination-list">
                            {{-- Prev --}}
                            @if ($page == 1)
                                <li class="pagination-item-arrow pagination-item-arrow-prev pagination-item-disabled">
                                    <a href="#!"><i class="material-icons md-24">chevron_left</i></a>
                                </li>
                            @else
                                <li class="pagination-item-arrow pagination-item-arrow-prev">
                                    <a href="{{ url('content?page=' . ($page - 1)) }}"><i
                                            class="material-icons md-24">chevron_left</i></a>
                                </li>
                            @endif

                            {{-- Page Numbers --}}
                            @for ($i = 1; $i <= $totalPages; $i++)
                                <li class="{{ $i == $page ? 'active' : '' }}">
                                    <a
                                        href="{{ $i == $page ? '#!' : url('content?page=' . $i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Next --}}
                            @if ($page == $totalPages)
                                <li class="pagination-item-arrow pagination-item-arrow-next pagination-item-disabled">
                                    <a href="#!"><i class="material-icons md-24">chevron_right</i></a>
                                </li>
                            @else
                                <li class="pagination-item-arrow pagination-item-arrow-next">
                                    <a href="{{ url('content?page=' . ($page + 1)) }}"><i
                                            class="material-icons md-24">chevron_right</i></a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                    <!-- End pagination -->
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.10/index.global.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.js"></script>
    @if ($lang == 'th')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/lang/th.js"></script>
    @endif

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
                eventContent: function(arg) {
                    let title = arg.event.title;
                    let time = arg.timeText ? `<span class="fc-event-time">${arg.timeText}</span>` : '';

                    let html = `
                        <div class="fc-event-custom p-1 rounded" style="border: 1px solid #dee2e6; cursor: pointer;">
                            <div class="fc-event-title fw-bold ">${title}</div>
                            ${time ? `<div class="fc-event-time small">${time}</div>` : ''}
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
