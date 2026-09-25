<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('cms.users.index', ['title' => 'Users']);
    }

    public function create()
    {
        return view('cms.users.form', ['title' => 'New user']);
    }

    public function edit(int $id)
    {
        return view('cms.users.form', ['title' => 'Edit user', 'record' => $id]);
    }
}
