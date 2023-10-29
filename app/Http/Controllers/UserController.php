<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\User;

class UserController extends Controller
{
    public function update(){
        $user = User::findOrFail($id);
        $user->role_id= $request->role_id;
        $user->update();
    }
}
