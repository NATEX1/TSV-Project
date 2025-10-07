<x-layout>

    <!-- Begin intro -->
    <!-- Swiper Container -->
    <div class="swiper bannerSwiper">
        <div class="swiper-wrapper">
            @foreach ($banners as $index => $banner)
                <div class="swiper-slide">
                    <img src="{{ checkImageUrl('storage/images/banners/' . $banner->banner_img) }}" class="banner-img"
                        alt="Banner {{ $index + 1 }}">

                    <div class="container">
                        <div class="caption">
                            <div class="caption-content">
                                <p class="section-desc" style="font-size: 14px">{!! $banner['text_' . $lang] !!}</p>
                                @if ($banner->path)
                                    <a href="{{ $banner->path }}" class="btn btn-with-icon btn-small ripple">
                                        <span>{{ $banner['text_button_' . $lang] }}</span>
                                        <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                            <use xlink:href="{{ asset('assets/img/sprite.svg#arrow-right') }}">
                                            </use>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- End intro -->

    <!-- Begin activities -->
    <section class="section services">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">{{ __('messages.volunteers') }}</div>
                        <h2>{{ __('messages.join_activities') }}</h2>
                    </div>
                </div>
                @foreach ($activities as $activity)
                    <div class="col-lg-4 col-md-6 col-12 item">
                        <article class="news-item item-style">
                            <a href="/content/{{ $activity->id }}" class="news-item-img">
                                <img src="{{ checkImageUrl('/storage/images/content/activities/' . $activity->cover_img) }}"
                                    alt="">
                            </a>
                            <div class="news-item-info">
                                <div class="news-item-date">
                                    {{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }}
                                </div>
                                <h4 class="news-item-heading item-heading">
                                    <a href="/content/{{ $activity->id }}" title="{{ $activity['title_' . $lang] }}">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($activity['title_' . $lang]), 50) }}
                                    </a>
                                </h4>
                                <div class="news-item-desc">
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($activity['content_' . $lang]), 90) }}...
                                    </p>
                                    <p><b>{{ __('messages.quota') }}</b> : {{ $activity->quota }}</p>

                                    {{-- @php
                                        $today = \Carbon\Carbon::now();
                                        $stopDate = \Carbon\Carbon::parse($activity->stop_date);
                                        $userId = session('user.id') ?? null;

                                        $volunteerList = isset($activity->volunteer_list)
                                            ? iterator_to_array($activity->volunteer_list)
                                            : [];

                                        $registerStatus = session('user.register_status');

                                        $hasJoined = $userId && in_array($userId, $volunteerList);
                                    @endphp

                                    @if (session('user') && $registerStatus == 4)
                                        @if (!$hasJoined)
                                            <form action="{{ route('activities.join') }}" method="POST"
                                                class="text-center">
                                                @csrf
                                                <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                                <button type="submit" class="btn btn-primary">
                                                    เข้าร่วมกิจกรรม
                                                </button>
                                            </form>
                                        @else
                                            <p class="text-success text-center">คุณได้สมัครเข้าร่วมกิจกรรมนี้แล้ว</p>
                                        @endif
                                    @endif --}}
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach

                <div class="col-12">
                    <div class="section-btns justify-content-center">
                        <a href="/activities" class="btn btn-with-icon btn-w240 ripple">
                            <span>{{ __('messages.see_all_activities') }}</span>
                            <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                <use xlink:href="{{ asset('/assets/img/sprite.svg#arrow-right') }}"></use>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End activities -->

    <!-- Begin steps -->
    <section class="section section-bgc">
        <div class="container">
            <div class="row items">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">{{ __('messages.details') }}</div>
                        <h2>{{ __('messages.steps_to_tsv') }}</h2>
                    </div>
                </div>
                @foreach ($steps as $step)
                    <div class="col-lg-4 col-md-6 col-12 item text-center">
                        <div class="ini">
                            <div class="ini-count">{{ str_pad($step->orders, 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="ini-info">
                                <h3 class="ini-heading item-heading-large">{{ $step['content_' . $lang] }}</h3>
                                <div class="ini-desc">
                                    @if ($step->cover_img)
                                        <img src="{{ checkImageUrl('/storage/images/steps/' . $step->cover_img) }}"
                                            alt="Step image">
                                    @else
                                        <p>{!! $step->icon !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End steps -->

    <!-- Begin users -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">{{ __('messages.details') }}</div>
                        <h2>{{ __('messages.tsv_registration_steps') }}</h2>
                    </div>
                </div>
                @foreach ($users as $user)
                    @php
                        $img = $user->user_img
                            ? '/storage/images/user/' . $user->user_img
                            : '/assets/media/avatars/blank.png';
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 item">
                        <a href="/profile/{{ $user->id }}" target="_blank" style="text-decoration: none;">
                            <div class="reviews-item item-style">
                                <div class="reviews-item-header">
                                    <div class="reviews-item-img">
                                        <img data-src="{{ checkImageUrl($img) }}" 
                                            src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
                                            alt="">
                                    </div>
                                    <div class="reviews-item-info">
                                        <h4 class="reviews-item-name item-heading">
                                            {{ $user['fname_' . $lang] }} {{ $user['lname_' . $lang] }}
                                        </h4>

                                        <div class="reviews-item-position" style="width: 12em; word-wrap: break-word;">
                                            <i class="material-icons"
                                                style="font-size:16px; color:#555;">badge_outline</i>
                                            <span>{{ $user->tsv_id ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End users -->

    <!-- Begin news -->
    <section class="section section-bgc">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">{{ __('messages.stay_updated') }}</div>
                        <h2>{{ __('messages.news') }}</h2>
                    </div>
                </div>
                @foreach ($news as $item)
                    <div class="col-lg-6 col-md-6 col-12 item">
                        <article class="news-item item-style">
                            <a href="/content/{{ $item->id }}" class="news-item-img">
                                <img data-src="{{ checkImageUrl('/storage/images/content/news/' . $item->cover_img) }}"
                                        alt="">
                            </a>
                            <div class="news-item-info">
                                <div class="news-item-date">
                                    {{ \Carbon\Carbon::parse($item->create_date)->format('d/m/Y') }}
                                </div>
                                <h3 class="news-item-heading item-heading">
                                    <a href="/content/{{ $item->id }}" title="{{ $item['title_' . $lang] }}">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item['title_' . $lang]), 50) }}
                                    </a>
                                </h3>
                                <div class="news-item-desc">
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($item['content_' . $lang]), 350) }}...
                                    </p>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach

                <div class="col-12">
                    <div class="section-btns justify-content-center">
                        <a href="/news" class="btn btn-with-icon btn-w240 ripple">
                            <span>{{ __('messages.see_all_news') }}</span>
                            <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                <use xlink:href="{{ asset('/assets/img/sprite.svg#arrow-right') }}"></use>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End news -->

</x-layout>
