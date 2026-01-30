<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\AuctionCategory;

class AuctionCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'ძველი წიგნები',
            'ხელნაწერები',
            'ფერწერა / ნახატები',
            'ვინტაჟური ნივთები',
            'ანტიკვარული დოკუმენტები',
            'სხვა',
        ];

        foreach ($categories as $name) {
            AuctionCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
