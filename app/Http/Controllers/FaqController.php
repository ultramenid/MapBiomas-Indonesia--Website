<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Support\Facades\App;

class FaqController extends Controller
{
    public function index()
    {
        return view('cms.faq.index', ['title' => 'FAQ']);
    }

    public function add()
    {
        return view('cms.faq.form', ['title' => 'New FAQ']);
    }

    public function edit(int $id)
    {
        return view('cms.faq.form', ['title' => 'Edit FAQ', 'record' => $id]);
    }

    public function listFaq()
    {
        $title = 'MapBiomas Landy - FAQ';
        $description = 'Bagian dari gerakan global MapBiomas Network untuk menghasilkan peta tutupan dan penggunaan lahan tahunan.';

        $data = Faq::query()
            ->select('id')
            ->selectRaw(App::getLocale() === 'id'
                ? 'questionID as question, answerID as answer'
                : 'questionEN as question, answerEN as answer')
            ->get();

        return view('frontends.faq', compact('title', 'description', 'data'));
    }
}
