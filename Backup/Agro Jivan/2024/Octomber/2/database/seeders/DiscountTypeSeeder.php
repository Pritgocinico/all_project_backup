<?php

namespace Database\Seeders;

use App\Models\DiscountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DocumentType
        DiscountType::create([
            'title' => 'Buy X get Y 30 % discount',
            'description' => 'Discount specific products or collections of products.',
            'type' => 'Product discount',
        ]);
        DiscountType::create([
            'title' => 'Buy X get Y',
            'description' => 'Discount products based on a customer’s purchase.',
            'type' => 'Product discount',
        ]);
    }
}
