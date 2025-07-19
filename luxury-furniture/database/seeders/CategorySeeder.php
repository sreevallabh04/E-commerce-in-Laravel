<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => json_encode(['en' => 'Living Room']),
                'slug' => 'living-room',
                'description' => json_encode(['en' => 'Create the perfect gathering space with our premium living room furniture.']),
                'image' => 'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=600',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'name' => json_encode(['en' => 'Bedroom']),
                'slug' => 'bedroom',
                'description' => json_encode(['en' => 'Design your dream sanctuary with our elegant bedroom furniture collection.']),
                'image' => 'https://images.pexels.com/photos/1350789/pexels-photo-1350789.jpeg?auto=compress&cs=tinysrgb&w=600',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'name' => json_encode(['en' => 'Dining']),
                'slug' => 'dining',
                'description' => json_encode(['en' => 'Elevate your dining experience with our sophisticated dining room furniture.']),
                'image' => 'https://images.pexels.com/photos/1571470/pexels-photo-1571470.jpeg?auto=compress&cs=tinysrgb&w=600',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'name' => json_encode(['en' => 'Office']),
                'slug' => 'office',
                'description' => json_encode(['en' => 'Boost productivity with our premium office furniture designed for comfort and style.']),
                'image' => 'https://images.pexels.com/photos/1571470/pexels-photo-1571470.jpeg?auto=compress&cs=tinysrgb&w=600',
                'status' => 'active',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
