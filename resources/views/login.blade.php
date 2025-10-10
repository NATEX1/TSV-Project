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
                        <li><a href="/login">{{ __('messages.sign_in') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- End bread crumbs -->

    <div class="section">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">{{ __('messages.newcomer') }}</div>
                        <h1>{{ __('messages.sign_in') }}</h1>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12 item">
                    <form action="{{ route('login') }}" method="post">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif


                        <div class="form-group">
                            <label for="username">{{ __('messages.email_address_or_username') }}</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username') }}">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('messages.birth_date_or_password') }} <span style="color: #ccc">{{ __('messages.for_example_january_1_1996_is_01012539') }}</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group flex-column">
                            <button type="submit" class="btn" style="width: 100%">
                                <span>{{ __('messages.sign_in') }}</span>
                                <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                    <use xlink:href="../assets/img/sprite.svg#arrow-right"></use>
                                </svg>
                            </button>
                            <div class="d-flex justify-content-center my-2 align-items-center" style="width: 100%">
                                <hr style="width: 100%">
                                <p class="text-center m-0 mx-2" style="color: #e3e3e3"> or </p>
                                <hr style="width: 100%">
                            </div>
                            <a href="{{ route('google.login') }}" class="btn-google">
                                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo">
                                Login with Google
                            </a>

                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
</x-layout>
