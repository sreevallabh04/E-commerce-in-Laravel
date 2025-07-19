<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'images', 'reviews'])
            ->active()
            ->inStock();

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by material
        if ($request->has('material')) {
            $query->where('material', $request->material);
        }

        // Filter by color
        if ($request->has('color')) {
            $query->where('color', $request->color);
        }

        // Sort products
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->orderByRaw('(SELECT AVG(rating) FROM reviews WHERE product_id = products.id) DESC');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::active()->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        $product->load(['category', 'images', 'reviews.user', 'variants', 'attributes']);

        // Get related products
        $relatedProducts = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->take(4)
            ->get();

        // Get recently viewed products
        $recentlyViewed = session('recently_viewed', []);
        if (!in_array($product->id, $recentlyViewed)) {
            array_unshift($recentlyViewed, $product->id);
            $recentlyViewed = array_slice($recentlyViewed, 0, 5);
            session(['recently_viewed' => $recentlyViewed]);
        }

        $recentlyViewedProducts = Product::with(['category', 'images'])
            ->whereIn('id', $recentlyViewed)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'recentlyViewedProducts'));
    }

    /**
     * Add product to wishlist.
     */
    public function addToWishlist(Request $request, Product $product)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Please login to add items to wishlist'], 401);
        }

        $user->wishlistItems()->syncWithoutDetaching([$product->id]);

        return response()->json([
            'message' => 'Product added to wishlist',
            'wishlist_count' => $user->wishlistItems()->count()
        ]);
    }

    /**
     * Remove product from wishlist.
     */
    public function removeFromWishlist(Request $request, Product $product)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Please login to manage wishlist'], 401);
        }

        $user->wishlistItems()->detach($product->id);

        return response()->json([
            'message' => 'Product removed from wishlist',
            'wishlist_count' => $user->wishlistItems()->count()
        ]);
    }

    /**
     * Get product quick view data.
     */
    public function quickView(Product $product)
    {
        $product->load(['category', 'images', 'reviews']);

        return response()->json([
            'product' => $product,
            'average_rating' => $product->average_rating,
            'reviews_count' => $product->reviews_count
        ]);
    }

    /**
     * Get product variants.
     */
    public function getVariants(Product $product)
    {
        $variants = $product->variants()->with('attributes')->get();

        return response()->json($variants);
    }

    /**
     * Get product reviews.
     */
    public function getReviews(Product $product)
    {
        $reviews = $product->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        return response()->json($reviews);
    }

    /**
     * Submit a product review.
     */
    public function submitReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Please login to submit a review'], 401);
        }

        // Check if user has already reviewed this product
        $existingReview = $product->reviews()->where('user_id', $user->id)->first();
        
        if ($existingReview) {
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            $product->reviews()->create([
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        return response()->json([
            'message' => 'Review submitted successfully',
            'average_rating' => $product->fresh()->average_rating,
            'reviews_count' => $product->fresh()->reviews_count
        ]);
    }

    /**
     * API: Get all products
     */
    public function apiIndex(Request $request)
    {
        $query = Product::with(['category', 'images', 'reviews'])
            ->active()
            ->inStock();

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by material
        if ($request->has('material')) {
            $query->where('material', $request->material);
        }

        // Filter by color
        if ($request->has('color')) {
            $query->where('color', $request->color);
        }

        // Sort products
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->orderByRaw('(SELECT AVG(rating) FROM reviews WHERE product_id = products.id) DESC');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * API: Get single product
     */
    public function apiShow(Product $product)
    {
        $product->load(['category', 'images', 'reviews.user', 'variants', 'attributes']);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * API: Get all categories
     */
    public function apiCategories()
    {
        $categories = Category::active()->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
} 