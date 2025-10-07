<!-- Begin Become TSV Banner -->
<div>
    <a class="bff d-none d-md-block" href="{{ route('register.form') }}">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="bff-container">
                        <p>{{ __('messages.join_our_community') }}</p>
                        <div class="btn btn-border btn-small">
                            <span>{{ __('messages.register_as_volunteer') }}</span>
                            <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                <use xlink:href="{{ asset('/assets/img/sprite.svg#arrow-right') }}"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Begin footer -->
<footer class="footer">
    <div class="footer-main">
        <div class="container">
            <div class="row items">
                <!-- Company Info -->
                <div class="col-xl-3 col-lg-3 col-md-5 col-12 item">
                    <div class="footer-company-info">
                        <div class="footer-company-top">
                            <a href="{{ url('/') }}" class="logo" title="{{ __('messages.home') }}">
                                <img data-src="{{ asset('../assets/img/logo-whitee_en.png') }}" width="115"
                                    height="36" class="lazy"
                                    src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                    alt="{{ __('messages.home') }}">
                            </a>
                            <div class="footer-company-desc">
                                {!! $contact['text_' . $lang] !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu -->
                <div class="col-xl-2 offset-xl-1 col-md-3 col-5 col-lg-2 item">
                    <div class="footer-item">
                        <p class="footer-item-heading">{{ __('messages.menu') }}</p>
                        <nav class="footer-nav">
                            <ul class="footer-mnu">
                                <li><a href="about-us" class="hover-link"
                                        data-title="{{ __('messages.about_us') }}"><span>{{ __('messages.about_us') }}</span></a>
                                </li>
                                <li><a href="active" class="hover-link"
                                        data-title="{{ __('messages.our_activities') }}"><span>{{ __('messages.our_activities') }}</span></a>
                                </li>
                                <li><a href="search" class="hover-link"
                                        data-title="{{ __('messages.find_our_volunteers') }}"><span>{{ __('messages.find_our_volunteers') }}</span></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Other Links -->
                <div class="col-xl-2 col-lg-3 col-md-3 col-7 item">
                    <div class="footer-item">
                        <p class="footer-item-heading">&nbsp;</p>
                        <nav class="footer-nav">
                            <ul class="footer-mnu">
                                <li><a href="https://e-training.tpqi.go.th/courses/381/info" target="_blank"
                                        class="hover-link"
                                        data-title="{{ __('messages.training_courses') }}"><span>{{ __('messages.training_courses') }}</span></a>
                                </li>
                                <li><a href="news" class="hover-link"
                                        data-title="{{ __('messages.news') }}"><span>{{ __('messages.news') }}</span></a>
                                </li>
                                <li><a href="contact-us" class="hover-link"
                                        data-title="{{ __('messages.contact_us') }}"><span>{{ __('messages.contact_us') }}</span></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-xs-4 col-lg-4 col-12 item">
                    <div class="footer-item">
                        <p class="footer-item-heading">{{ __('messages.contact_us') }}</p>
                        <ul class="footer-contacts">
                            <li>
                                <i class="material-icons md-22">location_on</i>
                                <div class="footer-contact-info">{!! $contact['address_' . $lang] !!}</div>
                            </li>
                            <li>
                                <i class="material-icons md-22 footer-contact-tel">smartphone</i>
                                <div class="footer-contact-info">
                                    <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                                </div>
                            </li>
                            <li>
                                <i class="material-icons md-22 footer-contact-email">email</i>
                                <div class="footer-contact-info">
                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Social Links -->
                    <ul class="footer-social-links">
                        <li>
                            <a href="{{ $contact->facebook }}" title="Facebook">
                                <svg viewBox="0 0 320 512">
                                    <use xlink:href="{{ asset('/assets/img/sprite.svg#facebook-icon') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $contact->ig }}" title="Instagram">
                                <svg viewBox="0 0 448 512">
                                    <use xlink:href="{{ asset('/assets/img/sprite.svg#instagram-icon') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $contact->line }}" title="LinkedIn">
                                <svg viewBox="0 0 448 512">
                                    <use xlink:href="{{ asset('/assets/img/sprite.svg#linkedin-icon') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $contact->twitter }}" title="Twitter">
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

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row justify-content-between items">
                <div class="col-md-auto col-12 item">
                    <nav class="footer-links">
                        <ul>
                            <!-- Add terms/privacy links if needed -->
                        </ul>
                    </nav>
                </div>
                <div class="col-md-auto col-12 item">
                    <div class="copyright">© {{ DATE('Y') }} {{ __('messages.ministry_of_tourism_and_sports') }}.
                        {{ __('messages.all_rights_reserved') }}</div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End footer -->
