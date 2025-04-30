<?php

namespace App\Http\Controllers\promo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = $this->getPromos();
        return view('components.homepage.promos', compact('promos'));
    }

    public function show($id)
    {
        $promo = collect($this->getPromos())->firstWhere('id', (int)$id);
        
        if (!$promo) {
            abort(404);
        }

        // Add additional details for the promo page
        $promo['terms_conditions'] = [
            'Syarat dan ketentuan berlaku',
            'Tidak dapat digabung dengan promo lain',
            'Periode promo terbatas',
            'Hanya berlaku untuk member baru'
        ];
        
        $promo['how_to_claim'] = [
            'Pilih properti yang diinginkan',
            'Klik tombol "Klaim Promo"',
            'Masukkan kode promo saat checkout',
            'Nikmati diskon yang didapat'
        ];

        return view('promos.show', compact('promo'));
    }

    public function getPromos()
    {
        return [
            [
                'id' => 1,
                'title' => 'Paket Premium 3 Bulan',
                'image' => 'base64_encoded_image_here',
                'badge' => '40% OFF',
                'description' => 'Dapatkan akses penuh ke semua properti premium',
                'original_price' => 1500000,
                'discounted_price' => 900000,
                'valid_until' => '2025-05-30'
            ],
            [
                'id' => 2,
                'title' => 'Diskon Member Baru',
                'image' => 'base64_encoded_image_here', 
                'badge' => '100rb OFF',
                'description' => 'Khusus pengguna baru pertama registrasi',
                'original_price' => 500000,
                'discounted_price' => 400000,
                'valid_until' => '2025-06-30'
            ]
        ];
    }
}