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
                        <li><a href="#!">{{ __('messages.consent') }}</a></li>
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
                        <div class="section-subheading">{{ __('messages.consent_subheading') }}</div>
                        <font size="5"><b>{{ __('messages.consent_title') }}</b></font><br>

                        {{-- Alert ถ้าไม่ยินยอม --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>{{ __('messages.consent_error') }}</strong>
                            </div>
                        @endif

                        <p>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ __('messages.consent_detail') }}
                        </p>
                    </div>
                </div>

                <div class="col-xl-12 col-md-12 content-item">
                    <form action="{{ route('consent.submit') }}" method="post" id="kt_sign_up_form">
                        @csrf
                        <b>{{ __('messages.consent_terms') }}</b><br>
                        <ul>
                            <ol>
                                <li><b>{{ __('messages.consent_keep') }}</b></li>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <input type="radio" class="form-field-input form-check-input" name="keep"
                                            value="0" checked id="agree-1">
                                        <label class="form-check-label"
                                            for="agree-1">{{ __('messages.agree') }}</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="radio" class="form-field-input form-check-input" name="keep"
                                            value="1" id="disagree-1">
                                        <label class="form-check-label"
                                            for="disagree-1">{{ __('messages.disagree') }}</label>
                                    </div>
                                </div>

                                <li><b>{{ __('messages.consent_expose') }}</b></li>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <input type="radio" class="form-field-input form-check-input" name="expose"
                                            value="0" checked id="agree-2">
                                        <label class="form-check-label"
                                            for="agree-2">{{ __('messages.agree') }}</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="radio" class="form-field-input form-check-input" name="expose"
                                            value="1" id="disagree-2">
                                        <label class="form-check-label"
                                            for="disagree-2">{{ __('messages.disagree') }}</label>
                                    </div>
                                </div>
                            </ol>
                        </ul>

                        <br><br>
                        <div class="section-btns justify-content-center">
                            <button type="submit" class="btn btn-with-icon btn-w240 ripple">
                                <span>{{ __('messages.accept_terms') }}</span>
                                <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                    <use xlink:href="../assets/img/sprite.svg#arrow-right"></use>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
