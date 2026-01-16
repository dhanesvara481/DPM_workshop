<?php

namespace App\Http\Controllers;
use App\Models\User;

abstract class Controller
{
    public function index ()
    {
        $users = User::all();
        return view('main', ['users' => $users]);
    }
}
