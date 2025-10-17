@php
    $googleUser = session('google_user', null);
@endphp

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
                        <li><a href="#!">{{ __('messages.register_as_volunteer') }}</a></li>
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
                        <div class="section-subheading">{{ __('messages.join_our_community') }}</div>
                        <h1>{{ __('messages.register_as_volunteer') }}</h1>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <ul>
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-xl-12 col-md-12 content-item">
                    <form action="{{ route('register') }}" method="post" id="kt_sign_up_form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters-default">
                            <h4>{{ __('messages.general_information') }}</h4>

                            @if ($lang == 'th')
                                <div class="col-xl-6 col-sm-4 col-12">
                                    <div class="form-field">
                                        <label>{{ __('messages.firstname_th') }} <span style="color:red">*</span>
                                            <span style="color:#cccccc">{{ __('messages.no_prefix_required') }}</span>
                                        </label>
                                        <input type="text" class="form-field-input"
                                            placeholder="{{ __('messages.firstname_th') }}" name="fname_th"
                                            autocomplete="off" required value="{{ old('fname_th') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-4 col-12">
                                    <div class="form-field">
                                        <label>{{ __('messages.lastname_th') }} <span
                                                style="color:red">*</span></label>
                                        <input type="text" class="form-field-input mask-phone"
                                            placeholder="{{ __('messages.lastname_th') }}" name="lname_th"
                                            autocomplete="off" required value="{{ old('lname_th') }}">
                                    </div>
                                </div>
                            @endif
                            <div class="col-xl-6 col-sm-4 col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.firstname_en') }}<span style="color:red">*</span>
                                        <span style="color:#cccccc">{{ __('messages.no_prefix_required') }}</span>
                                    </label>
                                    <input type="text" class="form-field-input"
                                        placeholder="{{ __('messages.firstname_en') }}" name="fname_en"
                                        autocomplete="off" required
                                        onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) "
                                        value="{{ old('fname_en') }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-4 col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.lastname_en') }} <span style="color:red">*</span></label>
                                    <input type="text" class="form-field-input mask-phone"
                                        placeholder="{{ __('messages.lastname_en') }}" name="lname_en"
                                        autocomplete="off" required
                                        onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) "
                                        value="{{ old('lname_en') }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-4 col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-field">
                                            <label>{{ __('messages.passport_or_identification') }}<span
                                                    style="color:red">*</span></label>
                                            <input type="text" class="form-field-input"
                                                placeholder="{{ __('messages.passport_or_identification') }}"
                                                name="idcard" autocomplete="off" required value="{{ old('idcard') }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-field">
                                            <label>{{ __('messages.sex') }}<span style="color:red">*</span></label>
                                            <select name="sex" class="form-field-input" required>
                                                <option value="" disabled {{ old('sex') === null ? 'selected' : '' }}>
                                                    {{ __('messages.select_sex') }}
                                                </option>
                                                <option value="0" {{ old('sex') == '0' ? 'selected' : '' }}>
                                                    {{ __('messages.male') }}</option>
                                                <option value="1" {{ old('sex') == '1' ? 'selected' : '' }}>
                                                    {{ __('messages.female') }}</option>
                                                <option value="2" {{ old('sex') == '2' ? 'selected' : '' }}>
                                                    {{ __('messages.other') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-sm-4 col-12 ">
                                <div class="form-field">
                                    <label>{{ __('messages.birth_date') }}<span style="color:red">*</span></label>
                                    <div class="row g-2">
                                        <!-- Day -->
                                        <div class="col-xl-4 col-sm-4 col-4">
                                            <select name="day" class="form-select" style="height: 50px;" required>
                                                <option value="" disabled>{{ __('messages.day') }}</option>
                                                @for ($day = 1; $day <= 31; $day++)
                                                    <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>
                                                        {{ $day }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Month (ชื่อเดือนไทย) -->
                                        @php
                                            $thaiMonths = __('messages.months');
                                        @endphp
                                        <div class="col-xl-4 col-sm-4 col-4">
                                            <select name="month" class="form-select" style="height: 50px;" required>
                                                <option value="" disabled>{{ __('messages.month') }}</option>
                                                @foreach ($thaiMonths as $num => $name)
                                                    <option value="{{ $num }}" {{ old('month') == $num ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Year (พ.ศ.) -->
                                        <div class="col-xl-4 col-sm-4 col-4">
                                            <select name="year" class="form-select" style="height: 50px;"
                                                required>
                                                <option value="" disabled>{{ __('messages.year') }}</option>
                                                @php
                                                    $currentYear = date('Y');
                                                    $maxYear = $currentYear - 18; // อายุ ≥ 18
                                                    $minYear = $currentYear - 100; // อายุ ≤ 100
                                                @endphp
                                                @for ($i = $maxYear; $i >= $minYear; $i--)
                                                    @php
                                                        $valueYear = $i + 543;
                                                        $displayYear = $lang === 'en' ? $i : $valueYear;
                                                    @endphp
                                                    <option value="{{ $valueYear }}"
                                                        {{ old('year') == $valueYear ? 'selected' : '' }}>
                                                        {{ $displayYear }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="col-xl-6 col-sm-6 col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.email_address') }}</label>
                                    <input type="email" class="form-field-input"
                                        placeholder="{{ __('messages.email_address') }}" name="email"
                                        autocomplete="off" value="{{ old('email', $googleUser->email ?? '') }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.telephone_no') }}</label>
                                    <input type="text" class="form-field-input mask-phone" minlength="10"
                                        maxlength="10" placeholder="{{ __('messages.telephone_no') }}"
                                        name="phone" autocomplete="off" id="contact-phone" pattern="\d+"
                                        value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.province') }} <span style="color:red">*</span></label>
                                    <select name="province_id" id="province" class="form-select"
                                        style="height:50px" required>
                                        <option selected disabled>{{ __('messages.select') }}</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}" 
                                                {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                {{ $province['name_' . $lang] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.district') }}<span style="color:red">*</span></label>
                                    <select name="amphure_id" id="amphure" class="form-select" style="height:50px"
                                        required>
                                        <option selected disabled>{{ __('messages.select') }}</option>
                                        @if(old('amphure_id'))
                                            @foreach ($districts as $district)
                                                @if($district->id == old('amphure_id'))
                                                    <option value="{{ $district->id }}" selected>
                                                        {{ $district['name_' . $lang] }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.subdistrict') }}<span style="color:red">*</span></label>
                                    <select name="district_id" id="district" class="form-select"
                                        style="height:50px" required>
                                        <option selected disabled>{{ __('messages.select') }}</option>
                                        @if(old('district_id'))
                                            @foreach ($subdistricts as $subdistrict)
                                                @if($subdistrict->id == old('district_id'))
                                                    <option value="{{ $subdistrict->id }}" selected>
                                                        {{ $subdistrict['name_' . $lang] }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-12 col-sm-6 col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.address') }}</label>
                                    <input type="text" class="form-field-input"
                                        placeholder="{{ __('messages.address') }}" name="address"
                                        autocomplete="off" value="{{ old('address') }}">
                                </div>
                            </div>

                            <h4>{{ __('messages.work_occupations') }}</h4>
                            <div class="col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.past_volunteer_work_occupations') }}</label>
                                    <textarea name="job" class="form-field-input" placeholder="{{ __('messages.past_volunteer_work_occupations') }}"
                                        cols="30" rows="6">{{ old('job') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.volunteer_work_experience') }}</label>
                                    <textarea name="experience" class="form-field-input" placeholder="{{ __('messages.volunteer_work_experience') }}"
                                        cols="30" rows="6">{{ old('experience') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-field">
                                    <label>{{ __('messages.certificates_or_training') }}</label>
                                    <textarea name="training" class="form-field-input" placeholder="{{ __('messages.certificates_or_training') }}"
                                        cols="30" rows="6">{{ old('training') }}</textarea>
                                </div>
                            </div>

                        </div>

                        <h4>{{ __('messages.details_of_skills') }}</h4>
                        <div class="acordion">
                            <div class="row gutter-default">
                                <div class="col-lg-12 col-12">
                                    <ul class="accordion-list">
                                        @foreach ($skillLv1 as $lv1)
                                            <li class="accordion-item section-bgc">
                                                <div class="accordion-trigger">{{ $lv1['name_' . $lang] }}</div>
                                                <div class="accordion-content content">
                                                    @foreach ($skillLv2 as $lv2)
                                                        @if ($lv2->lv1_id == $lv1->id)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="option[]" value="{{ $lv2->id }}"
                                                                    {{ is_array(old('option')) && in_array($lv2->id, old('option')) ? 'checked' : '' }}>
                                                                <label
                                                                    class="form-check-label">{{ $lv2['name_' . $lang] }}</label>
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
                        <br><br>
                        <button type="submit" class="btn btn-with-icon btn-w240 ripple">
                            <span>{{ __('messages.register_title_button') }}</span>
                            <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                <use xlink:href="../assets/img/sprite.svg#arrow-right"></use>
                            </svg>
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const provinces = @json($provinces);
        const amphures = @json($districts);
        const districts = @json($subdistricts);

        const provinceSelect = document.getElementById('province');
        const amphureSelect = document.getElementById('amphure');
        const districtSelect = document.getElementById('district');

        // ฟังก์ชันสำหรับโหลดข้อมูลอำเภอตามจังหวัดที่เลือก
        function loadAmphures(pid) {
            const filteredAmphures = amphures.filter(a => parseInt(a.province_id) === pid);

            amphureSelect.innerHTML = '<option selected disabled>{{ __('messages.select') }}</option>';
            filteredAmphures.forEach(a => {
                amphureSelect.innerHTML +=
                    `<option value="${a.id}" ${a.id == {{ old('amphure_id', 0) }} ? 'selected' : ''}>${a.name_{{ $lang }}}</option>`;
            });
        }

        // ฟังก์ชันสำหรับโหลดข้อมูลตำบลตามอำเภอที่เลือก
        function loadDistricts(aid) {
            const filteredDistricts = districts.filter(d => parseInt(d.amphure_id) === aid);

            districtSelect.innerHTML = '<option selected disabled>{{ __('messages.select') }}</option>';
            filteredDistricts.forEach(d => {
                districtSelect.innerHTML +=
                    `<option value="${d.id}" ${d.id == {{ old('district_id', 0) }} ? 'selected' : ''}>${d.name_{{ $lang }}}</option>`;
            });
        }

        // เมื่อโหลดหน้าเว็บเสร็จ
        document.addEventListener('DOMContentLoaded', function() {
            // ถ้ามีการเลือกจังหวัดไว้แล้ว (จาก old data)
            const selectedProvinceId = {{ old('province_id', 0) }};
            if (selectedProvinceId) {
                loadAmphures(selectedProvinceId);
                
                // ถ้ามีการเลือกอำเภอไว้แล้ว
                const selectedAmphureId = {{ old('amphure_id', 0) }};
                if (selectedAmphureId) {
                    loadDistricts(selectedAmphureId);
                }
            }
        });

        provinceSelect.addEventListener('change', function() {
            const pid = parseInt(this.value);
            loadAmphures(pid);
            districtSelect.innerHTML = '<option selected disabled>{{ __('messages.select') }}</option>';
        });

        amphureSelect.addEventListener('change', function() {
            const aid = parseInt(this.value);
            loadDistricts(aid);
        });
    </script>

</x-layout>