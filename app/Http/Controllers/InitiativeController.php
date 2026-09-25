<?php

namespace App\Http\Controllers;

use App\Models\Initiative;

class InitiativeController extends Controller
{
    public function index()
    {
        return view('cms.initiatives.index', ['title' => 'Initiatives']);
    }

    public function create()
    {
        return view('cms.initiatives.form', ['title' => 'New initiative']);
    }

    public function edit(int $id)
    {
        return view('cms.initiatives.form', ['title' => 'Edit initiative', 'record' => $id]);
    }
}
