<?php

namespace App\Http\Controllers;

use App\Services\MongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $user = $this->mongo->findOne('user', [
            '$or' => [
                ['email' => $request->username],
                ['username' => $request->username]
            ]
        ]);

        if (!$user) {
            Log::channel('user_activity')->info('Login failed: user not found', [
                'ip' => $request->ip(),
                'username' => $request->username,
            ]);
            return redirect()->back()->with('error', __('messages.invalid_credentials'));
        }

        if (md5(md5(md5($request->password))) != $user->password) {
            Log::channel('user_activity')->info('Login failed: invalid password', [
                'ip' => $request->ip(),
                'username' => $user->username,
            ]);
            return redirect()->back()->with('error', __('messages.invalid_credentials'));
        }

        if ($user->status_active == 1) {
            Log::channel('user_activity')->info('Login blocked: account suspended', [
                'ip' => $request->ip(),
                'username' => $user->username,
            ]);
            return redirect()->back()->with('error', __('messages.account_suspended'));
        }

        $statusRegister = $user->status_register;

        // แปลงเป็น array ปกติ
        $statusRegisterArray = $statusRegister instanceof \MongoDB\Model\BSONDocument || $statusRegister instanceof \MongoDB\Model\BSONArray
            ? $statusRegister->getArrayCopy()
            : (array) $statusRegister;

        $firstKey = !empty($statusRegisterArray) ? array_key_first($statusRegisterArray) : null;
        $firstStatus = $firstKey !== null ? $statusRegisterArray[$firstKey] : null;

        // สร้าง object แบบมี id
        $registerStatus = $firstStatus ? (object)[
            'id' => $firstKey,
            'name_th' => $firstStatus['name_th'] ?? null,
            'name_en' => $firstStatus['name_en'] ?? null,
            'color' => $firstStatus['color'] ?? null
        ] : null;

        if ($registerStatus->id == 5 || $registerStatus->id == 6) {
            Log::channel('user_activity')->info('Login blocked: register status invalid', [
                'ip' => $request->ip(),
                'username' => $user->username,
            ]);
            return redirect()->back()->with('error', __('messages.account_suspended'));
        }

        Log::channel('user_activity')->info('Login success', [
            'ip' => $request->ip(),
            'username' => $user->username,
        ]);

        // เก็บใน session
        $request->session()->put('user', [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'user_rights' => $user->user_rights,
            'register_status' => $registerStatus,
        ]);

        return redirect()->route('profile');
    }


    public function showLogin(Request $request)
    {
        return view('login');
    }

    public function register(Request $request)
    {

        $lang = app()->getLocale();
        try {
            $googleUser = $request->session()->get('google_user', null);

            $request->validate([
                'fname_th'    => ($lang == 'th' ? 'required|' : '') . 'string|max:255',
                'lname_th'    => ($lang == 'th' ? 'required|' : '') . 'string|max:255',
                'fname_en'    => 'required|string|max:255',
                'lname_en'    => 'required|string|max:255',
                'idcard'      => 'required|string',
                'phone'       => 'nullable|string|max:10|regex:/^[0-9]+$/',
                'province_id' => 'required|integer',
                'amphure_id'  => 'required|integer',
                'district_id' => 'required|integer',
                'day'         => 'required|integer',
                'month'       => 'required|integer',
                'year'        => 'required|integer',
                'option'      => 'array',
            ], [
                'fname_th.required'    => __('messages.fname_th_required'),
                'lname_th.required'    => __('messages.lname_th_required'),
                'fname_en.required'    => __('messages.fname_en_required'),
                'lname_en.required'    => __('messages.lname_en_required'),
                'idcard.required'      => __('messages.idcard_required'),
                'idcard.size'          => __('messages.idcard_size'),
                'idcard.unique'        => __('messages.idcard_unique'),
                'phone.required'       => __('messages.phone_required'),
                'province_id.required' => __('messages.province_required'),
                'amphure_id.required'  => __('messages.amphure_required'),
                'district_id.required' => __('messages.district_required'),
                'day.required'         => __('messages.day_required'),
                'month.required'       => __('messages.month_required'),
                'year.required'        => __('messages.year_required'),
                'option.array'         => __('messages.option_array'),
            ]);

            $existingUser = $this->mongo->findOne('user', [
                '$or' => [
                    ['idcard' => $request->idcard],
                    ['email' => $request->email]
                ]
            ]);

            if ($existingUser) {
                Log::channel('user_activity')->info('Register failed: Identification is already exist', [
                    'ip' => $request->ip(),
                    'username' => 'guest',
                ]);
                return redirect()->back()->with('error', 'มีผู้ใช้ที่ใช้เลขบัตรประชาชนนี้อยู่แล้ว');
            }

            // วันเกิด
            $year = $request->year;
            $birthday = sprintf('%04d-%02d-%02d', $year, $request->month, $request->day);
            $day   = sprintf('%02d', $request->day);
            $month = sprintf('%02d', $request->month);
            $year  = sprintf('%04d', $request->year);

            // รหัสผ่าน default (md5 ของวันเกิด)
            $pass = md5(md5(md5($day . $month . $year)));

            // ดึง province/amphure/district
            $province = $this->mongo->findOne('master_provinces', ['id' => (int)$request->province_id]);
            $amphoe = $this->mongo->findOne('master_amphures', ['id' => (int)$request->amphure_id]);
            $district = $this->mongo->findOne('master_districts', ['id' => (string)$request->district_id]);

            if (!$province || !$amphoe || !$district) {
                Log::channel('user_activity')->info('Register failed: invalid address data', [
                    'ip' => $request->ip(),
                    'username' => 'guest',
                ]);
                return redirect()->back()->withInput()->with('error', 'Invalid address data');
            }

            // Skill
            $allLv1 = $this->mongo->find('skill_lv1')->toArray();
            $allLv2 = $this->mongo->find('skill_lv2')->toArray();
            $selectedLv2 = $request->option ?? [];

            $skill_lv1 = [];
            foreach ($allLv1 as $lv1) {
                $lv2_items = [];
                foreach ($allLv2 as $lv2) {
                    if ((string)$lv2->lv1_id === (string)$lv1->id && in_array((string)$lv2->id, $selectedLv2)) {
                        $lv2_items[(string)$lv2->id] = [
                            'th' => $lv2->name_th,
                            'en' => $lv2->name_en
                        ];
                    }
                }
                if (!empty($lv2_items)) {
                    $skill_lv1[(string)$lv1->id] = [
                        'th' => $lv1->name_th,
                        'en' => $lv1->name_en,
                        'lv2' => $lv2_items
                    ];
                }
            }

            // เตรียมข้อมูล user
            $data = [
                'id' => time(),
                'consentform' => $request->consentform ?? 0,
                'idcard' => $request->idcard ?? null,
                'fname_th' => $request->fname_th ?? $googleUser->given_name ?? $request->fname_en ?? null,
                'lname_th' => $request->lname_th ?? $googleUser->family_name ?? $request->lname_en ?? null,
                'fname_en' => $request->fname_en ?? null,
                'lname_en' => $request->lname_en ?? null,
                'sex' => isset($request->sex) ? (int)$request->sex : null,
                'email' => $request->email ?? $googleUser->email ?? null,
                'username' => $googleUser->email ?? $request->idcard ?? null,
                'password' => $pass,
                'birthday' => $birthday,
                'phone' => $request->phone ?? null,
                'address_th' => $request->address ?? null,
                'address_en' => null,
                'job' => $request->job ?? null,
                'experience' => $request->experience ?? null,
                'training' => $request->training ?? null,
                'skill_lv1' => (object)$skill_lv1,
                'status_register' => (object)[
                    '0' => [
                        'name_th' => 'สมัครใหม่',
                        'name_en' => 'Newcomer',
                        'color' => '#ff8000'
                    ]
                ],
                'status_active' => 0,
                'user_rights' => 3,
                'active_list' => (object)[],
                'user_img' => null,
                'certificate' => '0000-00-00',
                'create_date' => now()->format('Y-m-d H:i:s'),
                'create_by' => (object)[],
                'exp_date' => null,
                'extend_date' => '0000-00-00 00:00:00',
                'data_old' => 0,
                'qrcode' => null,
                'quiz' => (object)[],
                'amphoes' => $amphoe,
                'provinces' => $province,
                'districts' => $district,
                'google_id' => $googleUser->id ?? null,
                'name' => $googleUser->name ?? null,
            ];

            $this->mongo->insertOne('user', $data);

            Log::channel('user_activity')->info('Register success', [
                'ip' => $request->ip(),
                'username' => $data['username'] ?? 'guest',
            ]);

            $request->session()->forget('google_user');

            return redirect()->route('login.form')->with('success', 'User registered successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }



    public function showSignup(Request $request)
    {

        $lang = app()->getLocale();

        if (!$request->session()->has('consent') || $request->session()->get('consent') !== true) {
            return redirect('/consent');
        }

        $provinces = iterator_to_array($this->mongo->find('master_provinces', [], ['sort' => ['name_' . $lang => 1]]));
        $districts = iterator_to_array($this->mongo->find('master_amphures', [], []));
        $subdistricts = iterator_to_array($this->mongo->find('master_districts', [], []));

        $skillLv1 = iterator_to_array($this->mongo->find('skill_lv1', [], ['sort' => ['orders' => 1]]));
        $skillLv2 = iterator_to_array($this->mongo->find('skill_lv2', [], ['sort' => ['orders' => 1]]));

        return view('signup', compact('provinces', 'districts', 'subdistricts', 'skillLv1', 'skillLv2'));
    }

    public function redirectToGoogle()
    {
        Log::channel('user_activity')->info('Redirect to Google OAuth', [
            'ip' => request()->ip(),
            'username' => 'guest',
        ]);

        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $this->mongo->findOne('user', ['email' => $googleUser->getEmail()]);

        if (!$user) {
            Log::channel('user_activity')->info('Google login new user', [
                'ip' => $request->ip(),
                'username' => $googleUser->email,
            ]);
            $request->session()->put('google_user', $googleUser);
            return redirect()->route('register.form');
        }

        if ($user->status_active == 1) {
            Log::channel('user_activity')->info('Login blocked: account suspended', [
                'ip' => $request->ip(),
                'username' => $user->username,
            ]);
            return redirect()->back()->with('error', __('messages.account_suspended'));
        }

        $statusRegister = $user['status_register'] ?? [];

        $statusRegisterArray = $statusRegister instanceof \MongoDB\Model\BSONDocument
            || $statusRegister instanceof \MongoDB\Model\BSONArray
            ? $statusRegister->getArrayCopy()
            : (array) $statusRegister;

        $firstKey = !empty($statusRegisterArray) ? array_key_first($statusRegisterArray) : null;
        $firstStatus = $firstKey !== null ? $statusRegisterArray[$firstKey] : null;

        $registerStatus = $firstStatus ? (object)[
            'id' => $firstKey,
            'name_th' => $firstStatus['name_th'] ?? null,
            'name_en' => $firstStatus['name_en'] ?? null,
            'color' => $firstStatus['color'] ?? null
        ] : null;

        if ($registerStatus->id == 5 || $registerStatus->id == 6) {
            Log::channel('user_activity')->info('Login blocked: register status invalid', [
                'ip' => $request->ip(),
                'username' => $user->username,
            ]);
            return redirect()->back()->with('error', __('messages.account_suspended'));
        }

        Log::channel('user_activity')->info('Google login success', [
            'ip' => $request->ip(),
            'username' => $user->username,
        ]);

        $request->session()->put('user', [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'user_rights' => $user['user_rights'],
            'register_status' => $registerStatus
        ]);

        return redirect()->route('profile');
    }


    public function logout(Request $request)
    {
        $username = session('user.username', 'guest');

        Log::channel('user_activity')->info('Logout', [
            'ip' => $request->ip(),
            'username' => $username,
        ]);

        $request->session()->forget('user');
        return redirect()->route('login');
    }
}
