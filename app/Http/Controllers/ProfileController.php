<?php

namespace App\Http\Controllers;

use App\Services\MongoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
use Intervention\Image\Laravel\Facades\Image;

class ProfileController extends Controller
{
    protected $mongo;

    public function __construct(MongoService $mongo)
    {
        $this->mongo = $mongo;
    }
    public function index(Request $request, $id = null)
    {
        $lang = app()->getLocale();
        $authUser = session()->get('user');

        if ($id) {
            $user = $this->mongo->findOne('user', ['id' => intval($id)]);
            if (!$user) abort(404);
        } else {
            if (!$authUser) abort(401);
            $user = $this->mongo->findOne('user', ['id' => intval($authUser['id'])]);
            if (!$user) abort(404);
        }
        // แปลง status_register
        if (!empty($user->status_register) && ($user->status_register instanceof \MongoDB\Model\BSONDocument || $user->status_register instanceof \MongoDB\Model\BSONArray)) {
            $statusArray = $user->status_register->getArrayCopy();
            $firstKey = array_key_first($statusArray);
            $firstStatus = $statusArray[$firstKey];

            $user->register_status = (object)[
                'id' => $firstKey,
                'name_th' => $firstStatus['name_th'] ?? null,
                'name_en' => $firstStatus['name_en'] ?? null,
                'color' => $firstStatus['color'] ?? null
            ];
        } elseif (is_array($user->status_register)) {
            $firstKey = array_key_first($user->status_register);
            $firstStatus = $user->status_register[$firstKey] ?? [];
            $user->register_status = (object)[
                'id' => $firstKey,
                'name_th' => $firstStatus['name_th'] ?? null,
                'name_en' => $firstStatus['name_en'] ?? null,
                'color' => $firstStatus['color'] ?? null
            ];
        } else {
            $user->register_status = null;
        }

        // ข้อมูลจังหวัด / อำเภอ / ตำบล
        $provinces = iterator_to_array($this->mongo->find('master_provinces', [], ['sort' => ['name_' . $lang => 1]]), false);
        $districts = iterator_to_array($this->mongo->find('master_amphures'), false);
        $subdistricts = iterator_to_array($this->mongo->find('master_districts'), false);

        // ข้อมูล skill
        $skill_lv1 = iterator_to_array($this->mongo->find('skill_lv1'), false);
        $skill_lv2 = iterator_to_array($this->mongo->find('skill_lv2'), false);
        $lv2_grouped = [];
        foreach ($skill_lv2 as $lv2) {
            $lv2_grouped[$lv2['lv1_id']][] = $lv2;
        }
        foreach ($skill_lv1 as &$lv1) {
            $lv1['lv2'] = $lv2_grouped[$lv1['id']] ?? [];
        }

        // ดึง array ของ activity IDs ที่ user เข้าร่วม
        $joinedIds = [];
        if (!empty($user->active_list) && ($user->active_list instanceof \MongoDB\Model\BSONDocument || is_array($user->active_list))) {
            $activeList = (array) $user->active_list;

            foreach ($activeList as $activity) {
                if (isset($activity['id'])) {
                    $joinedIds[] = (int)$activity['id'];
                }
            }
        }

        $joinedActivities = isset($user->active_list) ? (array)$user->active_list : [];
        $searchActivities = [];

        if ($request->filled('search')) {
            $keyword = $request->search;

            $searchActivities = iterator_to_array($this->mongo->find('content', [
                '$and' => [
                    ['menusub_id' => 4],
                    ['$or' => [
                        ['title_th' => ['$regex' => $keyword, '$options' => 'i']],
                        ['title_en' => ['$regex' => $keyword, '$options' => 'i']]
                    ]]
                ]
            ]), false);
        }

        $today = date('Y-m-d\T00:00:00');
        $tomorrow = date('Y-m-d\T23:59:59');

        $todayActivities = iterator_to_array(
            $this->mongo->find('content', [
                'menusub_id' => 4,
                'start_date' => ['$lte' => $tomorrow],
                'stop_date' => ['$gte' => $today],
            ]),
            false
        );

        foreach ($todayActivities as &$activity) {
            $activityId = isset($activity['id']) ? (int)$activity['id'] : null;
            $activity['joined'] = in_array($activityId, $joinedIds);
        }

        // Event Groups / Types
        $eventGroups = iterator_to_array($this->mongo->find('events_group', [], ['sort' => ['id' => 1]]), false);
        $eventTypes = iterator_to_array($this->mongo->find('events_type', [], ['sort' => ['id' => 1]]), false);

        return view('profile', compact(
            'user',
            'provinces',
            'districts',
            'subdistricts',
            'skill_lv1',
            'joinedActivities',
            'searchActivities',
            'eventGroups',
            'eventTypes',
            'joinedIds',
            'todayActivities'
        ));
    }

    public function showCard($id = null)
    {
        if ($id) {
            $user = $this->mongo->findOne('user', ['id' => intval($id)]);
        } else {
            $authUser = session()->get('user');

            if (!$authUser) {
                return redirect()->route('login')->with('error', __('messages.please_login'));
            }

            $user = $this->mongo->findOne('user', ['id' => intval($authUser['id'])]);
        }

        if (!$user) {
            abort(404);
        }

        return view('arsa-card', compact('user'));
    }

    public function searchActivities(Request $request)
    {
        $lang = app()->getLocale();
        $keyword = $request->query('q', '');

        if (!$keyword) {
            return response()->json(['results' => []]);
        }

        // ดึง user จาก session
        $user = $this->mongo->findOne('user', ['id' => (int)session('user.id')]);


        $activities = iterator_to_array(
            $this->mongo->find('content', [
                '$and' => [
                    ['menusub_id' => 4],
                    ['title_' . $lang => ['$regex' => $keyword, '$options' => 'i']],
                ]
            ]),
            false
        );

        $joinedIds = [];
        if ($user && !empty($user->active_list) && is_iterable($user->active_list)) {
            foreach ($user->active_list as $activityItem) {
                if (isset($activityItem['id'])) {
                    $joinedIds[] = (int)$activityItem['id'];
                }
            }
        }

        // map ให้ง่ายต่อ frontend
        $results = array_map(function ($act) use ($lang, $joinedIds) {
            return [
                'id' => $act->id,
                'title' => $act->{'title_' . $lang} ?? null,
                'start_date' => isset($act->start_date) ? \Carbon\Carbon::parse($act->start_date)->format('d/m/Y') : null,
                'stop_date' => isset($act->stop_date) ? \Carbon\Carbon::parse($act->stop_date)->format('d/m/Y') : null,
                'joined' => in_array((int)$act->id, $joinedIds)
            ];
        }, $activities);

        return response()->json(['results' => $results]);
    }



    public function update(Request $request)
    {

        // dd($request->all());

        $authUser = session()->get('user');
        if (!$authUser) {
            return redirect()->route('login')->with('error', __('messages.please_login'));
        }

        $userId = intval($authUser['id']);
        $user = $this->mongo->findOne('user', ['id' => $userId]);
        $updateData = [];

        if ($request->has('fname_th')) $updateData['fname_th'] = $request->fname_th;
        if ($request->has('lname_th')) $updateData['lname_th'] = $request->lname_th;
        if ($request->has('fname_en')) $updateData['fname_en'] = $request->fname_en;
        if ($request->has('lname_en')) $updateData['lname_en'] = $request->lname_en;
        if ($request->has('email')) $updateData['email'] = $request->email;
        if ($request->has('phone')) $updateData['phone'] = $request->phone;
        if ($request->has('address')) $updateData['address_th'] = $request->address;
        if ($request->has('job')) $updateData['job'] = $request->job;
        if ($request->has('experience')) $updateData['experience'] = $request->experience;
        if ($request->has('training')) $updateData['training'] = $request->training;

        if ($request->has('province_id')) {
            $province = $this->mongo->findOne('master_provinces', ['id' => intval($request->province_id)]);
            if ($province) {
                $updateData['provinces'] = [
                    'id' => $province['id'],
                    'name_th' => $province['name_th'],
                    'name_en' => $province['name_en'],
                ];
            }
        }

        if ($request->has('district_id')) {
            $amphoe = $this->mongo->findOne('master_amphures', ['id' => intval($request->district_id)]);
            if ($amphoe) {
                $updateData['amphoes'] = [
                    'id' => $amphoe['id'],
                    'name_th' => $amphoe['name_th'],
                    'name_en' => $amphoe['name_en'],
                    'province_id' => $amphoe['province_id']
                ];
            }
        }

        if ($request->has('sub_district_id')) {
            $subdistrict = $this->mongo->findOne('master_districts', ['id' => $request->sub_district_id]);
            if ($subdistrict) {
                $updateData['districts'] = [
                    'id' => $subdistrict['id'],
                    'name_th' => $subdistrict['name_th'],
                    'name_en' => $subdistrict['name_en'],
                    'amphure_id' => $subdistrict['amphure_id']
                ];
            }
        }

        if ($request->hasFile('profile_pic') && $request->file('profile_pic')->isValid()) {
            $file = $request->file('profile_pic');
            $filename = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();

            if (!empty($user->user_img) && Storage::disk('public')->exists('images/user/' . $user->user_img)) {
                Storage::disk('public')->delete('images/user/' . $user->user_img);
            }

            $file->storeAs('images/user', $filename, 'public');

            $updateData['user_img'] = $filename;
        }

        if ($request->filled('new_password') && $request->filled('confirm_password')) {
            if (strlen($request->new_password) < 6) {
                return back()->with('error', 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
            }

            if (empty($user['password'])) {
                if ($request->new_password !== $request->confirm_password) {
                    return back()->with('error', 'รหัสผ่านใหม่ไม่ตรงกัน');
                }

                $updateData['password'] = md5(md5(md5($request->new_password)));
            } else {
                if (!$request->filled('current_password')) {
                    return back()->with('error', 'กรุณากรอกรหัสผ่านปัจจุบัน');
                }

                if (md5(md5(md5($request->current_password))) !== $user['password']) {
                    return back()->with('error', 'รหัสผ่านปัจจุบันไม่ถูกต้อง');
                }

                if ($request->new_password !== $request->confirm_password) {
                    return back()->with('error', 'รหัสผ่านใหม่ไม่ตรงกัน');
                }

                $updateData['password'] = md5(md5(md5($request->new_password)));
            }
        }

        $skillIds = $request->input('skills', null);

        if ($skillIds !== null) {
            $skillLv1Data = [];

            foreach ($skillIds as $id) {
                $skill = $this->mongo->findOne('skill_lv2', ['id' => intval($id)]);
                if ($skill) {
                    $lv1_id = (string)$skill['lv1_id'];

                    if (!isset($skillLv1Data[$lv1_id])) {
                        // ดึงชื่อ lv1 จาก collection lv1
                        $lv1 = $this->mongo->findOne('skill_lv1', ['id' => intval($lv1_id)]);
                        $skillLv1Data[$lv1_id] = [
                            'th' => $lv1['name_th'] ?? '',
                            'en' => $lv1['name_en'] ?? '',
                            'lv2' => []
                        ];
                    }

                    $skillLv1Data[$lv1_id]['lv2'][(string)$skill['id']] = [
                        'th' => $skill['name_th'],
                        'en' => $skill['name_en']
                    ];
                }
            }

            $updateData['skill_lv1'] = $skillLv1Data;
        }

        $this->mongo->updateOne('user', ['id' => $userId], $updateData);

        $user = $this->mongo->findOne('user', ['id' => $userId]);
        // บันทึก session
        session()->put('user', [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email ?? '',
            'user_rights' => $user->user_rights,
            'register_status' => session('user.register_status'),
        ]);

        return redirect()->back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }


    public function report(Request $request, $id = null)
    {
        // dd($request->all()); 

        $request->validate([
            'report'   => 'required|string|max:5000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title'    => 'nullable|string|max:255',
            'group_id' => 'nullable|integer',
            'type_id'  => 'nullable|integer',
            'status'   => 'nullable|in:draft,submit',
        ]);


        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }


        $uploadedImages = $request->hasFile('images')
            ? $this->handleUploadImages($request->file('images'))
            : [];

        $userDoc = $this->mongo->findOne('user', ['id' => intval($user['id'])]);

        $typeKey = $id ? "1" : "2";

        $reports = isset($userDoc['report'][$typeKey])
            ? (array)$userDoc['report'][$typeKey]
            : [];

        $nextIndex = count($reports) > 0
            ? (max(array_map('intval', array_keys($reports))) + 1)
            : 0;
        $status = $request->status == "submit" ? 1 : 0;


        if ($typeKey === "1") {
            $newReport = [
                "events_type"   => null,
                "events_group"  => null,
                "detail_report" => $request->report,
                "create_date"   => Carbon::now()->format("Y-m-d H:i:s"),
                "events_start"  => $request->events_start ?? null,
                "events_stop"   => $request->events_stop ?? null,
                "status"        => $status,
                "content"       => (int)$id
            ];
        } else {
            $newReport = [
                "content"       => $request->title ?? "รายงานงานอื่นๆ",
                "events_type"   => null,
                "events_group"  => null,
                "detail_report" => $request->report,
                "create_date"   => Carbon::now()->format("Y-m-d H:i:s"),
                "status"        => $status,
            ];
        }

        if ($request->has('group_id') && $request->group_id) {
            $group = $this->mongo->findOne('events_group', ['id' => intval($request->group_id)]);
            $newReport['events_group'] = $group ? [
                'id' => (string) $group['id'],
                'th' => $group['group_th'],
                'en' => $group['group_en'],
            ] : null;
        }

        if ($typeKey === "2" && $request->has('type_id') && $request->type_id) {
            $type = $this->mongo->findOne('events_type', ['id' => intval($request->type_id)]);
            $newReport['events_type'] = $type ? [
                'id' => (string) $type['id'],
                'th' => $type['type_th'],
                'en' => $type['type_en'],
            ] : null;
        }

        if (!empty($uploadedImages)) {
            $newReport['images'] = $uploadedImages;
        }

        $this->mongo->updateOne(
            'user',
            ['id' => intval($user['id'])],
            [
                '$set' => [
                    "report.$typeKey.$nextIndex" => $newReport
                ]
            ],
            [],
            true
        );

        return redirect()->back()->with('success', 'บันทึกข้อมูลรายงานเรียบร้อย');
    }




    public function deleteReport($key)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $userDoc = $this->mongo->findOne('user', ['id' => intval($user['id'])]);
        if (!$userDoc) {
            return redirect()->back()->with('error', 'ไม่พบผู้ใช้');
        }

        // แยก mainKey-subKey
        [$mainKey, $subKey] = explode('-', $key);

        // ตรวจสอบว่ามี report ตัวนั้นหรือไม่
        if (empty($userDoc['report'][$mainKey][$subKey])) {
            return redirect()->back()->with('error', 'ไม่พบรายงานที่ต้องการลบ');
        }

        $report = $userDoc['report'][$mainKey][$subKey];

        // ลบรูปภาพถ้ามี
        if (!empty($report['images']) && is_array($report['images'])) {
            foreach ($report['images'] as $image) {
                $path = 'images/reports/' . $image;
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        // ลบ report
        $this->mongo->updateOne(
            'user',
            ['id' => intval($user['id'])],
            [
                '$unset' => [
                    "report.$mainKey.$subKey" => 1
                ]
            ],
            [],
            true
        );

        /*  // ลบ key ว่าง (optional)
        $this->mongo->updateOne(
            'user',
            ['id' => intval($user['id'])],
            [
                '$unset' => [
                    "report.$mainKey" => 1
                ]
            ],
            [],
            true
        );
 */
        return redirect()->back()->with('success', 'ลบรายงานเรียบร้อยแล้ว');
    }


    private function handleUploadImages($files, $maxFileSize = 250 * 1024)
    {
        $uploadedImages = [];

        foreach ($files as $file) {
            if ($file->isValid() && str_starts_with($file->getMimeType(), 'image/')) {
                $filename = uniqid() . '-' . time() . '.webp';
                $image = Image::read($file->path());

                if ($image->width() > 1920) {
                    $image->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $quality = 90;
                do {
                    $tempContent = $image->toWebp($quality)->toString();
                    $quality -= 10;
                } while (strlen($tempContent) > $maxFileSize && $quality > 10);

                if (strlen($tempContent) <= $maxFileSize) {
                    Storage::disk('public')->put('images/reports/' . $filename, $tempContent);
                    $uploadedImages[] = $filename;
                }
            }
        }

        return $uploadedImages;
    }
}
