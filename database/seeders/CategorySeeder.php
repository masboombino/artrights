<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Music Track', 'description' => 'Instrumental tracks', 'coefficient' => 5],
            ['name' => 'Sound Effect', 'description' => 'FX and sound design', 'coefficient' => 3],
            ['name' => 'Song', 'description' => 'Vocal songs', 'coefficient' => 6],
            ['name' => 'Beat', 'description' => 'Beats and instrumentals', 'coefficient' => 4],
            //
            ['name' => 'Short Film', 'description' => 'Short video productions', 'coefficient' => 6],
            ['name' => 'Movie Scene', 'description' => 'Scenes extracted from movies', 'coefficient' => 7],
            ['name' => 'Animation', 'description' => 'Animated productions', 'coefficient' => 5],
            ['name' => 'Documentary Clip', 'description' => 'Documentary excerpts', 'coefficient' => 4],
            ['name' => 'Video Art', 'description' => 'Experimental video works', 'coefficient' => 5.5],
            ['name' => 'Podcast Episode', 'description' => 'Audio podcast content', 'coefficient' => 2.8],
            ['name' => 'Audiobook', 'description' => 'Narrated book recordings', 'coefficient' => 3.2],
            //
            ['name' => 'Painting', 'description' => 'Traditional and modern paintings', 'coefficient' => 2],
            ['name' => 'Drawing', 'description' => 'Hand drawn artworks', 'coefficient' => 1.5],
            ['name' => 'Digital Art', 'description' => 'Digital illustrations and renders', 'coefficient' => 2.5],
            ['name' => 'Photography', 'description' => 'Still photography works', 'coefficient' => 1.7],
            ['name' => 'Illustrations', 'description' => 'Editorial and concept illustrations', 'coefficient' => 1.8],
            ['name' => 'Graphic Design', 'description' => 'Commercial and artistic graphics', 'coefficient' => 2.2],
            //
            ['name' => 'Article', 'description' => 'Written articles', 'coefficient' => 1.5],
            ['name' => 'Poem', 'description' => 'Poetry works', 'coefficient' => 1.2],
            ['name' => 'Book Chapter', 'description' => 'Chapters from books', 'coefficient' => 2],
            ['name' => 'Script', 'description' => 'Scripts and scenarios', 'coefficient' => 3],
            ['name' => 'Sculpture', 'description' => 'Three dimensional art pieces', 'coefficient' => 3.5],
            ['name' => 'Novel', 'description' => 'Full length fiction works', 'coefficient' => 2.5],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'coefficient' => $category['coefficient'],
                ]
            );
        }
    }
}

