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
    <!-- Breadcrumbs -->
    <nav class="bread-crumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="bread-crumbs-list">
                        <li>
                            <a href="{{ url('/') }}">{{ __('messages.home') }}</a>
                            <i class="material-icons md-18">chevron_right</i>
                        </li>
                        <li>
                            <a href="#!">{{ __('messages.profile') }} &nbsp;
                                {{ $user['fname_' . $lang] ?? $user->name }}&nbsp;{{ $user['lname_' . $lang] ?? '' }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Profile Section -->
    <div class="section">
        <div class="container">
            <div class="row items">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 item">
                    <div style="border: #d4d4e1 1px solid; border-radius: 10px;">
                        <article class="news-item">
                            <button style="border: none; background: transparent; padding: 0; overflow: hidden;"
                                data-bs-toggle="modal" data-bs-target="#editProfilePicModal">
                                @php
                                    if (empty($user->user_img)) {
                                        $userImage =
                                            'https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png';
                                    } elseif (filter_var($user->user_img, FILTER_VALIDATE_URL)) {
                                        $userImage = $user->user_img;
                                    } else {
                                        $userImage = checkImageUrl('storage/images/user/' . $user->user_img);
                                    }
                                @endphp

                                <img src="{{ $userImage }}" alt="myprofile"
                                    style="max-width:100%; min-height:525px; max-height:525px; object-fit: cover; border-radius: 10px 10px 0 0;">
                                <b style="color:#cccccc; font-size: 12px">
                                    {{ __('messages.note_image_picking') }}
                                </b>
                            </button>

                            <div class="news-item-info">
                                <div class="news-item-desc" style="font-size:12px;">

                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 style="margin: 0">{{ __('messages.personal_info') }}</h6>
                                        @if (session('user') && $user->id == session('user.id'))
                                            <button style="background: transparent; border: none;"
                                                data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                                <i class="material-icons" style=" color:#555;">edit</i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-5">
                                            <b style="font-size:12px;">{{ __('messages.participation_status') }}</b>
                                        </div>

                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 col-7">
                                            <span style="color: {{ $user->register_status->color ?? '#000' }}">
                                                {{ $user->register_status->{'name_' . $lang} ?? '' }}
                                            </span>
                                        </div>
                                    </div>


                                    @if ($user->register_status->id == 4)
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-5">
                                                <b style="font-size:12px;">{{ __('messages.tsv_id') }}</b>
                                            </div>
                                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 col-7">
                                                {{ $user->tsv_id ?? '-' }}
                                            </div>
                                        </div>
                                    @endif


                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-5">
                                            <b
                                                style="font-size:12px;">{{ __('messages.firstname_and_lastname') . ' (' . __('messages.thai') . ')' }}</b>
                                            <br>
                                            <b
                                                style="font-size:12px;">{{ __('messages.firstname_and_lastname') . ' (' . __('messages.english') . ')' }}</b>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 col-7">
                                            <p style="word-wrap: break-word;">
                                                {{ $user['fname_th'] ?? '-' }}&nbsp;&nbsp;{{ $user['lname_th'] ?? '' }}
                                                <br>
                                                {{ $user['fname_en'] ?? '-' }}&nbsp;&nbsp;{{ $user['lname_en'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($user->id == session('user.id'))
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-5">
                                                <b style="font-size:12px;">{{ __('messages.citizen_no') }}</b>
                                            </div>
                                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 col-7">
                                                <p>{{ $user->idcard ?? '-' }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($user->register_status->id == 2 || $user->register_status->id == 3)
                                        <div class="row">
                                            <div class="col-5">
                                                <b style="font-size:12px;">{{ __('messages.take_the_test') }}</b>
                                            </div>
                                            <div class="col-7">
                                                <a href="{{ route('quiz') }}" target="_blank" title="คลิกที่นี่"
                                                    class="d-flex gap-1 text-decoration-none">
                                                    <span
                                                        class="material-icons">link</span><span>{{ __('messages.quiz') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if ($user->id == session('user.id'))
                                    <hr>
                                    <div class="news-item-desc" style="font-size:12px;">
                                        <h6 class="mb-2" style="margin: 0">{{ __('messages.contact_info') }}</h6>
                                        <div class="row">
                                            <div class="col-5">
                                                <b style="font-size:12px;">{{ __('messages.email') }}</b>
                                            </div>
                                            <div class="col-7">
                                                <p style="word-wrap: break-word;">
                                                    {{ !empty($user->email) ? $user->email : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-5">
                                                <b style="font-size:12px;">{{ __('messages.telephone_no') }}</b>
                                            </div>
                                            <div class="col-7">
                                                <p>{{ !empty($user->phone) ? $user->phone : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-5">
                                                <b style="font-size:12px;">{{ __('messages.address') }}</b>
                                            </div>
                                            <div class="col-7">
                                                <p>{{ $user->address_th ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->id == session('user.id'))
                                    <hr>
                                    <div>
                                        <button class="btn" data-bs-toggle="modal"
                                            data-bs-target="#changePasswordModal" style="width: 100%">
                                            <i class="bi bi-lock"></i>
                                            <span> {{ __('messages.change_password') }}</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </article>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12 item">
                    <div class="tab-container">
                        <div class="tab-nav">
                            @if ($user->register_status->id == 4)
                                <button class="tab-link" data-tab="card">{{ __('messages.arsa_card') }}</button>
                            @endif

                            <button class="tab-link" data-tab="experience">{{ __('messages.experience') }}</button>

                            @if ($user->register_status->id == 4 && session('user.id') == $user->id)
                                <button class="tab-link"
                                    data-tab="activities">{{ __('messages.participation_activities') }}</button>
                                <button class="tab-link"
                                    data-tab="report">{{ __('messages.report_activity') }}</button>
                            @endif
                        </div>
                        @if ($user->register_status->id == 4)
                            <div class="tab-content p-2 p-lg-4" style="padding: 40px" data-tab="card">
                                <article class="news-post-article content">
                                    <h6 class="section-title">{{ __('messages.arsa_card') }}</h6>
                                    <br>
                                    <div class="d-flex justify-content-center">
                                        <iframe src="{{ route('arsa-card.show', $user->id) }}" frameborder="0"
                                            width="280" height="406"></iframe>
                                    </div>
                                </article>
                            </div>
                        @endif
                        <div class="tab-content" style="padding: 40px" data-tab="experience">
                            <article class="news-post-article content">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6>{{ __('messages.expertise_info') }}</h6>
                                        @if (session('user') && $user->id == session('user.id'))
                                            <button class="edit-user-bottom" data-bs-toggle="modal"
                                                data-bs-target="#editSkillModal">
                                                <i class="material-icons" style=" color:#555;">edit</i>
                                            </button>
                                        @endif

                                    </div>

                                    <div class="container">

                                        @if (isset($user->skill_lv1) && count($user->skill_lv1))
                                            <ul>
                                                @foreach ($user->skill_lv1 as $lv1)
                                                    @php
                                                        $lv1 = (array) $lv1;
                                                    @endphp
                                                    @if (isset($lv1['lv2']) && count($lv1['lv2']))
                                                        @foreach ($lv1['lv2'] as $lv2)
                                                            @php
                                                                $lv2 = (array) $lv2;
                                                            @endphp
                                                            <li>{{ $lv2["$lang"] ?? $lv2["$lang"] }}</li>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif

                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6>{{ __('messages.other_expertise') }}</h6>
                                        @if (session('user') && $user->id == session('user.id'))
                                            <button class="edit-user-bottom" data-bs-toggle="modal"
                                                data-bs-target="#editJobModal">
                                                <i class="material-icons" style=" color:#555;">edit</i>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="container">
                                        @if (isset($user->job) && !empty($user->job))
                                            {!! $user->job !!}
                                        @endif
                                    </div>

                                </div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6>{{ __('messages.volunteer_work_history') }}</h6>
                                        @if (session('user') && $user->id == session('user.id'))
                                            <button class="edit-user-bottom" data-bs-toggle="modal"
                                                data-bs-target="#editExperienceModal">
                                                <i class="material-icons" style=" color:#555;">edit</i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="container">
                                        @if (isset($user->experience) && !empty($user->experience))
                                            {!! $user->experience !!}
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6>{{ __('messages.certificates_or_training') }}</h6>
                                        @if (session('user') && $user->id == session('user.id'))
                                            <button class="edit-user-bottom" data-bs-toggle="modal"
                                                data-bs-target="#editTrainingModal">
                                                <i class="material-icons" style=" color:#555;">edit</i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="container">
                                        @if (isset($user->training) && !empty($user->training))
                                            {!! $user->training !!}
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </div>



                        @if ($user->register_status->id == 4 && session('user.id') == $user->id)
                            <div class="tab-content p-2 p-lg-4" style="padding: 40px" data-tab="activities">
                                <h6 class="mb-4"><i
                                        class="bi bi-journal-text me-1"></i>{{ __('messages.participation_activities') }}
                                </h6>

                                <!-- Search Section -->
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <div class="d-flex" id="searchBox"
                                            style="gap: 8px; align-items: center; position: relative;">
                                            <div class="input-group ">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="mb-0 border form-control"
                                                    placeholder="{{ __('messages.search') }}...">
                                            </div>
                                            <div id="resultsBox" class="popup-box p-2 flex-column gap-2">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Activities List -->
                                <div class="row">
                                    <div class="col-12">

                                        @if (!empty($todayActivities) && count($todayActivities) > 0)

                                            <p class="text-muted mb-2">{{ __('messages.activities_today') }}</p>
                                            @foreach ($todayActivities as $activity)
                                                <div class="border rounded p-4 mb-4">
                                                    <div class="row align-items-center">
                                                        <div class="col-12 col-md-8">
                                                            <h4 class="text-primary text-truncate m-0">
                                                                {{ $activity['title_' . $lang] }}
                                                            </h4>

                                                            <div>
                                                                <small><i class="bi bi-calendar-event"></i>
                                                                    <span>{{ __('messages.start_date') }}:
                                                                        {{ \Carbon\Carbon::parse($activity['start_date'])->format('d/m/Y') }}</span></small>
                                                            </div>
                                                            <div>
                                                                <small><i class="bi bi-calendar2-x"></i>
                                                                    <span>{{ __('messages.end_date') }}:
                                                                        {{ \Carbon\Carbon::parse($activity['stop_date'])->format('d/m/Y') }}</span></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-4  d-flex justify-content-end">
                                                            @if ($activity['joined'])
                                                                <p class="text-success text-center mb-0"
                                                                    style="font-size:12px">
                                                                    <i class="bi bi-check-circle"></i>
                                                                    {{ __('messages.already_joined') }}
                                                                </p>
                                                            @else
                                                                <form action="/activities/join" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="activity_id"
                                                                        value="{{ $activity['id'] }}">
                                                                    <button type="submit" class="btn btn-primary p-2"
                                                                        style="font-size:12px">
                                                                        <i class="bi bi-box-arrow-in-right"></i>
                                                                        {{ __('messages.join_activity') }}
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach

                                            <hr>
                                        @endif

                                        <!-- Joined Activities -->
                                        @if (!empty($joinedActivities) && count($joinedActivities) > 0)
                                            @php
                                                $joinedActivitiesArray = (array) $joinedActivities;

                                                foreach ($joinedActivitiesArray as &$activity) {
                                                    $reports = isset($user->report['1'])
                                                        ? (array) $user->report['1']
                                                        : [];
                                                    $report = null;
                                                    foreach ($reports as $item) {
                                                        if ($item['content'] == $activity['id']) {
                                                            $report = $item;
                                                        }
                                                    }
                                                    $activity['report'] = $report;
                                                    $activity['reported'] = $report ? true : false;
                                                }
                                                unset($activity);

                                                usort($joinedActivitiesArray, function ($a, $b) {
                                                    return $a['reported'] <=> $b['reported'];
                                                });
                                            @endphp

                                            <p class="text-muted mb-2">{{ __('messages.joined_activities') }}</p>

                                            @foreach ($joinedActivitiesArray as $activity)
                                                <div class="border rounded p-4 mb-4">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h4 class="text-primary text-truncate m-0">
                                                                {{ $activity['name_' . $lang] }}
                                                            </h4>
                                                            @if ($activity['reported'])
                                                                <span class="badge status-done my-2">
                                                                    <i
                                                                        class="bi bi-check-circle"></i>&nbsp;{{ __('messages.reported') }}
                                                                </span>
                                                            @else
                                                                <span class="badge status-pending my-2">
                                                                    <i
                                                                        class="bi bi-exclamation-circle"></i>&nbsp;{{ __('messages.not_reported') }}
                                                                </span>
                                                            @endif

                                                            <div>
                                                                <small><i class="bi bi-calendar-event"></i>
                                                                    <span>{{ __('messages.start_date') }}:
                                                                        {{ \Carbon\Carbon::parse($activity['events_start'])->format('d/m/Y') }}</span>
                                                                </small>
                                                            </div>
                                                            <div>
                                                                <small><i class="bi bi-calendar2-x"></i>
                                                                    <span>{{ __('messages.end_date') }}:
                                                                        {{ \Carbon\Carbon::parse($activity['events_stop'])->format('d/m/Y') }}</span>
                                                                </small>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            @if (!$activity['reported'])
                                                                <button
                                                                    class="btn btn-outline-primary p-2 flex align-items-center mt-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addReportActivityModal-{{ $activity['id'] }}"
                                                                    style="font-size: 12px">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                    <span>{{ __('messages.submit_report') }}</span>
                                                                </button>

                                                                <button data-bs-toggle="modal"
                                                                    data-bs-target="#confirmLeaveModal-{{ $activity['id'] }}"
                                                                    class="btn btn-outline-danger p-2 flex align-items-center mt-2"
                                                                    style="font-size: 12px">
                                                                    <i class="bi bi-box-arrow-left"></i>
                                                                    <span>{{ __('messages.cancel_join') }}</span>
                                                                </button>
                                                            @else
                                                                <button
                                                                    class="btn btn-success p-2 flex align-items-center mt-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#viewReportActivityModal-{{ $activity['id'] }}"
                                                                    style="font-size: 12px">
                                                                    <i class="bi bi-eye"></i>
                                                                    <span>{{ __('messages.view_report') }}</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade"
                                                    id="addReportActivityModal-{{ $activity['id'] }}">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    {{ $activity['name_' . $lang] }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('report.submit', $activity['id']) }}"
                                                                    method="POST" enctype="multipart/form-data"
                                                                    id="addReportActivityForm-{{ $activity['id'] }}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <label>{{ __('messages.category') }}</label>
                                                                            <select name="group_id"
                                                                                class="form-select" required>
                                                                                <option value="" disabled
                                                                                    selected>
                                                                                    {{ __('messages.select_category') }}
                                                                                </option>
                                                                                @foreach ($eventGroups as $group)
                                                                                    <option
                                                                                        value="{{ $group['id'] }}">
                                                                                        {{ $group['group_' . $lang] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <label>{{ __('messages.activity_report') }}</label>
                                                                            <textarea name="report" class="form-control" rows="10"
                                                                                placeholder="{{ __('messages.activity_report_placeholder') }}" required></textarea>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <label>{{ __('messages.images') }}</label>
                                                                            <input type="file" name="images[]"
                                                                                class="form-control" accept="image/*"
                                                                                multiple
                                                                                onchange="previewImages(event, '{{ $activity['id'] }}')">
                                                                        </div>
                                                                        <div id="preview-{{ $activity['id'] }}"
                                                                            class="d-flex flex-wrap mt-2"></div>
                                                                    </div>
                                                                    <input type="hidden" name="title"
                                                                        value="{{ $activity['name_th'] }}">
                                                                    <input type="hidden" name="events_start"
                                                                        value="{{ $activity['events_start'] }}">
                                                                    <input type="hidden" name="events_stop"
                                                                        value="{{ $activity['events_stop'] }}">
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div>
                                                                    <button class="btn p-2" style="font-size: 12px"
                                                                        onclick="document.getElementById('addReportActivityForm-{{ $activity['id'] }}').submit()">
                                                                        {{ __('messages.submit_report_button') }}
                                                                    </button>
                                                                    <button class="btn btn-border p-2"
                                                                        style="font-size: 12px"
                                                                        data-bs-dismiss="modal">
                                                                        {{ __('messages.cancel') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($activity['reported'])
                                                    <div class="modal fade"
                                                        id="viewReportActivityModal-{{ $activity['id'] }}">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">
                                                                        {{ __('messages.report') }}:
                                                                        {{ $activity['name_' . $lang] }}</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="mb-1">
                                                                        <strong>{{ __('messages.report_date') }}:</strong>
                                                                        {{ \Carbon\Carbon::parse($activity['report']->create_date)->format('d/m/Y - H:i') }}
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <strong>{{ __('messages.category') }}:</strong>
                                                                        {{ $activity['report']->events_group->{$lang} }}
                                                                    </p>
                                                                    <p class="mb-1 text-break">
                                                                        <strong>{{ __('messages.details') }}:</strong>
                                                                        {{ $activity['report']->detail_report }}
                                                                    </p>

                                                                    @if (!empty($activity['report']->images) && is_iterable($activity['report']->images))
                                                                        <div class="report-images d-flex flex-wrap">
                                                                            @foreach ($activity['report']->images as $img)
                                                                                <a href="{{ checkImageUrl('storage/images/reports/' . $img) }}"
                                                                                    target="_blank">
                                                                                    <img src="{{ checkImageUrl('storage/images/reports/' . $img) }}"
                                                                                        alt="{{ __('messages.report_image') }}"
                                                                                        loading="lazy"
                                                                                        class="border rounded me-2 mb-2"
                                                                                        style="width: 100px; height: 100px; object-fit: cover;">
                                                                                </a>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-border p-2"
                                                                        style="font-size: 12px"
                                                                        data-bs-dismiss="modal">
                                                                        {{ __('messages.close') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="modal fade" id="confirmLeaveModal-{{ $activity['id'] }}"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    {{ __('messages.cancel_join') }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="leaveForm-{{ $activity['id'] }}"
                                                                    action="{{ route('activities.leave', $activity['id']) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    {{ __('messages.confirm_cancel_text', ['name' => $activity['name_' . $lang]]) }}
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">
                                                                    {{ __('messages.cancel') }}
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                    onclick="document.getElementById('leaveForm-{{ $activity['id'] }}').submit()">
                                                                    {{ __('messages.confirm') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-5">
                                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                                <p class="text-muted mt-3">{{ __('messages.no_report_yet') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($user->register_status->id == 4 && session('user.id') == $user->id)
                            <div class="tab-content p-2 p-lg-4" style="padding: 40px" data-tab="report">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6><i class="bi bi-journal-text"></i> {{ __('messages.report_activity') }}</h6>
                                    <button class="btn p-2" style="font-size: 12px" data-bs-toggle="modal"
                                        data-bs-target="#addReportModal">
                                        <span>{{ __('messages.add_report') }}</span>
                                    </button>
                                </div>

                                @php
                                    $reports = isset($user->report['2']) ? (array) $user->report['2'] : [];
                                @endphp

                                @forelse ($reports as $index => $report)
                                    <div class="border p-4 rounded mb-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="text-primary text-truncate mb-2">
                                                    {{ $report->content }}
                                                </h4>
                                                <div class="mb-2 text-truncate" style="max-width: 100%;">
                                                    {{ $report->detail_report }}
                                                </div>
                                                <div>
                                                    <small><i class="bi bi-calendar-event"></i>
                                                        <span>{{ __('messages.report_date') }}:
                                                            {{ \Carbon\Carbon::parse($report->create_date)->format('d/m/Y') }}</span>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn p-2 flex align-items-center mt-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewReportModal-{{ $index }}"
                                                    style="font-size: 12px">
                                                    <i class="bi bi-eye"></i>
                                                    <span>{{ __('messages.view_report') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="viewReportModal-{{ $index }}">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $report->content }}</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-1">
                                                        <strong>{{ __('messages.report_date') }}:</strong>
                                                        {{ \Carbon\Carbon::parse($report->create_date)->format('d/m/Y - H:i') }}
                                                    </p>
                                                    <p class="mb-1"><strong>{{ __('messages.category') }}:</strong>
                                                        {{ $report->events_group->{$lang} }}</p>
                                                    <p class="mb-1"><strong>{{ __('messages.type') }}:</strong>
                                                        {{ $report->events_type->{$lang} }}</p>
                                                    <p class="mb-1 text-break">
                                                        <strong>{{ __('messages.details') }}:</strong>
                                                        {{ $report->detail_report }}
                                                    </p>

                                                    @if (!empty($report->images) && is_iterable($report->images))
                                                        <div class="report-images d-flex flex-wrap">
                                                            @foreach ($report->images as $img)
                                                                <a href="{{ checkImageUrl('storage/images/reports/' . $img) }}"
                                                                    target="_blank">
                                                                    <img src="{{ checkImageUrl('storage/images/reports/' . $img) }}"
                                                                        alt="{{ __('messages.report_image') }}"
                                                                        class="border rounded me-2 mb-2"
                                                                        loading="lazy"
                                                                        style="width: 100px; height: 100px; object-fit: cover;">
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-border p-2"
                                                        style="font-size: 12px" data-bs-dismiss="modal">
                                                        {{ __('messages.close') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-3">{{ __('messages.no_report_yet') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('user') && $user->id == session('user.id'))
        <div class="modal fade" id="changePasswordModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.change_password') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.update') }}" method="post" id="editPasswordForm">
                            @csrf
                            @method('PUT')

                            @php
                                $hasPassword = !empty($user->password);
                            @endphp

                            <div class="row">
                                @if ($hasPassword)
                                    <div class="col-12">
                                        <div class="form-field">
                                            <label>{{ __('messages.current_password') }} <span
                                                    style="color:red">*</span></label>
                                            <input class="form-control password" type="password"
                                                name="current_password" required />
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-sm-6 col-12">
                                    <div class="form-field">
                                        <label>{{ __('messages.new_password') }} <span
                                                style="color:red">*</span></label>
                                        <input class="form-control password" type="password" name="new_password"
                                            required />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-6 col-12">
                                    <div class="form-field">
                                        <label>{{ __('messages.confirm_password') }} <span
                                                style="color:red">*</span></label>
                                        <input class="form-control password" type="password" name="confirm_password"
                                            required />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-2" style="font-size: 12px"
                            onclick="document.getElementById('editPasswordForm').submit()">
                            {{ __('messages.save_changes') }}
                        </button>
                        <button type="button" class="btn btn-border p-2" style="font-size: 12px"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('user') && $user->id == session('user.id'))
        <div class="modal fade" id="editProfileModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.edit_profile') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.update') }}" id="editProfileForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-field">
                                        <label>{{ __('messages.first_name_th') }} <span
                                                style="color:red">*</span></label>
                                        <input class="form-control" type="text" name="fname_th" required
                                            value="{{ $user->fname_th ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-field">
                                        <label>{{ __('messages.last_name_th') }} <span
                                                style="color:red">*</span></label>
                                        <input class="form-control" type="text" name="lname_th" required
                                            value="{{ $user->lname_th ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-field">
                                        <label>{{ __('messages.first_name_en') }} <span
                                                style="color:red">*</span></label>
                                        <input class="form-control" type="text" name="fname_en" required
                                            value="{{ $user->fname_en ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-field">
                                        <label>{{ __('messages.last_name_en') }} <span
                                                style="color:red">*</span></label>
                                        <input class="form-control" type="text" name="lname_en" required
                                            value="{{ $user->lname_en ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="form-field">
                                        <label>{{ __('messages.citizen_id') }} <span
                                                style="color:red">*</span></label>
                                        <input type="text" name="citizen_id" class="form-control"
                                            value="{{ $user->idcard ?? '' }}"
                                            {{ !empty($user->idcard) ? 'disabled' : '' }} required />
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="form-field">
                                        <label>{{ __('messages.email') }}</label>
                                        <input class="form-control" type="text" name="email"
                                            value="{{ $user->email ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="form-field">
                                        <label>{{ __('messages.phone') }}</label>
                                        <input class="form-control" type="text" name="phone"
                                            value="{{ $user->phone ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="form-field">
                                        <label>{{ __('messages.province') }} <span style="color:red">*</span></label>
                                        <select name="province_id" id="province" class="form-select"
                                            style="height:50px" required>
                                            <option disabled {{ empty($user->provinces->id) ? 'selected' : '' }}>
                                                {{ __('messages.select') }}
                                            </option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province['id'] }}"
                                                    {{ isset($user->provinces->id) && $user->provinces->id == $province['id'] ? 'selected' : '' }}>
                                                    {{ $province['name_' . $lang] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="form-field">
                                        <label>{{ __('messages.district') }} <span style="color:red">*</span></label>
                                        <select name="district_id" id="district" class="form-select"
                                            style="height:50px" required>
                                            <option disabled {{ empty($user->district_id) ? 'selected' : '' }}>
                                                {{ __('messages.select') }}
                                            </option>
                                            @php
                                                $filteredDistricts = array_filter($districts, function ($d) use (
                                                    $user,
                                                ) {
                                                    return isset($user->provinces->id) &&
                                                        $d['province_id'] == $user->provinces->id;
                                                });
                                            @endphp
                                            @foreach ($filteredDistricts as $district)
                                                <option value="{{ $district['id'] }}"
                                                    {{ isset($user->amphoes->id) && $user->amphoes->id == $district['id'] ? 'selected' : '' }}>
                                                    {{ $district['name_' . $lang] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="form-field">
                                        <label>{{ __('messages.sub_district') }} <span
                                                style="color:red">*</span></label>
                                        <select name="sub_district_id" id="sub-district" class="form-select"
                                            style="height:50px" required>
                                            <option disabled {{ empty($user->districts->id) ? 'selected' : '' }}>
                                                {{ __('messages.select') }}
                                            </option>
                                            @php
                                                $filteredSubDistricts = array_filter($subdistricts, function ($s) use (
                                                    $user,
                                                ) {
                                                    return isset($user->amphoes->id) &&
                                                        $s['amphure_id'] == $user->amphoes->id;
                                                });
                                            @endphp
                                            @foreach ($filteredSubDistricts as $subdistrict)
                                                <option value="{{ $subdistrict['id'] }}"
                                                    {{ isset($user->districts->id) && $user->districts->id == $subdistrict['id'] ? 'selected' : '' }}>
                                                    {{ $subdistrict['name_' . $lang] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-field">
                                        <label>{{ __('messages.address') }} <span style="color:red">*</span></label>
                                        <input class="form-control" type="text" name="address" required
                                            value="{{ $user->address_th ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-2" style="font-size: 12px"
                            onclick="document.getElementById('editProfileForm').submit()">
                            {{ __('messages.save_changes') }}
                        </button>
                        <button type="button" class="btn btn-border p-2" style="font-size: 12px"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('user') && $user->id == session('user.id'))
        <div class="modal fade" id="editProfilePicModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.edit_profile_picture') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data"
                            id="editProfilePicForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-6">
                                    <div
                                        style="position: relative; width: 100%; height: 525px; overflow: hidden; border: 1px solid #ccc; border-radius: 8px; cursor: pointer;">
                                        <img src="{{ $userImage }}" id="imagePreview"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                            alt="{{ __('messages.profile_picture') }}">
                                    </div>
                                    <input type="file" name="profile_pic" id="inputImage" accept="image/*"
                                        style="margin-top: 10px;" class="form-control">
                                </div>
                                <div class="col-12 col-md-12 col-lg-6">
                                    <p>
                                        <strong>{{ __('messages.note') }}</strong>
                                        {{ __('messages.profile_picture_note') }}
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-2" style="font-size: 12px"
                            onclick="document.getElementById('editProfilePicForm').submit()">
                            {{ __('messages.save_changes') }}
                        </button>
                        <button type="button" class="btn btn-border p-2" style="font-size: 12px"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('user') && $user->id == session('user.id'))
        <div class="modal fade" id="editSkillModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.edit_skills') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.update') }}" method="POST" id="editSkillForm">
                            @csrf
                            @method('PUT')

                            @php
                                $userSkillIds = [];
                                if (isset($user->skill_lv1)) {
                                    foreach ($user->skill_lv1 as $lv1) {
                                        foreach ($lv1['lv2'] as $lv2_id => $lv2) {
                                            if ($lv2 instanceof \MongoDB\Model\BSONDocument) {
                                                $lv2 = (array) $lv2;
                                            }
                                            $userSkillIds[] = $lv2['id'] ?? $lv2_id;
                                        }
                                    }
                                }
                            @endphp

                            <ul class="accordion-list">
                                @foreach ($skill_lv1 as $lv1)
                                    @php
                                        if ($lv1 instanceof \MongoDB\Model\BSONDocument) {
                                            $lv1 = (array) $lv1;
                                        }
                                        $lv2_list = $lv1['lv2'] ?? [];
                                    @endphp
                                    <li class="accordion-item section-bgc">
                                        <div class="accordion-trigger">{{ $lv1['name_' . $lang] ?? '' }}</div>
                                        <div class="accordion-content content">
                                            @foreach ($lv2_list as $lv2_id => $lv2)
                                                @php
                                                    if ($lv2 instanceof \MongoDB\Model\BSONDocument) {
                                                        $lv2 = (array) $lv2;
                                                    }
                                                @endphp
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="skills[]"
                                                        value="{{ $lv2['id'] ?? $lv2_id }}"
                                                        {{ in_array($lv2['id'] ?? $lv2_id, $userSkillIds) ? 'checked' : '' }}>
                                                    <label
                                                        class="form-check-label">{{ $lv2['name_' . $lang] ?? '' }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-2" style="font-size: 12px"
                            onclick="document.getElementById('editSkillForm').submit()">
                            {{ __('messages.save_changes') }}
                        </button>
                        <button type="button" class="btn btn-border p-2" style="font-size: 12px"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('user') && $user->id == session('user.id'))
        <div class="modal fade" id="editJobModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.edit_other_expertise') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.update') }}" method="POST" id="editJobForm">
                            @csrf
                            @method('PUT')
                            <textarea name="job" id="job" class="form-control" rows="10"
                                placeholder="{{ __('messages.other_expertise_placeholder') }}">{!! $user->job ?? '' !!}</textarea>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-2" style="font-size: 12px"
                            onclick="document.getElementById('editJobForm').submit()">
                            {{ __('messages.save_changes') }}
                        </button>
                        <button type="button" class="btn btn-border p-2" style="font-size: 12px"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('user') && $user->id == session('user.id'))
        <div class="modal fade" id="editExperienceModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.edit_volunteer_experience') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.update') }}" method="POST" id="editExperienceForm">
                            @csrf
                            @method('PUT')
                            <textarea name="experience" id="experience" class="form-control" rows="10"
                                placeholder="{{ __('messages.volunteer_experience_placeholder') }}">{!! $user->experience ?? '' !!}</textarea>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-2" style="font-size: 12px"
                            onclick="document.getElementById('editExperienceForm').submit()">
                            {{ __('messages.save_changes') }}
                        </button>
                        <button type="button" class="btn btn-border p-2" style="font-size: 12px"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('user') && $user->id == session('user.id'))
        <div class="modal fade" id="editTrainingModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.edit_training_history') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.update') }}" method="POST" id="editTrainingForm">
                            @csrf
                            @method('PUT')
                            <textarea name="training" id="training" class="form-control" rows="10"
                                placeholder="{{ __('messages.training_history_placeholder') }}">{!! $user->training ?? '' !!}</textarea>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-2" style="font-size: 12px"
                            onclick="document.getElementById('editTrainingForm').submit()">
                            {{ __('messages.save_changes') }}
                        </button>
                        <button type="button" class="btn btn-border p-2" style="font-size: 12px"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('user') && $user->id == session('user.id'))
        <div class="modal fade" id="addReportModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.add_activity_report') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('report.submit') }}" method="POST" enctype="multipart/form-data"
                            id="addReportForm">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <label>{{ __('messages.report_title') }}</label>
                                    <input type="text" name="title"
                                        placeholder="{{ __('messages.report_title_placeholder') }}"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>{{ __('messages.category') }}</label>
                                    <select name="group_id" class="form-select" required style="height: 50px">
                                        <option value="" disabled selected>{{ __('messages.select_category') }}
                                        </option>
                                        @foreach ($eventGroups as $group)
                                            <option value="{{ $group['id'] }}">
                                                {{ $group['group_' . $lang] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>{{ __('messages.type') }}</label>
                                    <select name="type_id" class="form-select" required style="height: 50px">
                                        <option value="" disabled selected>{{ __('messages.select_type') }}
                                        </option>
                                        @foreach ($eventTypes as $type)
                                            <option value="{{ $type['id'] }}">
                                                {{ $type['type_' . $lang] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label>{{ __('messages.activity_report') }}</label>
                                    <textarea name="report" class="form-control" rows="10"
                                        placeholder="{{ __('messages.activity_report_placeholder') }}" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label>{{ __('messages.images') }}</label>
                                    <input type="file" name="images[]" class="form-control" accept="image/*"
                                        multiple onchange="previewImages(event, 'newReport')">
                                </div>
                                <div id="preview-newReport" class="d-flex flex-wrap mt-2"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-2" style="font-size: 12px" value="submit"
                            name="status" onclick="document.getElementById('addReportForm').submit()">
                            {{ __('messages.save_and_send_report') }}
                        </button>
                        <button type="button" class="btn btn-border p-2" style="font-size: 12px"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function previewImages(event, id) {
            const preview = document.getElementById(`preview-${id}`);
            if (!preview) {
                console.error("ไม่พบ preview div:", `preview-${id}`);
                return;
            }

            preview.innerHTML = "";
            const files = event.target.files;

            if (files.length > 5) {
                alert("สามารถอัพโหลดได้สูงสุด 5 รูปเท่านั้น");
                event.target.value = "";
                return;
            }

            Array.from(files).forEach(file => {
                if (!file.type.startsWith("image/")) {
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert(`ไฟล์ ${file.name} มีขนาดเกิน 2MB`);
                    event.target.value = "";
                    preview.innerHTML = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.classList.add("m-1", "border", "rounded");
                    img.style.width = "100px";
                    img.style.height = "100px";
                    img.style.objectFit = "cover";
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }


        document.addEventListener('DOMContentLoaded', function() {

            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            function openTab(tabName) {
                tabContents.forEach(tab => {
                    tab.style.display = (tab.dataset.tab === tabName) ? 'block' : 'none';
                });

                tabLinks.forEach(link => {
                    link.classList.toggle('active', link.dataset.tab === tabName);
                });
            }

            let activeTab = localStorage.getItem('activeTab');

            if (activeTab && document.querySelector(`.tab-link[data-tab="${activeTab}"]`)) {
                openTab(activeTab);
            } else {
                if (tabLinks.length > 0) {
                    openTab(tabLinks[0].dataset.tab);
                }
            }

            tabLinks.forEach(link => {
                link.addEventListener('click', () => {
                    const tabName = link.dataset.tab;
                    openTab(tabName);
                    localStorage.setItem('activeTab', tabName);
                });
            });


            const provinces = @json($provinces);
            const districts = @json($districts);
            const subDistricts = @json($subdistricts);

            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const subDistrictSelect = document.getElementById('sub-district');

            provinceSelect.addEventListener('change', function() {
                const pid = parseInt(this.value)
                const filteredDistricts = districts.filter(district => parseInt(district.province_id) ===
                    pid)

                districtSelect.innerHTML =
                    '<option selected disabled>{{ __('messages.select') }}</option>';
                filteredDistricts.forEach(district => {
                    districtSelect.innerHTML +=
                        `<option value="${district.id}">${district.name_{{ $lang }}}</option>`
                });

                subDistrictSelect.innerHTML =
                    '<option selected disabled>{{ __('messages.select') }}</option>';

            })

            districtSelect.addEventListener('change', function() {
                const did = parseInt(this.value)
                const filteredSubDistricts = subDistricts.filter(sd => parseInt(sd.amphure_id) === did)

                subDistrictSelect.innerHTML =
                    '<option selected disabled>{{ __('messages.select') }}</option>';
                filteredSubDistricts.forEach(sd => {
                    subDistrictSelect.innerHTML +=
                        `<option value="${sd.id}">${sd.name_{{ $lang }}}</option>`;
                })
            })

            const imagePreview = document.getElementById('imagePreview')
            const inputImage = document.getElementById('inputImage')

            imagePreview.addEventListener('click', function() {
                inputImage.click()
            })

            inputImage.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.type.startsWith('image/')) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('ขนาดไฟล์ไม่เกิน 2MB');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });




            const searchBoxInput = document.querySelector('#searchBox input');
            const resultsBox = document.getElementById('resultsBox');

            let timeout = null;

            searchBoxInput.addEventListener('input', () => {
                clearTimeout(timeout);
                const query = searchBoxInput.value.trim();

                if (!query) {
                    resultsBox.style.display = 'none';
                    resultsBox.innerHTML = '';
                    return;
                }

                const translations = {
                    no_results: @json(__('messages.no_results')),
                    already_joined: @json(__('messages.already_joined')),
                    join_activity: @json(__('messages.join_activity')),
                    start_date: @json(__('messages.start_date')),
                    end_date: @json(__('messages.end_date')),
                };


                timeout = setTimeout(() => {
                    fetch(`/api/activities?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            resultsBox.innerHTML = '';

                            if (data.results.length === 0) {
                                resultsBox.innerHTML = `
                            <div class="text-center p-2">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mb-0">${translations.no_results}</p>
                            </div>`;
                            } else {
                                data.results.forEach((activity, index) => {
                                    const card = document.createElement('div');
                                    card.classList.add('mb-2');

                                    const link =
                                        `{{ route('content', ['id' => '__ID__']) }}`
                                        .replace('__ID__', activity.id);

                                    card.innerHTML = `
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-md-8 mb-2 mb-md-0">
                                            <a href="${link}" style="color: black;" target="_blank">
                                                <h5 style="font-size: 16px" class="text-truncate mb-1">${activity.title}</h5>
                                            </a>
                                            <div class="text-muted small" style="font-size:12px">
                                                <div class="mb-1">
                                                    <i class="bi bi-calendar-check me-2"></i>${translations.start_date} ${activity.start_date || '-'}
                                                </div>
                                                <div>
                                                    <i class="bi bi-calendar-x me-2"></i>${translations.end_date} ${activity.stop_date || '-'}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 d-flex justify-content-end">
                                            ${activity.joined
                                                ? `<p class="text-success text-center mb-0" style="font-size:12px">
                                                                            <i class="bi bi-check-circle"></i> ${translations.already_joined}
                                                                        </p>`
                                                : `<form action="/activities/join" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="activity_id" value="${activity.id}">
                                                                            <button type="submit" class="btn btn-primary p-2" style="font-size:12px">
                                                                                <i class="bi bi-box-arrow-in-right"></i> ${translations.join_activity}
                                                                            </button>
                                                                        </form>`
                                            }
                                        </div>
                                    </div>
                                </div>`;

                                    resultsBox.appendChild(card);

                                    if (index < data.results.length - 1) {
                                        const hr = document.createElement('hr');
                                        hr.classList.add('my-2');
                                        resultsBox.appendChild(hr);
                                    }
                                });
                            }

                            resultsBox.style.display = 'flex';
                        });
                }, 300);
            });


            document.addEventListener('click', function(e) {
                if (!searchBoxInput.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.style.display = 'none';
                }
            });

        });
    </script>


</x-layout>
