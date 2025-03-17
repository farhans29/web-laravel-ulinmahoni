<?php

namespace App\Http\Controllers\villa;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VillaController extends Controller {
    public function index(){
        return view("pages.villa.index");
    }
}