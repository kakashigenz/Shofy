<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function show(Request $request){
       return view('user.show',['title'=> 'Thông tin của tôi']);
    }

}
