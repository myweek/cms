<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    
    public function index() {
        $ret = DB::table('users')->get();
        return $ret;
    }
}
