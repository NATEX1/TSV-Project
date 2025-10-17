<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Arsa Card</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    @php
        $user_image;
        if (empty($user->user_img)) {
            $user_image = 'https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png';
        } elseif (filter_var($user->user_img, FILTER_VALIDATE_URL)) {
            $user_image = $user->user_img;
        } else {
            $user_image = checkImageUrl('storage/images/user/' . $user->user_img);
        }

        $author = !empty($user->name_author) ? $user->name_author : __('messages.staff');
    @endphp

    <div
        style="position: relative; text-align: center; color: white; background-color: skyblue; max-width: 260px; width: 100%; margin: 0 auto; ">
        {{-- การ์ดพื้นหลัง --}}
        <img src="{{ checkImageUrl('/storage/images/card/' . $card->card_font) }}" alt="arsa_card" width="260px;"
            style="">

        {{-- รูปผู้ใช้ --}}
        <img src="{{ $user_image }}"
            style="position: absolute; top: 37%; left: 50%; transform: translate(-50%, -50%);
            width:64px; height:64px; 
            border-radius:50%; 
            object-fit: cover;">

        {{-- ข้อมูลชื่อ --}}
        <div
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size:10px; color:black;">
            <p style="margin-bottom:2px;">{{ $user['fname_th'] ?? '' }} &nbsp;&nbsp; {{ $user['lname_th'] ?? '' }}</p>
            <p>{{ $user['fname_en'] ?? '' }} &nbsp;&nbsp; {{ $user['lname_en'] ?? '' }}</p>
        </div>

        {{-- รหัส TSV --}}
        <div
            style="position: absolute; top: 63%; left: 50%; transform: translate(-50%, -50%); font-size:10px; color:black;">
            <p>{{ $user->tsv_id ?? '' }}</p>
        </div>

        {{-- ลายมือชื่อ --}}
        <div
            style="position: absolute; top: 74%; left: 25%; transform: translate(-50%, -50%); font-size:8px; color:black;">
            <p>..........................</p>
        </div>
        <div
            style="position: absolute; top: 77%; left: 25%; transform: translate(-50%, -50%); font-size:8px; color:black;">
            <p>{{ __('messages.signature') }}</p>
        </div>

        {{-- จังหวัด --}}
        <div
            style="position: absolute; top: 80%; left: 25%; transform: translate(-50%, -50%); font-size:8px; color:black;">
            <p>{{ __('messages.province') }} {{ $user->provinces['name_' . $lang] ?? '' }}</p>
        </div>

        {{-- วันหมดอายุ --}}
        @if (!empty($user->exp_date) && $user->exp_date != '0000-00-00')
            <div
                style="position: absolute; top: 83%; left: 25%; transform: translate(-50%, -50%); font-size:8px; color:black;">
                <p>{{ __('messages.expiry_date') }} {{ \Carbon\Carbon::parse($user->exp_date)->format('d/m/Y') }}</p>
            </div>
        @endif


        {{-- ลายเซ็น --}}
        @if (!empty($user->img_sign))
            <img src="{{ asset('images/signature/' . $user->img_sign) }}"
                style="min-width:50px; max-width:50px; position: absolute; top: 70%; left: 72%; transform: translate(-50%, -50%);">
        @endif

        {{-- ข้อมูลผู้อนุมัติ --}}
        <div
            style="position: absolute; top: 74%; left: 70%; transform: translate(-50%, -50%); font-size:8px; color:black;">
            <p>..........................</p>
        </div>
        <div
            style="position: absolute; top: 77%; left: 70%; transform: translate(-50%, -50%); font-size:8px; color:black;">
            <p>{{ $author }}</p>
        </div>
        <div
            style="position: absolute; top: 80%; left: 70%; transform: translate(-50%, -50%); font-size:7px; color:black;">
            <p>{{ __('messages.tourism_department') }}</p>
        </div>
        <div
            style="position: absolute; top: 83%; left: 70%; transform: translate(-50%, -50%); font-size:8px; color:black;">
            <p>{{ __('messages.card_issuer') }}</p>
        </div>
    </div>

</body>

</html>
