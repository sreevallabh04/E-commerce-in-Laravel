<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Frontend Route - Serve the main HTML file
Route::get('/', function () {
    return view('frontend.index');
})->name('frontend.home');

// Home Routes
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::post('/voice-search', [HomeController::class, 'voiceSearch'])->name('voice.search');
Route::get('/featured-deals', [HomeController::class, 'featuredDeals'])->name('deals.featured');
Route::post('/language/change', [HomeController::class, 'changeLanguage'])->name('language.change');
Route::post('/theme/toggle', [HomeController::class, 'toggleTheme'])->name('theme.toggle');

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/quick-view', [ProductController::class, 'quickView'])->name('products.quick-view');
Route::get('/products/{product}/variants', [ProductController::class, 'getVariants'])->name('products.variants');
Route::get('/products/{product}/reviews', [ProductController::class, 'getReviews'])->name('products.reviews');
Route::post('/products/{product}/reviews', [ProductController::class, 'submitReview'])->name('products.reviews.submit');

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{id}/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

// Wishlist Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
});

// Product Wishlist Toggle (for both authenticated and guest users)
Route::post('/products/{product}/wishlist', [ProductController::class, 'addToWishlist'])->name('products.wishlist.add');
Route::delete('/products/{product}/wishlist', [ProductController::class, 'removeFromWishlist'])->name('products.wishlist.remove');

// Order Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Static Pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/deals', function () {
    return view('pages.deals');
})->name('deals');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/shipping', function () {
    return view('pages.shipping');
})->name('shipping');

Route::get('/returns', function () {
    return view('pages.returns');
})->name('returns');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Product Management
    Route::resource('products', AdminController::class);
    Route::post('products/{product}/images', [AdminController::class, 'uploadImages'])->name('products.images.upload');
    Route::delete('products/{product}/images/{image}', [AdminController::class, 'deleteImage'])->name('products.images.delete');
    
    // Category Management
    Route::resource('categories', AdminController::class);
    
    // Order Management
    Route::get('orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('orders/{order}', [AdminController::class, 'orderShow'])->name('orders.show');
    Route::put('orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    
    // User Management
    Route::get('users', [AdminController::class, 'users'])->name('users.index');
    Route::get('users/{user}', [AdminController::class, 'userShow'])->name('users.show');
    Route::put('users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    
    // Analytics
    Route::get('analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('analytics/sales', [AdminController::class, 'salesAnalytics'])->name('analytics.sales');
    Route::get('analytics/products', [AdminController::class, 'productAnalytics'])->name('analytics.products');
    
    // Settings
    Route::get('settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

// API Routes for AJAX requests
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/categories', [CategoryController::class, 'list'])->name('categories.list');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
    Route::get('/wishlist/count', [WishlistController::class, 'getCount'])->name('wishlist.count');
});

// Fallback route for 404
Route::fallback(function () {
    return view('errors.404');
});

// Localization Routes
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hi'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch'); 