<?php

namespace App\Http\Controllers;

use App\Models\Partner;

class PartnerController extends Controller
{
    public function index()
    {
        return view('cms.partners.index', ['title' => 'Partners']);
    }

    public function create()
    {
        return view('cms.partners.form', ['title' => 'New partner']);
    }

    public function edit(int $id)
    {
        return view('cms.partners.form', ['title' => 'Edit partner', 'record' => $id]);
    }
}
