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

    <!-- Begin bread crumbs -->
    <nav class="bread-crumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="bread-crumbs-list">
                        <li>
                            <a href="{{ url('/home') }}">หน้าแรก</a>
                            <i class="material-icons md-18">chevron_right</i>
                        </li>

                        @if ($content->menusub_id == 3)
                            <li>
                                <a href="{{ url('/news') }}">ข่าวสาร</a>
                                <i class="material-icons md-18">chevron_right</i>
                            </li>
                        @elseif ($content->menusub_id == 4)
                            <li>
                                <a href="{{ url('/activities') }}">กิจกรรม</a>
                                <i class="material-icons md-18">chevron_right</i>
                            </li>
                        @endif

                        <li>
                            <a href="#!">{{ $content['title_' . $lang] }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav><!-- End bread crumbs -->

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="news-post">
                        <header class="news-post-header">
                            <h1 class="news-post-title">{{ $content['title_' . $lang] }}</h1>
                            <div class="news-post-meta">
                                <div class="news-post-meta-item">
                                    <i class="material-icons md-22">access_time</i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($content->create_date)->format('d/m/') }}
                                        {{ \Carbon\Carbon::parse($content->create_date)->year + 543 }}
                                        {{ \Carbon\Carbon::parse($content->create_date)->format('H:i') }} น.
                                    </span>
                                </div>
                                <div class="news-post-meta-item">
                                    <i class="material-icons md-20">visibility</i>
                                    <span>{{ $content->view }}</span>
                                </div>
                            </div>
                            @php
                            $folder = $content->menusub_id == 3 ? 'news' : 'activities'
                            @endphp
                            <div class="news-post-img img-style">
                                <img src="{{ checkImageUrl('/storage/images/content/'. $folder . '/' . $content->cover_img) }}"
                                    alt="{{ $content['title_' . $lang] }}" >
                            </div>
                        </header>

                        <article class="news-post-article content">
                            {!! $content['content_' . $lang] !!}
                            <br><br>
                            <b>{{ __('messages.quota') }}: {{ $content->quota }}</b>
                            <br>

                            {{-- @auth
                                <br>
                                <div class="col-12 text-center">
                                    <button type="button" class="btn btn-wide ripple" onclick="register()">
                                        <span>{{ __('messages.join_activities') }}</span>
                                    </button>
                                </div>
                            @endauth --}}

                            @php
                                $today = \Carbon\Carbon::now();
                                $stopDate = \Carbon\Carbon::parse($content->stop_date);
                                $userId = session('user.id') ?? null;

                                $registerStatus = session('user.register_status');
                            @endphp

                            @if (session('user') && $registerStatus->id == 4 && $content->menusub_id == 4)
                                @if (!$content->joined)
                                    <form action="{{ route('activities.join') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="activity_id" value="{{ $content->id }}">
                                        <button type="submit" class="btn btn-primary">
                                            เข้าร่วมกิจกรรม
                                        </button>
                                    </form>
                                @else
                                    <p class="text-success"><i class="bi bi-check-circle"></i> คุณได้สมัครเข้าร่วมกิจกรรมนี้แล้ว</p>
                                @endif
                            @endif


                        </article>

                        <script>
                            function register() {
                                alert("สมัครเข้าร่วมกิจกรรมแล้ว");
                            }
                        </script>

                        <footer class="news-post-footer">
                            <div class="row align-items-center justify-content-between items">
                                <div class="col-md col-12 item">
                                    <ul class="news-post-cat">
                                        <li>
                                            <a href="https://www.mots.go.th/" target="_blank"
                                                style="text-decoration: none;color:#000000;">
                                                {{ __('messages.posted_by') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-auto col-12 item">
                                    <div class="news-post-share">
                                        <p class="news-post-share-title">{{ __('messages.share') }}: </p>
                                        <ul class="page-social-links">
                                            <li>
                                                <a href="#!" title="Facebook">
                                                    <svg viewBox="0 0 320 512">
                                                        <use
                                                            xlink:href="{{ asset('assets/img/sprite.svg#facebook-icon') }}">
                                                        </use>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#!" title="Twitter">
                                                    <svg viewBox="0 0 512 512">
                                                        <use
                                                            xlink:href="{{ asset('assets/img/sprite.svg#twitter-icon') }}">
                                                        </use>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </footer>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
