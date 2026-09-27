<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Income categories
            ['name' => 'Gaji', 'type' => 'income', 'icon' => '💼', 'color' => '#22C55E'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '💻', 'color' => '#3B82F6'],
            ['name' => 'Investasi', 'type' => 'income', 'icon' => '📈', 'color' => '#D4AF37'],
            ['name' => 'Bisnis', 'type' => 'income', 'icon' => '🏢', 'color' => '#8B5CF6'],
            ['name' => 'Bonus', 'type' => 'income', 'icon' => '🎁', 'color' => '#F59E0B'],
            ['name' => 'Lainnya', 'type' => 'income', 'icon' => '💰', 'color' => '#6B7280'],

            // Expense categories
            ['name' => 'Makanan', 'type' => 'expense', 'icon' => '🍽️', 'color' => '#EF4444'],
            ['name' => 'Transportasi', 'type' => 'expense', 'icon' => '🚗', 'color' => '#3B82F6'],
            ['name' => 'Belanja', 'type' => 'expense', 'icon' => '🛍️', 'color' => '#8B5CF6'],
            ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => '🏥', 'color' => '#22C55E'],
            ['name' => 'Listrik & Air', 'type' => 'expense', 'icon' => '⚡', 'color' => '#F59E0B'],
            ['name' => 'Internet & HP', 'type' => 'expense', 'icon' => '📱', 'color' => '#06B6D4'],
            ['name' => 'Hiburan', 'type' => 'expense', 'icon' => '🎬', 'color' => '#EC4899'],
            ['name' => 'Pendidikan', 'type' => 'expense', 'icon' => '📚', 'color' => '#D4AF37'],
            ['name' => 'Olahraga', 'type' => 'expense', 'icon' => '⚽', 'color' => '#84CC16'],
            ['name' => 'Cicilan', 'type' => 'expense', 'icon' => '💳', 'color' => '#F97316'],
            ['name' => 'Sewa', 'type' => 'expense', 'icon' => '🏠', 'color' => '#78716C'],
            ['name' => 'Lainnya', 'type' => 'expense', 'icon' => '📋', 'color' => '#6B7280'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name'], 'type' => $category['type']],
                $category
            );
        }
    }
}
