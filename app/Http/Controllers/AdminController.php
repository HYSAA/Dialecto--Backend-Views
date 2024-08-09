<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    //
    public function manageUsers(){
        $users = User::select('id','email')->paginate(10);

        return view('admin.listUserAccounts')->with(compact('users'));
    }
}
