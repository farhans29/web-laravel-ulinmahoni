<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Exception;
use Illuminate\Http\Request;

class AllPropertiesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Property::query()->where('status', 1);
            
            // Apply filters if provided
            if ($request->has('type')) {
                $query->where('tags', $request->type);
            }
            
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
                });
            }

            if ($request->has('province')) {
                $query->where('province', $request->province);
            }

            if ($request->has('city')) {
                $query->where('city', $request->city);
            }

            if ($request->has('price_min')) {
                $query->where('price->original->1_month', '>=', $request->price_min);
            }

            if ($request->has('price_max')) {
                $query->where('price->original->1_month', '<=', $request->price_max);
            }

            // Order by created_at (newest first)
            $query->orderBy('created_at', 'desc');

            // Get paginated results
            $properties = $query->paginate(12)->withQueryString();

            return view('pages.properties.index', compact('properties'));
        } catch (Exception $e) {
            return back()->with('error', 'Error loading properties: ' . $e->getMessage());
        }
    }
} 