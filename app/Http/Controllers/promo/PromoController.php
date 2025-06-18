<?php

namespace App\Http\Controllers\promo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = \App\Models\Promo::where('status', 1)
            ->orderByDesc('idrec')
            ->get()
            ->map(function ($promo) {
                return [
                    'id' => $promo->idrec,
                    'title' => $promo->title,
                    'image' => $promo->image, // base64 accessor in model
                    'badge' => 'Promo', // You can customize or add a badge column if needed
                    'description' => $promo->descriptions,
                ];
            });
        return view('components.homepage.promos', compact('promos'));
    }

    public function show($id)
    {
        $promo = \App\Models\Promo::where('status', 1)->where('idrec', $id)->first();
        if (!$promo) {
            abort(404);
        }
        $promoArr = [
            'id' => $promo->idrec,
            'title' => $promo->title,
            'image' => $promo->image,
            'badge' => 'Promo',
            'description' => $promo->descriptions,
            // Add more fields if needed
        ];
        // Add additional details for the promo page
        $promoArr['terms_conditions'] = [
            'Syarat dan ketentuan berlaku',
            'Tidak dapat digabung dengan promo lain',
            'Periode promo terbatas',
            'Hanya berlaku untuk member baru'
        ];
        $promoArr['how_to_claim'] = [
            'Pilih properti yang diinginkan',
            'Klik tombol "Klaim Promo"',
            'Masukkan kode promo saat checkout',
            'Nikmati diskon yang didapat'
        ];
        return view('promos.show', ['promo' => $promoArr]);
    }

}