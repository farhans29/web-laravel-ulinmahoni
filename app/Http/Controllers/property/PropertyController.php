<?php

namespace App\Http\Controllers\property;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller {
    public function index(){
        return view("pages.property.index");
    }
}