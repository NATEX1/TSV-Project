<?php

namespace App\Http\Controllers;

use App\Services\MongoService;
use DateTime;
use Illuminate\Http\Request;

use function MongoDB\object;

class ActivityController extends Controller
{
    
    public function index(Request $request)
    {
        $lang = app()->getLocale();
        $perPage = 12;
        $page = max(1, (int)$request->input('page', 1));
        $skip = ($page - 1) * $perPage;

        // Fetch activities with pagination
        $activitiesCursor = $this->mongo->find(
            'content',
            ['menusub_id' => 4, 'status' => 0],
            [
                'skip' => $skip,
                'limit' => $perPage,
                'sort' => ['_id' => -1]
            ]
        );
        $activities = iterator_to_array($activitiesCursor);

        // Fetch current user and check joined activities
        $currentUser = $this->mongo->findOne('user', ['email' => session('user.email')]);
        $userActiveList = (array)$currentUser['active_list'] ?? [];
        $activeIds = array_map(function ($active) {
            return $active['id'] ?? null;
        }, $userActiveList);

        // Add 'joined' status to activities
        $activities = array_map(function ($activity) use ($activeIds) {
            $activity['joined'] = in_array($activity['id'], $activeIds);
            return $activity;
        }, $activities);

        $allActivities = iterator_to_array($this->mongo->find(
            'content',
            ['menusub_id' => 4, 'status' => 0]
        ));

        // Prepare events for FullCalendar
        $events = array_map(function ($activity) use ($lang) {
            $start = isset($activity['start_date']) ? new DateTime($activity['start_date']) : new DateTime();
            $end = isset($activity['stop_date']) ? new DateTime($activity['stop_date']) : null;

            return [
                'id' => $activity['id'],
                'title' => $activity['title_' . $lang] ?? '',
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end' => $end ? $end->format('Y-m-d\TH:i:s') : null,
                'color' => $activity['color'] ?? '#0a3d62',
            ];
        }, $allActivities);

        // Count total documents for pagination
        $total = $this->mongo->collection('content')->countDocuments(['menusub_id' => 4]);

        // Create LengthAwarePaginator instance
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $activities,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('activities', compact('activities', 'events', 'paginator', 'lang'));
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
            'event_type' =>  $activity->event_type ?? (object)['id' => 4, 'th' => 'กิจกรรมด้านอื่นๆ', 'en' => 'ect.'],
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
