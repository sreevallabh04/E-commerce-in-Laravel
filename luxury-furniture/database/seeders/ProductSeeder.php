<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $livingRoomCategory = Category::where('slug', 'living-room')->first();
        $bedroomCategory = Category::where('slug', 'bedroom')->first();
        $diningCategory = Category::where('slug', 'dining')->first();
        $officeCategory = Category::where('slug', 'office')->first();

        $products = [
            [
                'name' => json_encode(['en' => 'Premium Velvet Sofa']),
                'description' => json_encode(['en' => 'This luxurious velvet sofa combines comfort with elegance. Crafted with premium materials and attention to detail, it features plush velvet upholstery and a sturdy wooden frame.']),
                'short_description' => json_encode(['en' => 'Luxurious velvet sofa with premium comfort']),
                'price' => 299999,
                'sale_price' => null,
                'sku' => 'SOFA-001',
                'stock_quantity' => 10,
                'in_stock' => true,
                'featured' => true,
                'weight' => 85.5,
                'dimensions' => '220x95x85',
                'material' => 'Velvet',
                'color' => 'Navy Blue',
                'status' => 'active',
                'category_id' => $livingRoomCategory->id,
            ],
            [
                'name' => json_encode(['en' => 'Marble Dining Table']),
                'description' => json_encode(['en' => 'A stunning marble dining table that serves as the centerpiece of any dining room. The natural marble top is supported by elegant metal legs for a modern yet timeless look.']),
                'short_description' => json_encode(['en' => 'Elegant marble dining table with metal legs']),
                'price' => 189999,
                'sale_price' => null,
                'sku' => 'TABLE-001',
                'stock_quantity' => 5,
                'in_stock' => true,
                'featured' => true,
                'weight' => 120.0,
                'dimensions' => '180x90x75',
                'material' => 'Marble',
                'color' => 'White',
                'status' => 'active',
                'category_id' => $diningCategory->id,
            ],
            [
                'name' => json_encode(['en' => 'Royal Bedroom Set']),
                'description' => json_encode(['en' => 'Complete bedroom set featuring a king-size bed, matching nightstands, and a luxurious wardrobe. Crafted with premium wood and elegant finishes.']),
                'short_description' => json_encode(['en' => 'Complete luxury bedroom set with premium wood']),
                'price' => 499999,
                'sale_price' => 449999,
                'sku' => 'BED-001',
                'stock_quantity' => 3,
                'in_stock' => true,
                'featured' => true,
                'weight' => 200.0,
                'dimensions' => '210x180x120',
                'material' => 'Mahogany Wood',
                'color' => 'Dark Brown',
                'status' => 'active',
                'category_id' => $bedroomCategory->id,
            ],
            [
                'name' => json_encode(['en' => 'Executive Office Chair']),
                'description' => json_encode(['en' => 'Premium ergonomic office chair designed for maximum comfort during long work hours. Features adjustable height, lumbar support, and premium leather upholstery.']),
                'short_description' => json_encode(['en' => 'Ergonomic office chair with premium leather']),
                'price' => 89999,
                'sale_price' => null,
                'sku' => 'CHAIR-001',
                'stock_quantity' => 15,
                'in_stock' => true,
                'featured' => false,
                'weight' => 25.0,
                'dimensions' => '70x70x120',
                'material' => 'Leather',
                'color' => 'Black',
                'status' => 'active',
                'category_id' => $officeCategory->id,
            ],
            [
                'name' => json_encode(['en' => 'Luxury Wardrobe']),
                'description' => json_encode(['en' => 'Spacious wardrobe with multiple compartments, hanging space, and elegant design. Perfect for organizing your clothing collection in style.']),
                'short_description' => json_encode(['en' => 'Spacious wardrobe with elegant design']),
                'price' => 159999,
                'sale_price' => null,
                'sku' => 'WARDROBE-001',
                'stock_quantity' => 8,
                'in_stock' => true,
                'featured' => false,
                'weight' => 150.0,
                'dimensions' => '200x60x220',
                'material' => 'Wood',
                'color' => 'Walnut',
                'status' => 'active',
                'category_id' => $bedroomCategory->id,
            ],
            [
                'name' => json_encode(['en' => 'Designer Coffee Table']),
                'description' => json_encode(['en' => 'Modern coffee table with a glass top and metal frame. Perfect for displaying books, magazines, and decorative items while maintaining a clean aesthetic.']),
                'short_description' => json_encode(['en' => 'Modern glass coffee table with metal frame']),
                'price' => 45999,
                'sale_price' => null,
                'sku' => 'COFFEE-TABLE-001',
                'stock_quantity' => 12,
                'in_stock' => true,
                'featured' => false,
                'weight' => 35.0,
                'dimensions' => '120x60x45',
                'material' => 'Glass & Metal',
                'color' => 'Clear & Gold',
                'status' => 'active',
                'category_id' => $livingRoomCategory->id,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
