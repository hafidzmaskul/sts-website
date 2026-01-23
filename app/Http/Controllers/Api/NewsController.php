<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = News::published()
            ->with(['categories', 'creator']);

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $news = $query->latest('published_at')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $news = News::published()
            ->with(['categories', 'creator'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }
}
