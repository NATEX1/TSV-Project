<?php

namespace App\Http\Controllers;

use App\Services\MongoService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use MongoDB\BSON\ObjectId;

class FrontController extends Controller
{


    public function index()
    {

        $banners = iterator_to_array($this->mongo->find(
            'banner',
            ['status' => 1],
            ['sort' => ['orders' => 1]]
        ), false);

        $activities = $this->mongo->find(
            'content',
            ['menusub_id' => 4],
            [
                'limit' => 3,
                'sort' => ['id' => -1]
            ]
        );


        $steps = $this->mongo->find(
            'content',
            ['menusub_id' => 6],
        );

        $users = $this->mongo->find('user', [
            'status_register.4' => ['$exists' => true],
            'status_active' => 0,
            'data_old' => 0
        ], [
            'sort' => ['id' => -1],
            'limit' => 3
        ]);

        $news = $this->mongo->find(
            'content',
            ['menusub_id' => 3],
            [
                'limit' => 2,
                'sort' => ['_id' => -1]
            ]
        );

        // dd($users);
        // dd($banner);
        // dd($activities);

        return view('home', compact('banners', 'activities', 'steps', 'users', 'news'));
    }

    public function contact()
    {
        $contact = $this->mongo->findOne('contact');
        $provinces = $this->mongo->find('provinces', [], ['sort' => ['name_en' => 1]]);
        $provincesCursor = $this->mongo->find(
            'master_provinces',
            [],
            ['sort' => ['name_en' => 1]]
        );

        $provinces = iterator_to_array($provincesCursor);

        $regions = [
            1 => [], // ภาคเหนือ
            2 => [], // ภาคกลาง
            3 => [], // ภาคตะวันออกเฉียงเหนือ
            4 => [], // ภาคตะวันตก
            5 => [], // ภาคตะวันออก
            6 => [], // ภาคใต้
        ];

        foreach ($provinces as $province) {
            $regions[$province->geography_id][] = $province;
        }

        return view('contact', compact('contact', 'regions'));
    }

    public function about()
    {
        $content = $this->mongo->findOne('content', ['menusub_id' => 7]);
        $steps = $this->mongo->find(
            'content',
            ['menusub_id' => 6],
        );
        // dd($content);
        return view('about', compact('content', 'steps'));
    }

    public function course(Request $request)
    {
        $perPage = 12;
        $page = $request->input('page', 1);

        $skip = ($page - 1) * $perPage;

        $newsCursor = $this->mongo->find(
            'content',
            [
                'menusub_id' => 5,
                'status' => 0
            ],
            [
                'skip' => $skip,
                'limit' => $perPage,
                'sort' => ['_id' => -1]
            ]
        );

        $courses = iterator_to_array($newsCursor);

        $total = $this->mongo->collection('content')->countDocuments(['menusub_id' => 3]);
        $totalPages = ceil($total / $perPage);
        return view('course', compact('courses', 'page', 'totalPages'));
    }

    public function consent()
    {
        return view('consent');
    }

    public function submitConsent(Request $request)
    {
        $request->validate([
            'keep' => 'required|in:0,1',
            'expose' => 'required|in:0,1',
        ]);

        $keepConsent = $request->input('keep');
        $exposeConsent = $request->input('expose');

        if ($keepConsent == 1) {
            return redirect()->back()->with('error', true);
        }

        $request->session()->put('consent', true);

        return redirect()->route('register.form');
    }


    public function content($id)
    {
        $content = $this->mongo->findOne('content', ['id' => intval($id)]);

        if (!$content) {
            abort(404);
        }

        if ($content->menusub_id == 4) {
            $user = $this->mongo->findOne('user', ['id' => session('user.id')]);

            $joinedIds = [];

            if (!empty($user->active_list) && ($user->active_list instanceof \MongoDB\Model\BSONDocument || is_array($user->active_list))) {
                $activeList = (array) $user->active_list;

                foreach ($activeList as $activity) {
                    if (isset($activity->id)) {
                        $joinedIds[] = (int) $activity->id;
                    }
                }
            }

            $content->joined = in_array((int) $content->id, $joinedIds);
        }

        return view('content', compact('content'));
    }


    public function search(Request $request)
    {
        $lang = app()->getLocale();

        $provinces = $this->mongo->find('master_provinces', [], [
            'sort' => ['name_' . $lang => 1]
        ]);
        $skillLv1 = iterator_to_array($this->mongo->find(
            'skill_lv1',
            ['id' => ['$in' => [1, 6]]],
            ['sort' => ['orders' => 1]]
        ));
        $skillLv2 = iterator_to_array($this->mongo->find('skill_lv2', [], ['sort' => ['orders' => 1]]));

        $name = $request->input('name');
        $province = $request->input('province');
        $skills = $request->input('option', []);
        $skills = array_map('intval', $skills);

        $page = max(1, (int)$request->input('page', 1));
        $perPage = 9;
        $skip = ($page - 1) * $perPage;

        $filter = [
            'status_register.4' => ['$exists' => true],
            'status_active' => 0,
            'data_old' => 0
        ];

        if ($name) {
            $filter['$or'] = [
                ['fname_th' => ['$regex' => $name, '$options' => 'i']],
                ['lname_th' => ['$regex' => $name, '$options' => 'i']],
                ['fname_en' => ['$regex' => $name, '$options' => 'i']],
                ['lname_en' => ['$regex' => $name, '$options' => 'i']],
            ];
        }

        if ($province) {
            $filter['provinces.id'] = (int)$province;
        }

        if (!empty($skills)) {
            $slv2 = $this->mongo->find('skill_lv2', [
                'id' => ['$in' => array_map('intval', $skills)]
            ]);

            $filter['$or'] = [];
            foreach ($slv2 as $lv2) {
                $lv1Id = $lv2['lv1_id'];
                $lv2Id = $lv2['id'];
                $filter['$or'][] = ['skill_lv1.' . $lv1Id . '.lv2.' . $lv2Id => ['$exists' => true]];
            }
        }


        $totalCount = $this->mongo->count('user', $filter);

        $usersCursor = $this->mongo->find('user', $filter, [
            'sort' => ['id' => -1],
            'skip' => $skip,
            'limit' => $perPage
        ]);
        $users = iterator_to_array($usersCursor);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $users,
            $totalCount,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('search', compact(
            'provinces',
            'skillLv1',
            'skillLv2',
            'users',
            'paginator',
            'name',
            'province',
            'skills'
        ));
    }

    public function quiz()
    {
        return view('quiz');
    }
}
