<?php

namespace App\Http\Controllers;

use App\Services\MongoService;
use Illuminate\Http\Request;

class NewController extends Controller
{
    protected $mongo;

    public function __construct(MongoService $mongo)
    {
        $this->mongo = $mongo;
    }
    
    public function __invoke(Request $request)
    {
        $perPage = 12;
        $page = $request->input('page', 1);

        $skip = ($page - 1) * $perPage;

        $newsCursor = $this->mongo->find(
            'content',
            [
                'menusub_id' => 3,
                'status' => 0
            ],
            [
                'skip' => $skip,
                'limit' => $perPage,
                'sort' => ['_id' => -1]
            ]
        );

        $news = iterator_to_array($newsCursor);

        $total = $this->mongo->collection('content')->countDocuments(['menusub_id' => 3]);
        $totalPages = ceil($total / $perPage);
        return view('news', compact('news', 'page', 'totalPages'));
    }
}
