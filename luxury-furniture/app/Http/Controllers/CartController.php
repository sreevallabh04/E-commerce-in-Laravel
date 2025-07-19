<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        if ($user) {
            $cartItems = $user->cartItems()->with(['product.images', 'product.category'])->get();
        } else {
            $cartItems = collect();
            $cart = session('cart', []);
            
            if (!empty($cart)) {
                $productIds = array_keys($cart);
                $products = Product::with(['images', 'category'])->whereIn('id', $productIds)->get();
                
                foreach ($products as $product) {
                    $quantity = $cart[$product->id]['quantity'] ?? 1;
                    $cartItems->push((object) [
                        'id' => $product->id,
                        'quantity' => $quantity,
                        'product' => $product
                    ]);
                }
            }
        }

        return view('cart.index', compact('cartItems'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if (!$product->in_stock || $product->stock_quantity < $request->quantity) {
            return response()->json(['error' => 'Product is out of stock'], 400);
        }

        $user = auth()->user();
        
        if ($user) {
            // Add to database cart
            $cartItem = $user->cartItems()->updateOrCreate(
                [
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                ],
                [
                    'quantity' => $request->quantity,
                ]
            );

            $cartCount = $user->cartItems()->sum('quantity');
        } else {
            // Add to session cart
            $cart = session('cart', []);
            $key = $request->product_id . '_' . ($request->variant_id ?? 'null');
            
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $request->quantity;
            } else {
                $cart[$key] = [
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                ];
            }
            
            session(['cart' => $cart]);
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'message' => 'Product added to cart',
            'cart_count' => $cartCount,
            'product_name' => $product->name
        ]);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $user = auth()->user();
        
        if ($user) {
            $cartItem = $user->cartItems()->findOrFail($id);
            $cartItem->update(['quantity' => $request->quantity]);
            
            $cartCount = $user->cartItems()->sum('quantity');
            $subtotal = $cartItem->product->current_price * $request->quantity;
        } else {
            $cart = session('cart', []);
            $cart[$id]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
            
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $subtotal = 0; // Calculate from session data
        }

        return response()->json([
            'message' => 'Cart updated',
            'cart_count' => $cartCount,
            'subtotal' => $subtotal
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function remove($id)
    {
        $user = auth()->user();
        
        if ($user) {
            $user->cartItems()->where('id', $id)->delete();
            $cartCount = $user->cartItems()->sum('quantity');
        } else {
            $cart = session('cart', []);
            unset($cart[$id]);
            session(['cart' => $cart]);
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'message' => 'Item removed from cart',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Clear cart.
     */
    public function clear()
    {
        $user = auth()->user();
        
        if ($user) {
            $user->cartItems()->delete();
        } else {
            session()->forget('cart');
        }

        return response()->json([
            'message' => 'Cart cleared'
        ]);
    }

    /**
     * Get cart summary for mini cart.
     */
    public function summary()
    {
        $user = auth()->user();
        
        if ($user) {
            $cartItems = $user->cartItems()->with(['product.images'])->get();
            $cartCount = $user->cartItems()->sum('quantity');
        } else {
            $cart = session('cart', []);
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $cartItems = collect();
            
            if (!empty($cart)) {
                $productIds = array_keys($cart);
                $products = Product::with('images')->whereIn('id', $productIds)->get();
                
                foreach ($products as $product) {
                    $quantity = $cart[$product->id]['quantity'] ?? 1;
                    $cartItems->push((object) [
                        'id' => $product->id,
                        'quantity' => $quantity,
                        'product' => $product
                    ]);
                }
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->current_price * $item->quantity;
        });

        return response()->json([
            'cart_count' => $cartCount,
            'subtotal' => $subtotal,
            'items' => $cartItems->take(3) // Show only first 3 items in mini cart
        ]);
    }

    /**
     * Apply coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:20',
        ]);

        // Here you would validate the coupon code
        // For now, we'll return a mock response
        $discount = 10; // 10% discount
        
        return response()->json([
            'message' => 'Coupon applied successfully',
            'discount' => $discount
        ]);
    }

    /**
     * API: Get cart items
     */
    public function apiIndex()
    {
        $user = auth()->user();
        
        if ($user) {
            $cartItems = $user->cartItems()->with(['product.images', 'product.category'])->get();
            $cartCount = $user->cartItems()->sum('quantity');
        } else {
            $cartItems = collect();
            $cart = session('cart', []);
            $cartCount = 0;
            
            if (!empty($cart)) {
                $productIds = array_keys($cart);
                $products = Product::with(['images', 'category'])->whereIn('id', $productIds)->get();
                
                foreach ($products as $product) {
                    $quantity = $cart[$product->id]['quantity'] ?? 1;
                    $cartItems->push((object) [
                        'id' => $product->id,
                        'quantity' => $quantity,
                        'product' => $product
                    ]);
                    $cartCount += $quantity;
                }
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->current_price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'cart_count' => $cartCount,
                'subtotal' => $subtotal
            ]
        ]);
    }

    /**
     * API: Add item to cart
     */
    public function apiAdd(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if (!$product->in_stock || $product->stock_quantity < $request->quantity) {
            return response()->json(['error' => 'Product is out of stock'], 400);
        }

        $user = auth()->user();
        
        if ($user) {
            // Add to database cart
            $cartItem = $user->cartItems()->updateOrCreate(
                ['product_id' => $request->product_id],
                ['quantity' => $request->quantity]
            );
            $cartCount = $user->cartItems()->sum('quantity');
        } else {
            // Add to session cart
            $cart = session('cart', []);
            $productId = $request->product_id;
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $request->quantity;
            } else {
                $cart[$productId] = [
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ];
            }
            
            session(['cart' => $cart]);
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cartCount,
            'product_name' => $product->name
        ]);
    }

    /**
     * API: Update cart item
     */
    public function apiUpdate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $user = auth()->user();
        
        if ($user) {
            $cartItem = $user->cartItems()->where('product_id', $request->product_id)->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $request->quantity]);
            }
            $cartCount = $user->cartItems()->sum('quantity');
        } else {
            $cart = session('cart', []);
            $productId = $request->product_id;
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $request->quantity;
                session(['cart' => $cart]);
            }
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * API: Remove item from cart
     */
    public function apiRemove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = auth()->user();
        
        if ($user) {
            $user->cartItems()->where('product_id', $request->product_id)->delete();
            $cartCount = $user->cartItems()->sum('quantity');
        } else {
            $cart = session('cart', []);
            unset($cart[$request->product_id]);
            session(['cart' => $cart]);
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * API: Clear cart
     */
    public function apiClear()
    {
        $user = auth()->user();
        
        if ($user) {
            $user->cartItems()->delete();
        } else {
            session()->forget('cart');
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }
} 