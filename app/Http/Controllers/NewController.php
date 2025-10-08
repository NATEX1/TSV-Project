<?php

namespace App\Http\Controllers;

use App\Services\MongoService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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

        // Fetch data from MongoDB
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

        // Convert cursor to array
        $news = iterator_to_array($newsCursor);

        // Get total count of documents
        $total = $this->mongo->collection('content')->countDocuments(['menusub_id' => 3]);

        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $news, 
            $total, 
            $perPage,
            $page, 
            [
                'path' => $request->url(),
                'query' => $request->query() 
            ]
        );

        // Pass paginator to the view
        return view('news', ['news' => $paginator]);
    }
}
