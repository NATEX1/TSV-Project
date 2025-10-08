<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>อาสาสมัครท่องเที่ยวและกีฬา (อสทก.)</title>

    <link rel="icon" href="{{ asset('assets/image/favicon/favicon.ico') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/libs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link rel="preload" href="{{ asset('assets/fonts/istok-web-v15-latin/istok-web-v15-latin-regular.woff2') }}"
        as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/istok-web-v15-latin/istok-web-v15-latin-700.woff2') }}"
        as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/montserrat-v15-latin/montserrat-v15-latin-700.woff2') }}"
        as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/montserrat-v15-latin/montserrat-v15-latin-600.woff2') }}"
        as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/material-icons/material-icons.woff2') }}" as="font"
        type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/material-icons/material-icons-outlined.woff2') }}" as="font"
        type="font/woff2" crossorigin>

    <!-- icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.css"> --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
        }

        /* #calendar {
            max-width: 1200px;
            margin: 0 auto;
            font-size: 13px;
        } */
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

</head>

<body>
    <main class="main">
        <div class="main-inner">
            <x-header />

            {{ $slot }}
        </div>

        <x-footer />


    </main>


    <div id="cookiePopup"
        style="position: fixed; margin: 16px; bottom: 0; right: 0; z-index: 1; background-color: white; padding: 24px; border-radius: 12px; max-width: 400px; box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px; transform: translateY(calc(100% + 16px));">

        <!-- ปุ่ม X -->
        <button id="closeCookiePopup"
            style="position: absolute; top: 8px; right: 8px; background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>

        <div class="mb-2 mb-md-0">
            <h6 style="margin-bottom: 8px">{{ __('messages.cookie_title') }}</h6>
            <p>
                {{ __('messages.cookie_text') }}
                <a href="/privacy-policy" class="text-decoration-underline">{{ __('messages.cookie_more') }}</a>
            </p>
        </div>
        <div>
            <button class="btn" id="acceptCookieBtn">
                <span>{{ __('messages.cookie_accept') }}</span>
            </button>

            <button class="btn btn-border" id="settingsCookieBtn">
                <span>{{ __('messages.cookie_settings') }}</span>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('cookiePopup');
            const acceptBtn = document.getElementById('acceptCookieBtn');
            const closeBtn = document.getElementById('closeCookiePopup');

            if (!localStorage.getItem('cookiesAccepted')) {
                setTimeout(() => {
                    popup.style.transform = 'translateY(0)';
                }, 100);
            }

            const closePopup = () => {
                popup.style.transform = 'translateY(100%)';
                setTimeout(() => popup.style.display = 'none', 500);
            };

            acceptBtn.addEventListener('click', async function() {
                let userIP = 'unknown';
                try {
                    const response = await fetch('https://api.ipify.org?format=json');
                    const data = await response.json();
                    userIP = data.ip;
                } catch (err) {
                    console.error('Cannot fetch IP', err);
                }

                document.cookie = `userIP=${userIP}; path=/; max-age=${60*60*24*30}`; 
                localStorage.setItem('cookiesAccepted', 'true');
                closePopup();
            });

            closeBtn.addEventListener('click', closePopup);
        });
    </script>





    <script src="{{ asset('assets/libs/lozad/lozad.min.js') }}"></script>
    <script src="{{ asset('assets/libs/device/device.js') }}"></script>
    <script src="{{ asset('assets/libs/ScrollToFixed/jquery-scrolltofixed-min.js') }}"></script>
    <script src="{{ asset('assets/libs/spincrement/jquery.spincrement.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation-1.19.3/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".bannerSwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: "slide",
            speed: 1000
        });
    </script>

</body>

</html>
