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
                        <li><a href="#!">{{ __('messages.contact_us') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End bread crumbs -->

    <div class="section">
        <div class="container">
            <div class="row content-items">
                <div class="col-12">
                    <div class="section-heading">
                        <div class="section-subheading">{{ __('messages.we_are_always_in_touch') }}</div>
                        <h1>{{ __('messages.contact_us') }}</h1>
                    </div>
                </div>

                <div class="col-xl-4 col-md-5 content-item">
                    <div class="contact-info section-bgc">
                        <h3>{{ __('messages.contact_info') }}</h3>
                        <ul class="contact-list">
                            <li>
                                <i class="material-icons md-22">location_on</i>
                                <div class="footer-contact-info">
                                    {!! $contact['address_' . $lang] !!}
                                </div>
                            </li>
                            <li>
                                <i class="material-icons md-22 footer-contact-tel">smartphone</i>
                                <div class="footer-contact-info">
                                    <a href="tel:{{ $contact->phone }}" class="formingHrefTel">{{ $contact->phone }}</a>
                                </div>
                            </li>
                            <li>
                                <i class="material-icons md-22 footer-contact-email">{{ __('email') }}</i>
                                <div class="footer-contact-info">
                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="map col-xl-8 col-md-7 content-item">
                    <iframe class="lazy" referrerpolicy="no-referrer-when-downgrade"
                        data-src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3875.309301336373!2d100.508207!3d13.760212!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x5e6b3dca6cbb01c3!2z4LiB4Lij4Liw4LiX4Lij4Lin4LiH4LiB4Liy4Lij4LiX4LmI4Lit4LiH4LmA4LiX4Li14LmI4Lii4Lin4LmB4Lil4Liw4LiB4Li14Lis4Liy!5e0!3m2!1sth!2sth!4v1664432178746!5m2!1sth!2sth"></iframe>

                </div>

                {{-- <div class="col-xl-8 col-md-7 content-item">
                    <form action="#!" method="post" class="contact-form contact-form-padding">
                        @csrf
                        <input type="hidden" name="form_subject" value="Contact form">

                        <div class="row gutters-default">
                            <div class="col-12">
                                <h3>Send Us a Message</h3>
                            </div>

                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="form-field">
                                    <label for="contact-name" class="form-field-label">Full Name</label>
                                    <input type="text" class="form-field-input" name="ContactName" autocomplete="off"
                                        required id="contact-name">
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="form-field">
                                    <label for="contact-phone" class="form-field-label">Phone Number</label>
                                    <input type="tel" class="form-field-input mask-phone" name="ContactPhone"
                                        autocomplete="off" required id="contact-phone">
                                </div>
                            </div>

                            <div class="col-xl-4 col-12">
                                <div class="form-field">
                                    <label for="contact-email" class="form-field-label">Email</label>
                                    <input type="email" class="form-field-input" name="ContactEmail"
                                        autocomplete="off" required id="contact-email">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-field">
                                    <label for="contact-message" class="form-field-label">Your Message</label>
                                    <textarea name="ContactMessage" class="form-field-input" id="contact-message" cols="30" rows="6"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-btn">
                                    <button type="submit" class="btn btn-w240 ripple"><span>Send</span></button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div> --}}

            </div>
        </div>
    </div>

    <!-- Begin map -->
    {{-- <div class="map">
        <iframe class="lazy" referrerpolicy="no-referrer-when-downgrade"
            data-src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3875.309301336373!2d100.508207!3d13.760212!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x5e6b3dca6cbb01c3!2z4LiB4Lij4Liw4LiX4Lij4Lin4LiH4LiB4Liy4Lij4LiX4LmI4Lit4LiH4LmA4LiX4Li14LmI4Lii4Lin4LmB4Lil4Liw4LiB4Li14Lis4Liy!5e0!3m2!1sth!2sth!4v1664432178746!5m2!1sth!2sth"></iframe>

    </div> --}}
    <!-- End map -->

    <div class="section">
        <div class="container">
            <div class="row content-items">
                <h3>{{ __('messages.contact_information_for_provincial_tourism_and_sports_offices') }}</h3>

                @php
                    $regionNames = [
                        1 => __('messages.region_north'),
                        2 => __('messages.region_central'),
                        3 => __('messages.region_northeast'),
                        4 => __('messages.region_west'),
                        5 => __('messages.region_east'),
                        6 => __('messages.region_south'),
                    ];
                @endphp

                @foreach ($regions as $regionId => $provinces)
                    <div class="col-lg-6">
                        <div align="center">
                            <h3>{{ $regionNames[$regionId] }}</h3>
                        </div>
                        <hr />
                        <ul>
                            @foreach ($provinces as $province)
                                <li>
                                    <div>
                                        <a href="{{ $province->link ?? "http://www.mots.go.th/$province->name_en" }}"
                                            target="_blank">{{ __('messages.provincial_department_of_tourism_and_sports') }}{{ $lang == 'en' ? ' ' : '' }}{{ $province['name_' . $lang] }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

</x-layout>
