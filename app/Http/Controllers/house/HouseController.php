<?php

namespace App\Http\Controllers\House;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HouseController extends Controller {
    public function index(){
        return view("pages.house.index");
    }

    public function show($id)
    {
        // For now, we'll use dummy data. Later you can replace this with actual database queries
        $house = [
            'id' => $id,
            'name' => 'Rexucia House & Room',
            'type' => 'Coliving',
            'location' => 'Petojo Selatan, Gambir',
            'distance' => '2.4 km dari Stasiun MRT Bundaran HI',
            'price' => [
                'original' => [
                    '1_month' => 1300000,
                    '6_month' => 0,
                    '12_month' => 0,
                ],
                'discounted' => [
                    '1_month' => 975000,
                    '6_month' => 0,
                    '12_month' => 0,
                ]
            ],
            'features' => [
                'Diskon sewa 12 Bulan',
                'S+ Voucher s.d. 2%'
            ],
            'image' => 'images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0006.jpg',
            'attributes' => [
                'amenities' => [
                    'High-speed WiFi',
                    '24/7 Security',
                    'Shared Kitchen',
                    'Laundry Service',
                    'Parking Area',
                    'Common Area'
                ],
                'room_facilities' => [
                    'Air Conditioning',
                    'Private Bathroom',
                    'Furnished',
                    'TV Cable Ready'
                ],
                'rules' => [
                    'No Smoking',
                    'No Pets',
                    'ID Card Required',
                    'Deposit Required'
                ]
            ],
            'description' => 'Experience modern coliving at its finest in this strategically located property. 
                            Featuring well-designed spaces, community areas, and all the amenities you need 
                            for comfortable urban living. Perfect for young professionals and students looking 
                            for a vibrant community in the heart of the city.'
        ];

        return view('pages.house.show', compact('house'));
    }
}