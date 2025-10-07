<x-layout>
    <nav class="bread-crumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="bread-crumbs-list">
                        <li>
                            <a href="index.php">หน้าแรก</a>
                            <i class="material-icons md-18">chevron_right</i>
                        </li>
                        <li><a href="#!">{{ __('messages.about_us') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading heading-center">
                        <div class="section-subheading">รายละเอียด</div>
                        <h1>{{ __('messages.about_us') }}</h1>
                    </div>
                    <div class="content">
                        @php
                            $coverImage = !$content->cover_img ? $content->cover_img : '64106fb48d127-16787987721.png';
                        @endphp
                        {{-- <div class="img-style">
                            <img src="{{ checkImageUrl('storage/images/content/banners/' . $coverImage) }}">
                        </div> --}}

                        {{-- <h2>{{ $content['title_' . $lang] }}</h2> --}}

                        <div>
                            {!! $content['content_' . $lang] !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
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
                                <h3 class="ini-heading item-heading-large">{{ $step['content_' . $lang] }}
                                </h3>
                                <div class="ini-desc">
                                    @if ($step->cover_img)
                                        <img src="{{ asset('/storage/images/steps/' . $step->cover_img) }}"
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
</x-layout>
