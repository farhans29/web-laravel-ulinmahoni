<?php

namespace App\Http\Controllers\homepage;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    public function index(){
        return view("pages.homepage.index");
    }
}