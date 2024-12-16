<?php

namespace App\Http\Controllers;


use Datatables;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function anydata(User $user)
    {
        $user = User::all();
        return datatables()->of($user)
            ->make(true);
    }
}
