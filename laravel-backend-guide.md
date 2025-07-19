# Laravel Backend Implementation Guide

## Project Structure

```
luxury-furniture-ecommerce/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   └── OrderController.php
│   │   │   ├── Api/
│   │   │   │   ├── ProductApiController.php
│   │   │   │   ├── CartApiController.php
│   │   │   │   └── SearchApiController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   └── CheckoutController.php
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php
│   │   │   └── LocalizationMiddleware.php
│   │   └── Requests/
│   │       ├── ProductRequest.php
│   │       └── CheckoutRequest.php
│   ├── Models/
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Cart.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   └── User.php
│   └── Services/
│       ├── ProductService.php
│       ├── CartService.php
│       └── PaymentService.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_categories_table.php
│   │   ├── 2024_01_01_000001_create_products_table.php
│   │   ├── 2024_01_01_000002_create_carts_table.php
│   │   ├── 2024_01_01_000003_create_orders_table.php
│   │   └── 2024_01_01_000004_create_order_items_table.php
│   └── seeders/
│       ├── CategorySeeder.php
│       ├── ProductSeeder.php
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── admin.blade.php
│   │   ├── components/
│   │   │   ├── navbar.blade.php
│   │   │   └── footer.blade.php
│   │   ├── home.blade.php
│   │   ├── products/
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│   │   ├── cart/
│   │   │   └── index.blade.php
│   │   └── admin/
│   │       ├── dashboard.blade.php
│   │       └── products/
│   │           ├── index.blade.php
│   │           └── create.blade.php
│   └── lang/
│       ├── en/
│       │   └── messages.php
│       └── hi/
│           └── messages.php
└── routes/
    ├── web.php
    └── api.php
```

## Database Schema

### Categories Table
```php
<?php
// database/migrations/2024_01_01_000000_create_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
```

### Products Table
```php
<?php
// database/migrations/2024_01_01_000001_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('sku')->unique();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('manage_stock')->default(true);
            $table->boolean('in_stock')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->json('images')->nullable();
            $table->json('gallery')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->json('meta_data')->nullable();
            $table->timestamps();
            
            $table->index(['category_id', 'status']);
            $table->index('is_featured');
            $table->fullText(['name', 'description', 'short_description']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
```

### Cart Table
```php
<?php
// database/migrations/2024_01_01_000002_create_carts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            $table->index(['session_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
```

## Models

### Product Model
```php
<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'price', 
        'sale_price', 'sku', 'stock_quantity', 'manage_stock', 'in_stock',
        'is_featured', 'images', 'gallery', 'weight', 'dimensions',
        'material', 'color', 'status', 'category_id', 'rating', 
        'review_count', 'meta_data'
    ];

    protected $casts = [
        'images' => 'array',
        'gallery' => 'array',
        'dimensions' => 'array',
        'meta_data' => 'array',
        'is_featured' => 'boolean',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'rating' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getPriceAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > $this->sale_price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?: $this->price;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->whereFullText(['name', 'description', 'short_description'], $term)
                    ->orWhere('name', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
```

### Category Model
```php
<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
```

## Controllers

### Home Controller
```php
<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->inStock()
            ->with('category')
            ->limit(8)
            ->get();

        $categories = Category::active()
            ->ordered()
            ->withCount('products')
            ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
```

### Product Controller
```php
<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->inStock()->with('category');

        // Category filter
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Search filter
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Price range filter
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Sort
        switch ($request->get('sort', 'name')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12);
        $categories = Category::active()->ordered()->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        
        $relatedProducts = Product::active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
```

### API Controllers
```php
<?php
// app/Http/Controllers/Api/ProductApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->inStock()->with('category');

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');
        
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product retrieved successfully'
        ]);
    }
}
```

```php
<?php
// app/Http/Controllers/Api/SearchApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    public function suggestions(Request $request)
    {
        $term = $request->get('q');
        
        if (strlen($term) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Query too short'
            ]);
        }

        $products = Product::active()
            ->search($term)
            ->limit(10)
            ->get(['id', 'name', 'slug', 'price', 'images']);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Suggestions retrieved successfully'
        ]);
    }
}
```

## Services

### Cart Service
```php
<?php
// app/Services/CartService.php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        
        if (!$product->in_stock || $product->stock_quantity < $quantity) {
            throw new \Exception('Product not available in requested quantity');
        }

        $sessionId = Session::getId();
        $userId = auth()->id();

        $existingCart = Cart::where('product_id', $productId)
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId)
                      ->orWhere('user_id', $userId);
            })
            ->first();

        if ($existingCart) {
            $existingCart->quantity += $quantity;
            $existingCart->save();
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->final_price
            ]);
        }

        return $this->getCartItems();
    }

    public function removeFromCart($cartId)
    {
        Cart::findOrFail($cartId)->delete();
        return $this->getCartItems();
    }

    public function updateQuantity($cartId, $quantity)
    {
        $cart = Cart::findOrFail($cartId);
        $cart->quantity = $quantity;
        $cart->save();
        
        return $this->getCartItems();
    }

    public function getCartItems()
    {
        $sessionId = Session::getId();
        $userId = auth()->id();

        return Cart::with('product')
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId)
                      ->orWhere('user_id', $userId);
            })
            ->get();
    }

    public function getCartTotal()
    {
        $items = $this->getCartItems();
        return $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    public function clearCart()
    {
        $sessionId = Session::getId();
        $userId = auth()->id();

        Cart::where(function ($query) use ($sessionId, $userId) {
            $query->where('session_id', $sessionId)
                  ->orWhere('user_id', $userId);
        })->delete();
    }
}
```

## Blade Templates

### Layout Template
```blade
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'LuxFurn - Premium Furniture')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|playfair-display:400,500,600,700" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @yield('styles')
</head>
<body class="dark-mode">
    <!-- Navigation -->
    @include('components.navbar')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('components.footer')
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
```

### Home View
```blade
<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'LuxFurn - Premium Furniture Collection')

@section('content')
<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-bg">
        <div class="parallax-layer" data-speed="0.5"></div>
        <div class="parallax-layer" data-speed="0.8"></div>
    </div>
    
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">
                <span class="title-line">{{ __('messages.luxury') }}</span>
                <span class="title-line">{{ __('messages.redefined') }}</span>
            </h1>
            <p class="hero-subtitle">{{ __('messages.hero_subtitle') }}</p>
            <div class="hero-buttons">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    {{ __('messages.explore_collection') }}
                </a>
                <button class="btn btn-secondary">{{ __('messages.watch_story') }}</button>
            </div>
        </div>
        
        @if($featuredProducts->isNotEmpty())
        <div class="hero-product">
            <div class="product-showcase">
                <img src="{{ $featuredProducts->first()->images[0] ?? '' }}" 
                     alt="{{ $featuredProducts->first()->name }}">
                <div class="product-info">
                    <h3>{{ $featuredProducts->first()->name }}</h3>
                    <p>₹{{ number_format($featuredProducts->first()->final_price) }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="scroll-indicator">
        <div class="scroll-arrow"></div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products" id="products">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('messages.featured_collection') }}</h2>
            <p class="section-subtitle">{{ __('messages.featured_subtitle') }}</p>
        </div>
        
        <div class="products-grid">
            @foreach($featuredProducts as $product)
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ $product->images[0] ?? '' }}" alt="{{ $product->name }}">
                    <div class="product-overlay">
                        <button class="btn btn-small quick-view" data-product-id="{{ $product->id }}">
                            {{ __('messages.quick_view') }}
                        </button>
                        <button class="btn btn-small add-to-cart" data-product-id="{{ $product->id }}">
                            {{ __('messages.add_to_cart') }}
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>{{ $product->name }}</h3>
                    <p class="product-price">₹{{ number_format($product->final_price) }}</p>
                    <div class="product-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $product->rating ? '' : '-o' }}"></i>
                        @endfor
                        <span>({{ $product->review_count }} {{ __('messages.reviews') }})</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Categories -->
<section class="categories">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('messages.shop_by_category') }}</h2>
            <p class="section-subtitle">{{ __('messages.category_subtitle') }}</p>
        </div>
        
        <div class="categories-grid">
            @foreach($categories as $category)
            <div class="category-card">
                <img src="{{ $category->image ?? '' }}" alt="{{ $category->name }}">
                <div class="category-overlay">
                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->description }}</p>
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="btn btn-primary">{{ __('messages.shop_now') }}</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// Add to cart functionality
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product added to cart!');
                updateCartCount(data.cart_count);
            } else {
                showNotification('Error adding product to cart', 'error');
            }
        });
    });
});
</script>
@endsection
```

## Routes

### Web Routes
```php
<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class);
});

// Language Routes
Route::get('/lang/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
})->name('language.switch');
```

### API Routes
```php
<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\SearchApiController;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index']);
    Route::get('/{product}', [ProductApiController::class, 'show']);
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartApiController::class, 'index']);
    Route::post('/add', [CartApiController::class, 'add']);
    Route::patch('/{cart}', [CartApiController::class, 'update']);
    Route::delete('/{cart}', [CartApiController::class, 'remove']);
});

Route::get('/search/suggestions', [SearchApiController::class, 'suggestions']);
```

## Language Files

### English Messages
```php
<?php
// resources/lang/en/messages.php

return [
    'luxury' => 'Luxury',
    'redefined' => 'Redefined',
    'hero_subtitle' => 'Experience premium furniture that transforms your space into a masterpiece',
    'explore_collection' => 'Explore Collection',
    'watch_story' => 'Watch Story',
    'featured_collection' => 'Featured Collection',
    'featured_subtitle' => 'Curated pieces that define elegance',
    'quick_view' => 'Quick View',
    'add_to_cart' => 'Add to Cart',
    'reviews' => 'reviews',
    'shop_by_category' => 'Shop by Category',
    'category_subtitle' => 'Discover our premium collections',
    'shop_now' => 'Shop Now',
    'free_delivery' => 'Free Delivery',
    'warranty' => '10 Year Warranty',
    'support' => '24/7 Support',
    'premium_quality' => 'Premium Quality',
];
```

### Hindi Messages
```php
<?php
// resources/lang/hi/messages.php

return [
    'luxury' => 'विलासिता',
    'redefined' => 'पुनर्परिभाषित',
    'hero_subtitle' => 'प्रीमियम फर्नीचर का अनुभव करें जो आपके स्थान को एक कलाकृति में बदल देता है',
    'explore_collection' => 'संग्रह देखें',
    'watch_story' => 'कहानी देखें',
    'featured_collection' => 'विशेष संग्रह',
    'featured_subtitle' => 'शानदार टुकड़े जो भव्यता को परिभाषित करते हैं',
    'quick_view' => 'त्वरित दृश्य',
    'add_to_cart' => 'कार्ट में जोड़ें',
    'reviews' => 'समीक्षाएं',
    'shop_by_category' => 'श्रेणी के अनुसार खरीदें',
    'category_subtitle' => 'हमारे प्रीमियम संग्रह की खोज करें',
    'shop_now' => 'अभी खरीदें',
    'free_delivery' => 'मुफ्त डिलीवरी',
    'warranty' => '10 साल की गारंटी',
    'support' => '24/7 सहायता',
    'premium_quality' => 'प्रीमियम गुणवत्ता',
];
```

## Installation Commands

```bash
# Create new Laravel project
composer create-project laravel/laravel luxury-furniture-ecommerce

# Install additional packages
composer require laravel/sanctum
composer require intervention/image
composer require spatie/laravel-sluggable

# Generate migrations
php artisan make:migration create_categories_table
php artisan make:migration create_products_table
php artisan make:migration create_carts_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table

# Generate models
php artisan make:model Category -m
php artisan make:model Product -m
php artisan make:model Cart -m
php artisan make:model Order -m
php artisan make:model OrderItem -m

# Generate controllers
php artisan make:controller HomeController
php artisan make:controller ProductController
php artisan make:controller CartController
php artisan make:controller CheckoutController
php artisan make:controller Admin/AdminController
php artisan make:controller Admin/ProductController
php artisan make:controller Api/ProductApiController
php artisan make:controller Api/CartApiController
php artisan make:controller Api/SearchApiController

# Generate seeders
php artisan make:seeder CategorySeeder
php artisan make:seeder ProductSeeder

# Generate middleware
php artisan make:middleware AdminMiddleware
php artisan make:middleware LocalizationMiddleware

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Generate storage link
php artisan storage:link

# Generate application key
php artisan key:generate
```

This comprehensive Laravel backend provides all the functionality needed for your luxury furniture e-commerce platform with proper MVC architecture, API endpoints, localization, and admin features.