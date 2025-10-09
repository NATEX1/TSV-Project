<x-layout>
    <!-- Begin bread crumbs -->
    <nav class="bread-crumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="bread-crumbs-list">
                        <li>
                            <a href="{{ url('/') }}">{{ __('messages.home') }}</a>
                            <i class="material-icons md-18">chevron_right</i>
                        </li>
                        <li><a href="#!">{{ __('messages.volunteers') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav><!-- End bread crumbs -->

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">{{ __('messages.details') }}</div>
                        <h1>{{ __('messages.search_volunteers') }}</h1>
                    </div>
                </div>

                <form action="{{ route('search') }}" method="GET" id="kt_sign_up_form">
                    <div class="row gutters-default">
                        <div class="col-xl-6 col-sm-6 col-12">
                            <div class="form-field">
                                <label for="contact-name">{{ __('messages.name') }}</label>
                                <input type="text" placeholder="{{ __('messages.search_name_placeholder') }}"
                                    class="form-field-input" name="name" value="{{ $name ?? '' }}"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 col-12">
                            <div class="form-field">
                                <label for="contact-province">{{ __('messages.province') }}</label>
                                <select name="province" class="form-select" style="height:50px">
                                    <option value="" {{ empty($province) ? 'selected' : '' }}>
                                        {{ __('messages.select_province') }}
                                    </option>
                                    @foreach ($provinces as $prov)
                                        <option value="{{ $prov->id }}"
                                            {{ $province == $prov->id ? 'selected' : '' }}>
                                            {{ $prov['name_' . app()->getLocale()] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <h4>{{ __('messages.expertise') }}</h4>
                    <div class="accordion">
                        <div class="row gutters-default">
                            <div class="col-lg-12 col-12">
                                <ul class="accordion-list">
                                    @foreach ($skillLv1 as $lv1)
                                        <li class="accordion-item section-bgc">
                                            <div class="accordion-trigger">{{ $lv1['name_' . $lang] }}</div>
                                            <div class="accordion-content content">
                                                @foreach ($skillLv2 as $lv2)
                                                    @if ($lv2['lv1_id'] == $lv1['id'])
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="option[]" value="{{ $lv2['id'] }}"
                                                                {{ in_array($lv2['id'], $skills ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label">{{ $lv2['name_' . $lang] }}</label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="section-btns justify-content-center mt-4">
                        <button type="submit" class="btn btn-with-icon btn-w240 ripple">
                            <span>{{ __('messages.search') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section id="services" class="section section-bgc">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">{{ __('messages.volunteers') }}</div>
                        <h2>{{ __('messages.volunteers') }}</h2>
                    </div>
                </div>
                @forelse ($users as $user)
                    @php
                        $img = empty($user->user_img)
                            ? '/assets/backoffice/assets/media/avatars/blank.png'
                            : '/storage/images/user/' . $user->user_img;
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 item">
                        <a href="{{ url('profile/' . $user->id) }}" target="_blank" style="text-decoration: none;">
                            <div class="reviews-item item-style">
                                <div class="reviews-item-header">
                                    <div class="reviews-item-img">
                                        <img src="{{ checkImageUrl($img) }}"
                                            data-src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                            alt="{{ __('messages.profile_picture') }}">
                                    </div>
                                    <div class="reviews-item-info">
                                        <h4 class="reviews-item-name item-heading">
                                            {{ $user['fname_' . $lang] }}&nbsp;&nbsp;{{ $user['lname_' . $lang] }}
                                        </h4>
                                        @if (!empty($user->tsv_id))
                                            <i class="material-icons" style="font-size:16px; color:#555;">badge_outline</i>
                                            <span>{{ $user->tsv_id }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>{{ __('messages.no_users_found') }}</p>
                    </div>
                @endforelse

                @if ($paginator->hasPages())
                    <div class="col-12">
                        <nav class="pagination d-flex justify-content-center mt-4">
                            <ul class="pagination-list">
                                <!-- Previous Page -->
                                <li class="{{ $paginator->onFirstPage() ? 'disabled' : '' }}">
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
                                        <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                                    </li>
                                @endif

                                <!-- Next Page -->
                                <li class="{{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                                    <a href="{{ $paginator->nextPageUrl() }}" 
                                       aria-label="{{ __('messages.next') }}">
                                        <i class="material-icons md-24">chevron_right</i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>