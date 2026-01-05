<?php

namespace Database\Seeders;

use App\Models\ShopType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shopTypes = [
            // Food & Beverage
            ['name' => 'Restaurant', 'category' => 'Food & Beverage', 'description' => 'Full-service restaurant'],
            ['name' => 'Coffee Shop', 'category' => 'Food & Beverage', 'description' => 'Coffee shop or café'],
            ['name' => 'Fast Food', 'category' => 'Food & Beverage', 'description' => 'Fast food restaurant'],
            ['name' => 'Bakery', 'category' => 'Food & Beverage', 'description' => 'Bakery and pastry shop'],
            ['name' => 'Ice Cream Shop', 'category' => 'Food & Beverage', 'description' => 'Ice cream parlor'],
            ['name' => 'Bar', 'category' => 'Food & Beverage', 'description' => 'Bar or pub'],
            ['name' => 'Cafeteria', 'category' => 'Food & Beverage', 'description' => 'Self-service cafeteria'],

            // Entertainment
            ['name' => 'Cinema', 'category' => 'Entertainment', 'description' => 'Movie theater'],
            ['name' => 'Theater', 'category' => 'Entertainment', 'description' => 'Live theater or performance venue'],
            ['name' => 'Concert Hall', 'category' => 'Entertainment', 'description' => 'Concert venue'],
            ['name' => 'Nightclub', 'category' => 'Entertainment', 'description' => 'Nightclub or disco'],
            ['name' => 'Karaoke Bar', 'category' => 'Entertainment', 'description' => 'Karaoke venue'],
            ['name' => 'Amusement Park', 'category' => 'Entertainment', 'description' => 'Theme park or amusement center'],

            // Retail & Shopping
            ['name' => 'Retail Store', 'category' => 'Retail & Shopping', 'description' => 'General retail store'],
            ['name' => 'Supermarket', 'category' => 'Retail & Shopping', 'description' => 'Large grocery store'],
            ['name' => 'Pharmacy', 'category' => 'Retail & Shopping', 'description' => 'Pharmacy or drugstore'],
            ['name' => 'Bookstore', 'category' => 'Retail & Shopping', 'description' => 'Book store'],
            ['name' => 'Clothing Store', 'category' => 'Retail & Shopping', 'description' => 'Clothing and fashion store'],
            ['name' => 'Electronics Store', 'category' => 'Retail & Shopping', 'description' => 'Electronics retailer'],
            ['name' => 'Jewelry Store', 'category' => 'Retail & Shopping', 'description' => 'Jewelry and accessories'],
            ['name' => 'Sporting Goods', 'category' => 'Retail & Shopping', 'description' => 'Sports equipment store'],

            // Services
            ['name' => 'Hotel', 'category' => 'Hospitality', 'description' => 'Hotel or accommodation'],
            ['name' => 'Internet Cafe', 'category' => 'Technology', 'description' => 'Internet café'],
            ['name' => 'Game Center', 'category' => 'Entertainment', 'description' => 'Video game arcade'],
            ['name' => 'Beauty Salon', 'category' => 'Services', 'description' => 'Hair salon or beauty parlor'],
            ['name' => 'Gym', 'category' => 'Services', 'description' => 'Fitness center or gymnasium'],
            ['name' => 'Laundry', 'category' => 'Services', 'description' => 'Laundry service'],
            ['name' => 'Car Wash', 'category' => 'Services', 'description' => 'Automotive cleaning service'],
            ['name' => 'Pet Store', 'category' => 'Retail & Shopping', 'description' => 'Pet supplies store'],

            // Other
            ['name' => 'Office', 'category' => 'Business', 'description' => 'Business office'],
            ['name' => 'School', 'category' => 'Education', 'description' => 'Educational institution'],
            ['name' => 'Hospital', 'category' => 'Healthcare', 'description' => 'Medical facility'],
            ['name' => 'Bank', 'category' => 'Financial', 'description' => 'Banking institution'],
            ['name' => 'Post Office', 'category' => 'Government', 'description' => 'Postal service'],
            ['name' => 'Gas Station', 'category' => 'Transportation', 'description' => 'Fuel station'],
            ['name' => 'Auto Repair', 'category' => 'Services', 'description' => 'Automotive repair shop'],
        ];

        foreach ($shopTypes as $shopType) {
            ShopType::firstOrCreate(
                ['name' => $shopType['name']],
                $shopType
            );
        }
    }
}
