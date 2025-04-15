<?php

namespace App\Http\Controllers\apart;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApartController extends Controller {
    public function index(){
        return view("pages.apartment.index");
    }

    public function show($id)
    {
        // For now, we'll use dummy data. Later you can replace this with actual database queries
        $apartment = [
            'id' => $id,
            'name' => 'Royal Mediteranian Apartment',
            'type' => 'Coliving',
            'location' => 'Cilandak Barat, Cilandak',
            'distance' => '976 m dari Stasiun MRT Fatmawati',
            'price' => [
                'original' => 4250000,
                'discounted' => 4025000
            ],
            'features' => [
                'Diskon sewa 12 Bulan',
                'S+ Voucher s.d. 2%'
            ],
            'image' => 'images/assets/apt.jpg',
            'attributes' => [
                'amenities' => [
                    'High-speed WiFi',
                    '24/7 Security',
                    'Swimming Pool',
                    'Gym',
                    'Parking Area',
                    'Common Area'
                ],
                'room_facilities' => [
                    'Air Conditioning',
                    'Private Bathroom',
                    'Furnished',
                    'TV Cable Ready',
                    'Balcony'
                ],
                'rules' => [
                    'No Smoking',
                    'No Pets',
                    'ID Card Required',
                    'Deposit Required'
                ]
            ],
            'description' => 'Experience luxury living in this modern apartment complex. Located in a prime area with 
                            easy access to public transportation and shopping centers. The apartment features high-quality 
                            furnishings and amenities perfect for young professionals seeking comfort and convenience.'
        ];

        return view('pages.apartment.show', compact('apartment'));
    }
}