<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArtworksSeeder extends Seeder
{
    public function run(): void
    {
        $artists = Artist::with('user')->get();
        
        if ($artists->isEmpty()) {
            return;
        }
        
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            return;
        }

        // Diverse artwork templates
        $artworkTemplates = [
            // Music
            ['title' => 'Desert Sound', 'description' => 'Electronic music track inspired by Saharan rhythms'],
            ['title' => 'Love Melody', 'description' => 'Romantic song in local dialect'],
            ['title' => 'City Rhythm', 'description' => 'Modern beat in hip-hop style'],
            ['title' => 'Nature Sounds', 'description' => 'Collection of natural sound effects'],
            ['title' => 'Freedom Anthem', 'description' => 'National music piece'],
            ['title' => 'Dance of Hope', 'description' => 'Soundtrack for short film'],

            // Visual Arts
            ['title' => 'Memory Canvas', 'description' => 'Oil painting expressing cultural heritage'],
            ['title' => 'Street Drawings', 'description' => 'Pencil sketches of old city neighborhoods'],
            ['title' => 'Interactive Digital Art', 'description' => 'Digital design using AI technologies'],
            ['title' => 'Heritage Photography', 'description' => 'Photographic series of historical landmarks'],
            ['title' => 'Stone Sculpture', 'description' => 'Stone statue representing national unity'],
            ['title' => 'Brand Identity Design', 'description' => 'Complete visual identity for a brand'],

            // Film & Video
            ['title' => 'Short Journey', 'description' => 'Short film about migration and return'],
            ['title' => 'Dramatic Scene', 'description' => 'Excerpt from a feature film'],
            ['title' => 'Animated Story', 'description' => 'Traditional animation for children'],
            ['title' => 'Craft Documentary', 'description' => 'Documentary about traditional crafts'],
            ['title' => 'Video Art', 'description' => 'Experimental video artwork'],

            // Literature
            ['title' => 'Nostalgia Poem', 'description' => 'Collection of poems about roots and belonging'],
            ['title' => 'Art Analysis Article', 'description' => 'In-depth analysis of contemporary art'],
            ['title' => 'Novel Chapter', 'description' => 'First chapter of historical novel'],
            ['title' => 'Film Script', 'description' => 'Drama film screenplay'],
            ['title' => 'Cultural Podcast', 'description' => 'Episode from talk show about arts'],
            ['title' => 'Digital Book', 'description' => 'E-book about art history'],
        ];

        $artworkCounter = 1;

        foreach ($artists as $artist) {
            // Create 3-8 artworks for each artist
            $artworksCount = rand(3, 8);

            // Get random categories for the artist
            $randomCategories = $categories->random(min($artworksCount, $categories->count()));

            foreach ($randomCategories as $index => $category) {
                $template = $artworkTemplates[array_rand($artworkTemplates)];

                // Customize title and description to be unique
                $uniqueTitle = $template['title'] . ' ' . $artworkCounter;
                $uniqueDescription = $template['description'] . ' - by ' . $artist->stage_name;

                Artwork::firstOrCreate(
                    [
                        'artist_id' => $artist->id,
                        'title' => $uniqueTitle,
                    ],
                    [
                        'category_id' => $category->id,
                        'description' => $uniqueDescription,
                        'status' => 'APPROVED',
                        'platform_tax_status' => rand(0, 1) ? 'PAID' : 'PENDING',
                    ]
                );

                $artworkCounter++;
            }
        }
    }
}
