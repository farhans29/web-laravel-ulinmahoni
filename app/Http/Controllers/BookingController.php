<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = DB::table('t_transactions')
            ->join('users', 't_transactions.user_id', '=', 'users.id')
            ->select('t_transactions.*', 'users.username', 'users.email')
            ->where('users.id', Auth::user()->id)
            ->orderBy('t_transactions.created_at', 'desc')
            ->paginate(10);

        // Convert the paginated results to Booking model instances
        $bookings->getCollection()->transform(function ($booking) {
            return (new Booking)->forceFill((array)$booking);
        });

        return view('bookings.index', compact('bookings'));
    }
}
