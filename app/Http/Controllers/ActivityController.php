<?php

namespace App\Http\Controllers;

use App\Services\MongoService;
use DateTime;
use Illuminate\Http\Request;

use function MongoDB\object;

class ActivityController extends Controller
{
    protected $mongo;

    public function __construct(MongoService $mongo)
    {
        $this->mongo = $mongo;
    }
    public function index(Request $request)
    {
        $perPage = 12;
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $perPage;
        $lang = app()->getLocale();

        $activitiesCursor = $this->mongo->find(
            'content',
            ['menusub_id' => 4],
            [
                'skip' => $skip,
                'limit' => $perPage,
                'sort' => ['_id' => -1]
            ]
        );

        $activities = iterator_to_array($activitiesCursor);

        $currentUser = $this->mongo->findOne('user', ['email' => session('user.email')]);
        $userActiveList = $currentUser['active_list'] ?? [];

        $activeIds = [];
        foreach ($userActiveList as $active) {
            if (isset($active['id'])) {
                $activeIds[] = $active['id'];
            }
        }

        $activities = array_map(function ($activity) use ($activeIds) {
            $activity['joined'] = in_array($activity['id'], $activeIds);
            return $activity;
        }, $activities);

        $events = array_map(function ($activity) use ($lang) {
            $start = isset($activity['start_date']) ? new DateTime($activity['start_date']) : new DateTime();
            $end = isset($activity['stop_date']) ? new DateTime($activity['stop_date']) : null;

            return [
                'id'    => $activity['id'],
                'title' => $activity['title_' . $lang] ?? '',
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end'   => $end ? $end->format('Y-m-d\TH:i:s') : null,
                'color' => $activity['color'] ?? '#0a3d62',
            ];
        }, $activities);

        $total = $this->mongo->collection('content')->countDocuments(['menusub_id' => 4]);
        $totalPages = ceil($total / $perPage);

        // dd($activities);

        // ส่ง $activities ไป Blade
        return view('activities', compact('activities', 'events', 'page', 'totalPages'));
    }

    public function join(Request $request)
    {
        $activityId = intval($request->activity_id);

        $activity = $this->mongo->findOne('content', ['id' => $activityId]);

        if (!$activity) {
            return redirect()->back()->with('error', 'กิจกรรมไม่ถูกต้อง');
        }

        // dd($activity);

        $userId = session('user.id');

        $user = $this->mongo->findOne('user', ['id' => $userId]);
        if (!$user) {
            return redirect()->back()->with('error', 'ไม่พบผู้ใช้');
        }

        $activityList = isset($user['active_list']) ? (array) $user['active_list'] : [];

        // เพิ่มกิจกรรมใหม่
        $activityList[] = [
            'name_th' => $activity->title_th ?? null,
            'name_en' => $activity->title_en ?? null,
            'type' => null,
            'events_start' => $activity->start_date ?? null,
            'events_stop' => $activity->stop_date ?? null,
            'events_type' =>  null,
            'id' => $activityId,
            'join_date' => now()->toDateTimeString()
        ];

        $this->mongo->updateOne(
            'user',
            ['id' => $userId],
            ['$set' => ['active_list' => (object) $activityList]],
            [],
            true
        );

        return redirect()->back()->with('success', 'สมัครเข้าร่วมกิจกรรมเรียบร้อยแล้ว');
    }

    public function leave($id)
    {
        $userId = session('user.id');

        $user = $this->mongo->findOne('user', ['id' => $userId]);
        if (!$user) {
            return redirect()->back()->with('error', 'ไม่พบผู้ใช้');
        }

        $activityList = isset($user['active_list']) ? (array) $user['active_list'] : [];

        $activityList = array_filter($activityList, function ($activity) use ($id) {
            $activityArray = (array) $activity;
            return $activityArray['id'] != $id;
        });

        $activityList = array_values($activityList);

        $this->mongo->updateOne(
            'user',
            ['id' => $userId],
            ['$set' => ['active_list' => (object) $activityList]],
            [],
            true
        );

        return redirect()->back()->with('success', 'ยกเลิกการเข้าร่วมกิจกรรมเรียบร้อยแล้ว');
    }
}
