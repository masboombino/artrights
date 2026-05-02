<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArtworksSeeder extends Seeder
{
    /**
     * Real works by featured Algerian artists (keyed by stage_name).
     * Year helps the description read like a real catalog entry.
     */
    private function realArtworksByStageName(): array
    {
        return [
            'Cheb Khaled' => [
                ['title' => 'Didi',             'year' => 1992, 'desc' => 'Rai single from the album Khaled'],
                ['title' => 'Aicha',            'year' => 1996, 'desc' => 'Single from the album Sahra, music by Jean-Jacques Goldman'],
                ['title' => 'Abdel Kader',      'year' => 1998, 'desc' => 'Featured with Rachid Taha and Faudel on the 1, 2, 3 Soleils live album'],
                ['title' => 'El Arbi',          'year' => 1993, 'desc' => 'Rai track from the album N\'ssi N\'ssi'],
                ['title' => 'Wahrane Wahrane',  'year' => 1996, 'desc' => 'Homage to the city of Oran from the album Sahra'],
                ['title' => 'C\'est la vie',    'year' => 2012, 'desc' => 'Title track of the album of the same name'],
                ['title' => 'N\'ssi N\'ssi',    'year' => 1993, 'desc' => 'Title track produced with Don Was'],
                ['title' => 'Trigue Lycée',     'year' => 1988, 'desc' => 'Classic early-career Rai song'],
            ],
            'Dahmane El Harrachi' => [
                ['title' => 'Ya Rayah',            'year' => 1973, 'desc' => 'Chaabi anthem of exile, later covered by Rachid Taha'],
                ['title' => 'Galou El Arab Galou', 'year' => 1970, 'desc' => 'Chaabi song with traditional mandole'],
                ['title' => 'Hak ou Bak',          'year' => 1971, 'desc' => 'Chaabi piece recorded with Studio Pathe'],
                ['title' => 'El Ghaya',            'year' => 1972, 'desc' => 'Philosophical Chaabi composition'],
                ['title' => 'Ma Zelt Nhab Chabab', 'year' => 1975, 'desc' => 'Chaabi reflection on youth'],
                ['title' => 'Ki Nemchi u Nehki',   'year' => 1974, 'desc' => 'Chaabi dialogue song'],
                ['title' => 'Ah Ya Noual',         'year' => 1976, 'desc' => 'Romantic Chaabi ballad'],
            ],
            'Cheb Mami' => [
                ['title' => 'Parisien du Nord', 'year' => 1998, 'desc' => 'Collaboration with K-Mel fusing Rai and hip-hop'],
                ['title' => 'Saida',            'year' => 1999, 'desc' => 'Single from the album Meli Meli'],
                ['title' => 'Viens Habibi',     'year' => 2001, 'desc' => 'Single from the album Dellali'],
                ['title' => 'Douni El Bladi',   'year' => 2006, 'desc' => 'Title from Layali album about longing for home'],
                ['title' => 'Desert Rose',      'year' => 2000, 'desc' => 'Global hit duet with Sting'],
                ['title' => 'El Hfih',          'year' => 1990, 'desc' => 'Early Rai recording'],
                ['title' => 'Meli Meli',        'year' => 1999, 'desc' => 'Title track of the album'],
                ['title' => 'Bledi',            'year' => 2006, 'desc' => 'Track about the homeland from Layali'],
            ],
            'Cheb Hasni' => [
                ['title' => 'Baida Mon Amour',       'year' => 1989, 'desc' => 'Early Rai romantic cassette release'],
                ['title' => 'El Beida',              'year' => 1992, 'desc' => 'Iconic Rai ballad'],
                ['title' => 'Tal Ghyabek Ya Ghzali', 'year' => 1993, 'desc' => 'Rai duet-style romantic track'],
                ['title' => 'Ki Kount Sghir',        'year' => 1991, 'desc' => 'Rai song about childhood'],
                ['title' => 'Cheft Eli Yebkini',     'year' => 1994, 'desc' => 'Rai ballad recorded shortly before his death'],
                ['title' => 'Sentek Ma Nensaha',     'year' => 1993, 'desc' => 'Rai love song'],
                ['title' => 'El Visa',               'year' => 1992, 'desc' => 'Rai song about emigration'],
            ],
            'Cheikha Rimitti' => [
                ['title' => 'Charrag Gataa', 'year' => 1954, 'desc' => 'Landmark Bedoui-Rai track, one of the first modern Rai recordings'],
                ['title' => 'Nouar',         'year' => 1976, 'desc' => 'Traditional Bedoui performance'],
                ['title' => 'El Belbla',     'year' => 1986, 'desc' => 'Bedoui-Rai recording released on cassette'],
                ['title' => 'Hak Ya Rai',    'year' => 1994, 'desc' => 'Rai track from the album Sidi Mansour'],
                ['title' => 'Ya Louleydi',   'year' => 1990, 'desc' => 'Traditional Bedoui-Rai song'],
            ],
            'Houari Benchenet' => [
                ['title' => 'Malgré Maridha', 'year' => 1985, 'desc' => 'Wahrani Rai romantic classic'],
                ['title' => 'Ghaida Ya Ghaida', 'year' => 1987, 'desc' => 'Wahrani Rai track on cassette'],
                ['title' => 'El Harba Wine',  'year' => 1988, 'desc' => 'Wahrani Rai track about emigration'],
            ],
            'Bellemou' => [
                ['title' => 'Rai Rai',        'year' => 1978, 'desc' => 'Pop-Rai instrumental that helped modernize Rai'],
                ['title' => 'Medahate',       'year' => 1982, 'desc' => 'Electric trumpet and synth fusion'],
                ['title' => 'Aiylat Bouterfas', 'year' => 1981, 'desc' => 'Pop-Rai instrumental with electric trumpet lead'],
            ],
            'Idir' => [
                ['title' => 'A Vava Inouva', 'year' => 1973, 'desc' => 'Kabyle folk song that reached international audiences'],
                ['title' => 'Ssendu',        'year' => 1976, 'desc' => 'Kabyle track from the album A Vava Inouva'],
                ['title' => 'Thugdidh',      'year' => 1979, 'desc' => 'Kabyle folk song from the album Ay Arrac Nnegh'],
                ['title' => 'Zwith Rwith',   'year' => 1993, 'desc' => 'Kabyle folk song from the album Les chasseurs de lumières'],
                ['title' => 'A Ruyat Ryul',  'year' => 1999, 'desc' => 'Kabyle ballad from the album Identités'],
            ],
            'Matoub Lounes' => [
                ['title' => 'L\'Artiste',                          'year' => 1988, 'desc' => 'Kabyle political song from the album L\'Ironie du Sort'],
                ['title' => 'Thameghra',                            'year' => 1985, 'desc' => 'Kabyle cassette release'],
                ['title' => 'Kenza',                                'year' => 1999, 'desc' => 'Posthumous album title track dedicated to his daughter'],
                ['title' => 'Regard sur l\'Histoire d\'un Pays Damné', 'year' => 1991, 'desc' => 'Kabyle concept album track'],
                ['title' => 'Tiyita',                               'year' => 1993, 'desc' => 'Kabyle song from Communion avec la Patrie'],
            ],
            'Lounis Ait Menguellet' => [
                ['title' => 'Ammi',           'year' => 1981, 'desc' => 'Kabyle ballad from the album Ammi'],
                ['title' => 'Abrid Neltigara', 'year' => 1985, 'desc' => 'Kabyle track about the road of dignity'],
                ['title' => 'Asefru',         'year' => 1987, 'desc' => 'Kabyle poetic composition'],
                ['title' => 'Izem',           'year' => 1993, 'desc' => 'Kabyle song from the album of the same name'],
            ],
            'Mohamed Tahar Fergani' => [
                ['title' => 'Ya Msafer',         'year' => 1960, 'desc' => 'Malouf traditional Constantinois piece'],
                ['title' => 'Nouba Dhil',        'year' => 1975, 'desc' => 'Complete Malouf Nouba in mode Dhil'],
                ['title' => 'Istikhbar Zidane',  'year' => 1968, 'desc' => 'Vocal improvisation in Malouf mode Zidane'],
            ],
            'Salim Fergani' => [
                ['title' => 'Nouba Raml',        'year' => 1998, 'desc' => 'Andalusian Nouba in mode Raml'],
                ['title' => 'Touchia Sika',      'year' => 2002, 'desc' => 'Orchestral Malouf overture in mode Sika'],
                ['title' => 'Hawzi Constantine', 'year' => 2010, 'desc' => 'Hawzi vocal interpretation'],
            ],
            'Beihdja Rahal' => [
                ['title' => 'Nouba Maya',       'year' => 1995, 'desc' => 'San\'a Nouba in mode Maya'],
                ['title' => 'Nouba Rasd Dhil',  'year' => 2001, 'desc' => 'Complete San\'a Nouba'],
                ['title' => 'Inqilab Zidane',   'year' => 2007, 'desc' => 'San\'a vocal suite'],
            ],
        ];
    }

    /**
     * Generic but realistic artwork templates for non-featured artists.
     */
    private function genericTemplates(): array
    {
        return [
            ['title' => 'Noudjoum El Djazair',   'desc' => 'Modern Rai track with dialectal lyrics'],
            ['title' => 'Bladi Ya Bladi',        'desc' => 'Patriotic song celebrating the homeland'],
            ['title' => 'Layali El Kasbah',      'desc' => 'Chaabi-inspired track about Algiers nightlife'],
            ['title' => 'Sahra Wahrania',        'desc' => 'Oranais Rai track about nightlife'],
            ['title' => 'Habibti El Baida',      'desc' => 'Romantic Rai ballad in Algerian dialect'],
            ['title' => 'Ghorba',                'desc' => 'Chaabi-Rai composition about emigration'],
            ['title' => 'Djurdjura',             'desc' => 'Kabyle instrumental inspired by the mountains'],
            ['title' => 'Imazighen',             'desc' => 'Amazigh-themed vocal piece'],
            ['title' => 'Souvenirs d\'Alger',    'desc' => 'Photographic series of the Algiers Casbah'],
            ['title' => 'Tassili Colors',        'desc' => 'Painting inspired by the Tassili rock art'],
            ['title' => 'Casbah Stories',        'desc' => 'Short documentary about the Casbah of Algiers'],
            ['title' => 'Sahara Breeze',         'desc' => 'Instrumental track mixing Gnawa and electronic elements'],
            ['title' => 'Gnawa Fusion',          'desc' => 'Instrumental fusion of Gnawa rhythms and modern beats'],
            ['title' => 'Matloue',               'desc' => 'Traditional poem set to music'],
            ['title' => 'Andaloussi Nights',     'desc' => 'Andalusian classical vocal performance'],
        ];
    }

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

        $realMap = $this->realArtworksByStageName();
        $generics = $this->genericTemplates();

        $songCategory = $categories->firstWhere('name', 'Song') ?? $categories->first();
        $musicTrackCategory = $categories->firstWhere('name', 'Music Track') ?? $songCategory;
        $photographyCategory = $categories->firstWhere('name', 'Photography') ?? $songCategory;
        $paintingCategory = $categories->firstWhere('name', 'Painting') ?? $songCategory;
        $documentaryCategory = $categories->firstWhere('name', 'Documentary Clip') ?? $songCategory;

        $artworkCounter = 1;

        foreach ($artists as $artist) {
            $stage = $artist->stage_name;

            if ($stage && isset($realMap[$stage])) {
                foreach ($realMap[$stage] as $work) {
                    Artwork::firstOrCreate(
                        [
                            'artist_id' => $artist->id,
                            'title' => $work['title'],
                        ],
                        [
                            'category_id' => $songCategory->id,
                            'description' => $work['desc'] . ' (' . $work['year'] . ')',
                            'status' => 'APPROVED',
                            'platform_tax_status' => 'PAID',
                        ]
                    );
                    $artworkCounter++;
                }
                continue;
            }

            $count = 4;
            $pickedIndexes = [];
            for ($i = 0; $i < $count; $i++) {
                $idx = ($artist->id * 7 + $i * 3) % count($generics);
                if (in_array($idx, $pickedIndexes, true)) {
                    $idx = ($idx + 1) % count($generics);
                }
                $pickedIndexes[] = $idx;

                $template = $generics[$idx];
                $title = $template['title'] . ' — ' . $artist->stage_name;

                $category = match ($i % 5) {
                    0 => $songCategory,
                    1 => $musicTrackCategory,
                    2 => $paintingCategory,
                    3 => $photographyCategory,
                    default => $documentaryCategory,
                };

                Artwork::firstOrCreate(
                    [
                        'artist_id' => $artist->id,
                        'title' => $title,
                    ],
                    [
                        'category_id' => $category->id,
                        'description' => $template['desc'] . ' — performed by ' . $artist->stage_name,
                        'status' => 'APPROVED',
                        'platform_tax_status' => ($artworkCounter % 3 === 0) ? 'PENDING' : 'PAID',
                    ]
                );

                $artworkCounter++;
            }
        }
    }
}
