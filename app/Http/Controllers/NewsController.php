<?php

namespace App\Http\Controllers;

use App\Models\NewsItem;

class NewsController extends Controller
{
    public function index()
    {
        return view('cms.news.index', ['title' => 'News']);
    }

    public function create()
    {
        return view('cms.news.form', ['title' => 'New news']);
    }

    public function edit(int $id)
    {
        return view('cms.news.form', ['title' => 'Edit news', 'record' => $id]);
    }

    public function show(string $lang, string $slug)
    {
        $news = NewsItem::published()->internal()->where('slug', $slug)->firstOrFail();

        $title = 'MapBiomas Indonesia - ' . $news->bi('title');
        $description = (string) $news->bi('excerpt');

        return view('frontends.news-detail', compact('title', 'description', 'news'));
    }
}
