<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Facades\App;

class PagesController extends Controller
{
    public function cmsIndex()
    {
        return view('cms.pages.index', ['title' => 'Pages', 'pages' => Page::orderBy('name')->get()]);
    }

    public function cmsEdit(string $slug)
    {
        return view('cms.pages.edit', ['title' => ucfirst($slug), 'slug' => $slug]);
    }

    public function about()
    {
        $title = 'MapBiomas Landy - About';
        $description = 'Bagian dari gerakan global MapBiomas Network untuk menghasilkan peta tutupan dan penggunaan lahan tahunan.';
        $column = App::getLocale() === 'id' ? 'contentID' : 'contentEN';
        $data = Page::where('name', 'about')->first([$column . ' as content']);

        return view('frontends.about', compact('title', 'description', 'data'));
    }
}
