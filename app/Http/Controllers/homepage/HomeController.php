<?php

namespace App\Http\Controllers\homepage;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller {
    private const API_URL = 'https://api-dev.rumahayoda.com/banner';
    
    public function index()
    {
        try {
            $response = $this->fetchBannerData();
            
            if ($response->successful()) {
                return $this->handleSuccessResponse($response);
            }
            
            return $this->handleErrorResponse($response);
            
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    private function fetchBannerData()
    {
        return Http::withoutVerifying()
            ->timeout(60)
            ->retry(3, 100, function ($exception) {
                return $exception instanceof \Illuminate\Http\Client\ConnectionException;
            })
            ->withHeaders([
                'Accept' => 'application/json',
                'Cache-Control' => 'no-cache'
            ])
            ->get(self::API_URL);
    }

    private function handleSuccessResponse($response)
    {
        $bannerData = $response->json();
        Log::info('Banner data fetched successfully', [
            'data' => $bannerData
        ]);

        return view("pages.homepage.index", [
            'banners' => $bannerData,
            'debug' => [
                'type' => 'success',
                'data' => $bannerData
            ]
        ]);
    }

    private function handleErrorResponse($response)
    {
        $errorData = [
            'status' => $response->status(),
            'body' => $response->body()
        ];
        
        Log::error('Failed to fetch banner data', $errorData);

        return view("pages.homepage.index", [
            'banners' => [],
            // 'debug' => [
            //     'type' => 'error',
            //     'data' => $errorData
            // ]
        ]);
    }

    private function handleException(Exception $e)
    {
        $errorData = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];

        Log::error('Exception while fetching banner data', $errorData);

        return view("pages.homepage.index", [
            'banners' => [],
            // 'debug' => [
            //     'type' => 'exception',
            //     'data' => $errorData
            // ]
        ]);
    }

    public function comingSoon()
    {
        return view("pages.coming-soon.index");
    }
}