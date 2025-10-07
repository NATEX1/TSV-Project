<div>
    <!-- Begin mobile main menu -->
    <nav class="mmm">
        <div class="mmm-content">
            <ul class="mmm-list">
                <li><a href="/home"><span>{{ __('messages.home') }}</span></a></li>
                <li><a href="/about-us"><span>{{ __('messages.about_us') }}</span></a></li>
                <li><a href="/activities"><span>{{ __('messages.our_activities') }}</span></a></li>
                <li><a href="/search"><span>{{ __('messages.find_our_volunteers') }}</span></a></li>
                <li><a href="/courses" target="_blank"><span>{{ __('messages.training_courses') }}</span></a></li>
                <li><a href="/news"><span>{{ __('messages.news') }}</span></a></li>
                <li><a href="/contact-us"><span>{{ __('messages.contact_us') }}</span></a></li>
            </ul>
        </div>
        <div class="mmm-footer">
            <hr>
            <ul class="mmm-lang">
                <li class="active"><a href="index"><span>{{ __('messages.english') }}</span></a></li>
                <li><a href="../th/home"><span>{{ __('messages.thai') }}</span></a></li>
            </ul>
        </div>
    </nav>
    <!-- End mobile main menu -->

    <header class="header">
        <nav class="header-top">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <ul class="header-top-info">
                            <li>
                                <a href="mailto:{{ $contact->email }}">
                                    <i class="material-icons md-18">mail_outline</i>
                                    {{ $contact->email }}
                                </a>
                            </li>
                            <li>
                                <a href="tel:{{ $contact->phone }}">
                                    <i class="material-icons md-18">phone_in_talk</i>
                                    {{ $contact->phone }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <div class="heaer-top-links">
                            <ul class="social-links">
                                <li>
                                    <a href="tel:{{ $contact->facebook }}" title="Facebook">
                                        <svg viewBox="0 0 320 512">
                                            <use xlink:href="{{ asset('/assets/img/sprite.svg#facebook-icon') }}">
                                            </use>
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:{{ $contact->ig }}" title="Instagram">
                                        <svg viewBox="0 0 448 512">
                                            <use xlink:href="{{ asset('/assets/img/sprite.svg#instagram-icon') }}">
                                            </use>
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:{{ $contact->line }}" title="LinkedIn">
                                        <svg viewBox="0 0 448 512">
                                            <use xlink:href="{{ asset('/assets/img/sprite.svg#linkedin-icon') }}">
                                            </use>
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:{{ $contact->twitter }}" title="Twitter">
                                        <svg viewBox="0 0 512 512">
                                            <use xlink:href="{{ asset('/assets/img/sprite.svg#twitter-icon') }}"></use>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <nav class="header-fixed">
            <div class="container">
                <div class="row flex-nowrap align-items-center justify-content-between">
                    <div class="col-auto d-block d-lg-none header-fixed-col">
                        <div class="main-mnu-btn">
                            <span class="bar bar-1"></span>
                            <span class="bar bar-2"></span>
                            <span class="bar bar-3"></span>
                            <span class="bar bar-4"></span>
                        </div>
                    </div>
                    <div class="col-auto header-fixed-col">
                        <!-- Begin logo -->
                        <a href="/home" class="logo" title="PathSoft">
                            <img src="{{ asset('/assets/img/logo_en.png') }}" width="115" height="36"
                                alt="PathSoft">
                        </a><!-- End logo -->
                    </div>
                    <div class="col-auto header-fixed-col d-none d-lg-block col-static">
                        <!-- Begin main menu -->
                        <nav class="main-mnu">
                            <ul class="main-mnu-list">
                                <li><a href="/home"
                                        data-title="{{ __('messages.home') }}"><span>{{ __('messages.home') }}</span></a>
                                </li>
                                <li><a href="/about-us"
                                        data-title="{{ __('messages.about_us') }}"><span>{{ __('messages.about_us') }}</span></a>
                                </li>
                                <li><a href="/activities"
                                        data-title="{{ __('messages.our_activities') }}"><span>{{ __('messages.our_activities') }}</span></a>
                                </li>
                                <li><a href="/search"
                                        data-title="{{ __('messages.find_our_volunteers') }}"><span>{{ __('messages.find_our_volunteers') }}</span></a>
                                </li>
                                <li><a href="/courses"
                                        data-title="{{ __('messages.training_courses') }}"><span>{{ __('messages.training_courses') }}</span></a>
                                </li>
                                <li><a href="/news"
                                        data-title="{{ __('messages.news') }}"><span>{{ __('messages.news') }}</span></a>
                                </li>
                                <li><a href="/contact-us"
                                        data-title="{{ __('messages.contact_us') }}"><span>{{ __('messages.contact_us') }}</span></a>
                                </li>
                            </ul>
                        </nav>
                        <!-- End main menu -->
                    </div>
                    <div class="col-auto header-fixed-col col-static">
                        <ul class="header-actions">
                            <li class="d-none d-lg-block">
                                <div class="header-lang">
                                    <div class="header-lang-current"><i class="material-icons md-22">perm_identity</i>
                                    </div>
                                    <ul class="header-lang-list" style="width: 300px">
                                        @if (session()->has('user'))
                                            {{-- ถ้า login แล้ว --}}
                                            <li>
                                                <a href="{{ route('profile') }}" class="hover-link"
                                                    data-title="{{ __('messages.profile') }}">
                                                    <span>{{ __('messages.profile') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}" class="hover-link"
                                                    data-title="{{ __('messages.logout') }}">
                                                    <span>{{ __('messages.logout') }}</span>
                                                </a>
                                            </li>
                                        @else
                                            {{-- ถ้ายังไม่ได้ login --}}
                                            <li>
                                                <a href="{{ route('login') }}" class="hover-link"
                                                    data-title="{{ __('messages.login') }}">
                                                    <span>{{ __('messages.login') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('register') }}" class="hover-link"
                                                    data-title="{{ __('messages.register_as_volunteer') }}">
                                                    <span>{{ __('messages.register_as_volunteer') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                            <li class="d-none d-lg-block">
                                <div class="header-lang">
                                    <div class="header-lang-current"><i class="material-icons md-22">language</i>
                                    </div>
                                    <ul class="header-lang-list">
                                        <li class="{{ $lang === 'en' ? 'active' : '' }}">
                                            <a href="{{ url('lang/en') }}" class="hover-link" data-lang="En"
                                                data-title="{{ __('messages.english') }}">
                                                <span>{{ __('messages.english') }}</span>
                                            </a>
                                        </li>
                                        <li class="{{ $lang === 'th' ? 'active' : '' }}">
                                            <a href="{{ url('lang/th') }}" class="hover-link" data-lang="Th"
                                                data-title="{{ __('messages.thai') }}">
                                                <span>{{ __('messages.thai') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</div>
