<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products and categories.
     */
    public function index(): View
    {
        // Mock data for demonstration
        $featuredProducts = collect([
            [
                'id' => 1,
                'name' => 'Luxury Velvet Sofa',
                'price' => 2500,
                'sale_price' => 2000,
                'image' => '/images/products/sofa-1.jpg',
                'category' => ['name' => 'Sofas']
            ],
            [
                'id' => 2,
                'name' => 'Modern Dining Table',
                'price' => 1800,
                'sale_price' => null,
                'image' => '/images/products/table-1.jpg',
                'category' => ['name' => 'Dining']
            ],
            [
                'id' => 3,
                'name' => 'Premium Bed Frame',
                'price' => 3200,
                'sale_price' => 2800,
                'image' => '/images/products/bed-1.jpg',
                'category' => ['name' => 'Bedroom']
            ]
        ]);

        $featuredCategories = collect([
            ['id' => 1, 'name' => 'Sofas', 'image' => '/images/categories/sofas.jpg'],
            ['id' => 2, 'name' => 'Dining', 'image' => '/images/categories/dining.jpg'],
            ['id' => 3, 'name' => 'Bedroom', 'image' => '/images/categories/bedroom.jpg'],
            ['id' => 4, 'name' => 'Office', 'image' => '/images/categories/office.jpg'],
            ['id' => 5, 'name' => 'Outdoor', 'image' => '/images/categories/outdoor.jpg'],
            ['id' => 6, 'name' => 'Storage', 'image' => '/images/categories/storage.jpg']
        ]);

        $latestProducts = $featuredProducts;
        $deals = $featuredProducts->whereNotNull('sale_price');

        return view('home', compact(
            'featuredProducts',
            'featuredCategories',
            'latestProducts',
            'deals'
        ));
    }

    /**
     * Search products with auto-suggestions.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if ($request->ajax()) {
            // Mock suggestions
            $suggestions = collect([
                ['id' => 1, 'name' => 'Luxury Velvet Sofa', 'sku' => 'SOFA001', 'price' => 2500, 'sale_price' => 2000],
                ['id' => 2, 'name' => 'Modern Dining Table', 'sku' => 'TABLE001', 'price' => 1800, 'sale_price' => null],
                ['id' => 3, 'name' => 'Premium Bed Frame', 'sku' => 'BED001', 'price' => 3200, 'sale_price' => 2800]
            ])->filter(function($item) use ($query) {
                return stripos($item['name'], $query) !== false;
            })->take(5);

            return response()->json($suggestions);
        }

        // Mock products for search results
        $products = collect([
            ['id' => 1, 'name' => 'Luxury Velvet Sofa', 'price' => 2500, 'sale_price' => 2000, 'image' => '/images/products/sofa-1.jpg'],
            ['id' => 2, 'name' => 'Modern Dining Table', 'price' => 1800, 'sale_price' => null, 'image' => '/images/products/table-1.jpg'],
            ['id' => 3, 'name' => 'Premium Bed Frame', 'price' => 3200, 'sale_price' => 2800, 'image' => '/images/products/bed-1.jpg']
        ])->filter(function($item) use ($query) {
            return stripos($item['name'], $query) !== false;
        });

        return view('search', compact('products', 'query'));
    }

    /**
     * Voice search functionality.
     */
    public function voiceSearch(Request $request)
    {
        $audioData = $request->input('audio');
        
        // Here you would integrate with a speech-to-text service
        // For now, we'll return a mock response
        $transcribedText = "luxury sofa"; // Mock transcription
        
        $products = collect([
            ['id' => 1, 'name' => 'Luxury Velvet Sofa', 'price' => 2500, 'sale_price' => 2000, 'image' => '/images/products/sofa-1.jpg'],
            ['id' => 2, 'name' => 'Modern Dining Table', 'price' => 1800, 'sale_price' => null, 'image' => '/images/products/table-1.jpg'],
            ['id' => 3, 'name' => 'Premium Bed Frame', 'price' => 3200, 'sale_price' => 2800, 'image' => '/images/products/bed-1.jpg']
        ])->filter(function($item) use ($transcribedText) {
            return stripos($item['name'], $transcribedText) !== false;
        })->take(10);

        return response()->json([
            'transcribed_text' => $transcribedText,
            'products' => $products
        ]);
    }

    /**
     * Get featured deals for carousel.
     */
    public function featuredDeals()
    {
        $deals = collect([
            ['id' => 1, 'name' => 'Luxury Velvet Sofa', 'price' => 2500, 'sale_price' => 2000, 'image' => '/images/products/sofa-1.jpg'],
            ['id' => 3, 'name' => 'Premium Bed Frame', 'price' => 3200, 'sale_price' => 2800, 'image' => '/images/products/bed-1.jpg']
        ])->whereNotNull('sale_price');

        return response()->json($deals);
    }

    /**
     * Change language.
     */
    public function changeLanguage(Request $request)
    {
        $language = $request->get('language');
        
        if (in_array($language, ['en', 'hi'])) {
            session(['locale' => $language]);
        }

        return redirect()->back();
    }

    /**
     * Toggle dark/light mode.
     */
    public function toggleTheme(Request $request)
    {
        $currentTheme = session('theme', 'light');
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';
        
        session(['theme' => $newTheme]);

        return response()->json(['theme' => $newTheme]);
    }
} 