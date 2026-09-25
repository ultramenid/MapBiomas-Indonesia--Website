<?php

namespace App\Http\Controllers;

use App\Models\Initiative;
use App\Models\NewsItem;

class IndexController extends Controller
{
    public function index()
    {
        $title = 'MapBiomas Indonesia';
        $description = 'Learning from the past for the future';

        $news = NewsItem::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        $initiatives = Initiative::active()->get();

        return view('frontends.index', compact('title', 'description', 'news', 'initiatives'));
    }
}
