<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Agent;
use App\Models\Artist;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    private function getWilayaNumber($wilayaName): string
    {
        $wilayaNumbers = [
            'Adrar' => '01', 'Chlef' => '02', 'Laghouat' => '03', 'Oum El Bouaghi' => '04', 'Batna' => '05',
            'Bejaia' => '06', 'Biskra' => '07', 'Bechar' => '08', 'Blida' => '09', 'Bouira' => '10',
            'Tamanrasset' => '11', 'Tebessa' => '12', 'Tlemcen' => '13', 'Tiaret' => '14', 'Tizi Ouzou' => '15',
            'Algiers' => '16', 'Djelfa' => '17', 'Jijel' => '18', 'Setif' => '19', 'Saida' => '20',
            'Skikda' => '21', 'Sidi Bel Abbes' => '22', 'Annaba' => '23', 'Guelma' => '24', 'Constantine' => '25',
            'Medea' => '26', 'Mostaganem' => '27', 'MSila' => '28', 'Mascara' => '29', 'Ouargla' => '30',
            'Oran' => '31', 'El Bayadh' => '32', 'Illizi' => '33', 'Bordj Bou Arreridj' => '34', 'Boumerdes' => '35',
            'El Tarf' => '36', 'Tindouf' => '37', 'Tissemsilt' => '38', 'El Oued' => '39', 'Khenchela' => '40',
            'Souk Ahras' => '41', 'Tipaza' => '42', 'Mila' => '43', 'Ain Defla' => '44', 'Naama' => '45',
            'Ain Temouchent' => '46', 'Ghardaia' => '47', 'Relizane' => '48', 'El M\'Ghair' => '57', 'El Menia' => '58',
            'Ouled Djellal' => '51', 'Bordj Badji Mokhtar' => '50', 'Beni Abbes' => '52', 'In Salah' => '53',
            'In Guezzam' => '54', 'Touggourt' => '55', 'Djanet' => '56',
            'El Hegueir' => '59', 'Sebdou' => '60', 'Beni Saf' => '61', 'Telerghma' => '62', 'Azzaba' => '63',
            'Djemila' => '64', 'El Eulma' => '65', 'Barika' => '66', 'Menaa' => '67', 'Ksar El Hirane' => '68',
            'Sidi Khaled' => '69', 'Sidi Aissa' => '70'
        ];

        return $wilayaNumbers[$wilayaName] ?? str_pad($wilayaName, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Real Algerian artists mapped by wilaya name.
     * Algiers features the iconic names requested; other wilayas can extend this list.
     */
    private function realArtistsByWilaya(): array
    {
        return [
            'Algiers' => [
                [
                    'real_name'   => 'Khaled Hadj Brahim',
                    'stage_name'  => 'Cheb Khaled',
                    'address'     => '12 Rue Didouche Mourad, Alger-Centre',
                    'birth_place' => 'Sidi El Houari, Oran',
                    'birth_date'  => '1960-02-29',
                ],
                [
                    'real_name'   => 'Abderrahmane Amrani',
                    'stage_name'  => 'Dahmane El Harrachi',
                    'address'     => 'Cité Nasr, El Harrach, Alger',
                    'birth_place' => 'El Biar, Alger',
                    'birth_date'  => '1926-07-07',
                ],
                [
                    'real_name'   => 'Mohamed Khelifati',
                    'stage_name'  => 'Cheb Mami',
                    'address'     => '45 Boulevard Mohamed V, Bab El Oued, Alger',
                    'birth_place' => 'Saida',
                    'birth_date'  => '1966-07-11',
                ],
                [
                    'real_name'   => 'Hasni Chakroun',
                    'stage_name'  => 'Cheb Hasni',
                    'address'     => '8 Rue Hassiba Ben Bouali, Alger',
                    'birth_place' => 'Gambetta, Oran',
                    'birth_date'  => '1968-02-01',
                ],
            ],
            'Oran' => [
                [
                    'real_name'   => 'Cheikha Rimitti',
                    'stage_name'  => 'Cheikha Rimitti',
                    'address'     => 'Rue Larbi Ben Mhidi, Oran',
                    'birth_place' => 'Tessala, Sidi Bel Abbes',
                    'birth_date'  => '1923-05-08',
                ],
                [
                    'real_name'   => 'Houari Benchenet',
                    'stage_name'  => 'Houari Benchenet',
                    'address'     => 'Hai El Badr, Oran',
                    'birth_place' => 'Oran',
                    'birth_date'  => '1954-04-15',
                ],
                [
                    'real_name'   => 'Bellemou Messaoud',
                    'stage_name'  => 'Bellemou',
                    'address'     => 'Place d\'Armes, Oran',
                    'birth_place' => 'Ain Temouchent',
                    'birth_date'  => '1947-08-20',
                ],
            ],
            'Tizi Ouzou' => [
                [
                    'real_name'   => 'Hamid Cheriet',
                    'stage_name'  => 'Idir',
                    'address'     => 'Village Ait Lahcene, Beni Yenni',
                    'birth_place' => 'Ait Lahcene, Tizi Ouzou',
                    'birth_date'  => '1949-10-25',
                ],
                [
                    'real_name'   => 'Lounes Matoub',
                    'stage_name'  => 'Matoub Lounes',
                    'address'     => 'Taourirt Moussa, Beni Douala',
                    'birth_place' => 'Taourirt Moussa, Tizi Ouzou',
                    'birth_date'  => '1956-01-24',
                ],
                [
                    'real_name'   => 'Ait Menguellet Lounis',
                    'stage_name'  => 'Lounis Ait Menguellet',
                    'address'     => 'Ighil Bwammas, Beni Yenni',
                    'birth_place' => 'Ighil Bwammas, Tizi Ouzou',
                    'birth_date'  => '1950-01-17',
                ],
            ],
            'Constantine' => [
                [
                    'real_name'   => 'Mohamed Tahar Fergani',
                    'stage_name'  => 'Mohamed Tahar Fergani',
                    'address'     => 'Rue Abane Ramdane, Constantine',
                    'birth_place' => 'Constantine',
                    'birth_date'  => '1928-05-09',
                ],
                [
                    'real_name'   => 'Salim Fergani',
                    'stage_name'  => 'Salim Fergani',
                    'address'     => 'Vieille Ville, Constantine',
                    'birth_place' => 'Constantine',
                    'birth_date'  => '1958-06-15',
                ],
                [
                    'real_name'   => 'Beihdja Rahal',
                    'stage_name'  => 'Beihdja Rahal',
                    'address'     => 'Cite Ciloc, Constantine',
                    'birth_place' => 'Alger',
                    'birth_date'  => '1962-09-04',
                ],
            ],
        ];
    }

    /**
     * Pool of real Algerian full names for agencies that do not have featured artists.
     * Kept deterministic per agency via modulo so re-seeding is stable.
     */
    private function fallbackArtistPool(): array
    {
        return [
            ['real_name' => 'Karim Benabdellah', 'stage_name' => 'Karim B', 'birth_place' => 'Algiers'],
            ['real_name' => 'Samir Benfodil',    'stage_name' => 'Samir BF', 'birth_place' => 'Oran'],
            ['real_name' => 'Nadia Benahmed',    'stage_name' => 'Nadia B', 'birth_place' => 'Constantine'],
            ['real_name' => 'Rachid Bouazzi',    'stage_name' => 'Rachid Bz', 'birth_place' => 'Setif'],
            ['real_name' => 'Leila Chaoui',      'stage_name' => 'Leila Ch', 'birth_place' => 'Annaba'],
            ['real_name' => 'Amine Bousbia',     'stage_name' => 'Amine BS', 'birth_place' => 'Blida'],
            ['real_name' => 'Yacine Belhadj',    'stage_name' => 'Yacine BH', 'birth_place' => 'Tlemcen'],
            ['real_name' => 'Meriem Djerrah',    'stage_name' => 'Meriem Dj', 'birth_place' => 'Bejaia'],
            ['real_name' => 'Walid Chaabi',      'stage_name' => 'Walid Ch', 'birth_place' => 'Batna'],
            ['real_name' => 'Hanane Dib',        'stage_name' => 'Hanane D', 'birth_place' => 'Mostaganem'],
            ['real_name' => 'Sofiane Attar',     'stage_name' => 'Sofiane A', 'birth_place' => 'Ouargla'],
            ['real_name' => 'Yasmine Bouakkaz',  'stage_name' => 'Yasmine Bk', 'birth_place' => 'Biskra'],
            ['real_name' => 'Toufik Rahmani',    'stage_name' => 'Toufik R', 'birth_place' => 'Djelfa'],
            ['real_name' => 'Lydia Belkacem',    'stage_name' => 'Lydia Bk', 'birth_place' => 'Jijel'],
            ['real_name' => 'Fouad Zerouki',     'stage_name' => 'Fouad Z', 'birth_place' => 'Medea'],
            ['real_name' => 'Samira Khellaf',    'stage_name' => 'Samira Kh', 'birth_place' => 'Tipaza'],
            ['real_name' => 'Brahim Lakhdar',    'stage_name' => 'Brahim L', 'birth_place' => 'Mascara'],
            ['real_name' => 'Rania Boudjemaa',   'stage_name' => 'Rania Bj', 'birth_place' => 'Boumerdes'],
            ['real_name' => 'Djamel Laribi',     'stage_name' => 'Djamel L', 'birth_place' => 'Ghardaia'],
            ['real_name' => 'Imene Meddah',      'stage_name' => 'Imene M', 'birth_place' => 'Relizane'],
        ];
    }

    private function fallbackStaffPool(): array
    {
        return [
            ['first' => 'Mohamed', 'last' => 'Bensalah'],
            ['first' => 'Ahmed',   'last' => 'Belkacem'],
            ['first' => 'Youcef',  'last' => 'Mansouri'],
            ['first' => 'Sofiane', 'last' => 'Haddad'],
            ['first' => 'Nabil',   'last' => 'Saadi'],
            ['first' => 'Rachid',  'last' => 'Boukhelifa'],
            ['first' => 'Samir',   'last' => 'Kaci'],
            ['first' => 'Hakim',   'last' => 'Tounsi'],
            ['first' => 'Karim',   'last' => 'Benyoucef'],
            ['first' => 'Lahcen',  'last' => 'Messaoudi'],
            ['first' => 'Djamel',  'last' => 'Chergui'],
            ['first' => 'Farid',   'last' => 'Zitouni'],
            ['first' => 'Amel',    'last' => 'Boumediene'],
            ['first' => 'Nadia',   'last' => 'Chaabane'],
            ['first' => 'Leila',   'last' => 'Kherbache'],
            ['first' => 'Souad',   'last' => 'Hamdani'],
            ['first' => 'Ilhem',   'last' => 'Merad'],
            ['first' => 'Fatiha',  'last' => 'Ghomari'],
            ['first' => 'Wafa',    'last' => 'Sebti'],
            ['first' => 'Meriem',  'last' => 'Chaib'],
        ];
    }

    private function pickStaffName(int $seed): array
    {
        $pool = $this->fallbackStaffPool();
        return $pool[$seed % count($pool)];
    }

    public function run(): void
    {
        $roleIds = Role::pluck('id', 'name')->toArray();

        if (empty($roleIds)) {
            return;
        }

        $password = Hash::make('11223344');

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Rabah Djerrah',
                'password' => $password,
                'phone' => '0776920265',
                'role_id' => $roleIds['super_admin'] ?? null,
            ]
        );

        if (!$superAdmin->hasRole('super_admin')) {
            $superAdmin->syncRoles(['super_admin']);
        }

        $agencies = Agency::all();

        if ($agencies->isEmpty()) {
            return;
        }

        $realArtistsMap = $this->realArtistsByWilaya();
        $fallbackArtists = $this->fallbackArtistPool();

        foreach ($agencies as $agency) {
            $wilayaNumber = $this->getWilayaNumber($agency->wilaya);
            $wilayaSeed = (int) $wilayaNumber;

            $adminName = $this->pickStaffName($wilayaSeed);
            $adminEmail = 'admin' . $wilayaNumber . '@gmail.com';
            $admin = User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName['first'] . ' ' . $adminName['last'],
                    'password' => $password,
                    'phone' => '0551' . $wilayaNumber . '00000',
                    'agency_id' => $agency->id,
                    'role_id' => $roleIds['admin'] ?? null,
                ]
            );

            if (!$admin->hasRole('admin')) {
                $admin->syncRoles(['admin']);
            }

            if (!$agency->admin_id) {
                $agency->admin_id = $admin->id;
                $agency->save();
            }

            for ($gestNum = 1; $gestNum <= 3; $gestNum++) {
                $gestEmailNumber = str_repeat($wilayaNumber, $gestNum);
                $gestEmail = 'gest' . $gestEmailNumber . '@gmail.com';

                $gestName = $this->pickStaffName($wilayaSeed + $gestNum * 7);

                $gestionnaire = User::firstOrCreate(
                    ['email' => $gestEmail],
                    [
                        'name' => $gestName['first'] . ' ' . $gestName['last'],
                        'password' => $password,
                        'phone' => '0552' . str_pad($gestEmailNumber, 7, '0', STR_PAD_RIGHT),
                        'agency_id' => $agency->id,
                        'role_id' => $roleIds['gestionnaire'] ?? null,
                    ]
                );

                if (!$gestionnaire->hasRole('gestionnaire')) {
                    $gestionnaire->syncRoles(['gestionnaire']);
                }
            }

            for ($agentNum = 1; $agentNum <= 3; $agentNum++) {
                $agentEmailNumber = str_repeat($wilayaNumber, $agentNum);
                $agentEmail = 'agent' . $agentEmailNumber . '@gmail.com';

                $agentName = $this->pickStaffName($wilayaSeed + $agentNum * 13);

                $agentUser = User::firstOrCreate(
                    ['email' => $agentEmail],
                    [
                        'name' => $agentName['first'] . ' ' . $agentName['last'],
                        'password' => $password,
                        'phone' => '0553' . str_pad($agentEmailNumber, 7, '0', STR_PAD_RIGHT),
                        'agency_id' => $agency->id,
                        'role_id' => $roleIds['agent'] ?? null,
                    ]
                );

                if (!$agentUser->hasRole('agent')) {
                    $agentUser->syncRoles(['agent']);
                }

                Agent::firstOrCreate(
                    ['user_id' => $agentUser->id],
                    [
                        'agency_id' => $agency->id,
                        'badge_number' => 'AG-' . $wilayaNumber . '-' . $agentNum,
                    ]
                );
            }

            $featuredArtists = $realArtistsMap[$agency->wilaya] ?? [];
            $artistCount = !empty($featuredArtists) ? count($featuredArtists) : 3;

            for ($artistNum = 1; $artistNum <= $artistCount; $artistNum++) {
                $artistEmailNumber = str_repeat($wilayaNumber, $artistNum);
                $artistEmail = 'artist' . $artistEmailNumber . '@gmail.com';

                if (!empty($featuredArtists[$artistNum - 1])) {
                    $featured = $featuredArtists[$artistNum - 1];
                    $fullName = $featured['real_name'];
                    $stageName = $featured['stage_name'];
                    $address = $featured['address'];
                    $birthPlace = $featured['birth_place'];
                    $birthDate = Carbon::parse($featured['birth_date']);
                } else {
                    $fallback = $fallbackArtists[($wilayaSeed + $artistNum) % count($fallbackArtists)];
                    $fullName = $fallback['real_name'];
                    $stageName = $fallback['stage_name'];
                    $address = 'Rue ' . (($wilayaSeed * 3 + $artistNum) % 200 + 1) . ', ' . $agency->wilaya;
                    $birthPlace = $fallback['birth_place'];
                    $birthDate = Carbon::now()->subYears(22 + (($wilayaSeed + $artistNum * 5) % 35));
                }

                $artistUser = User::firstOrCreate(
                    ['email' => $artistEmail],
                    [
                        'name' => $fullName,
                        'password' => $password,
                        'phone' => '0554' . $wilayaNumber . str_pad($artistNum, 4, '0', STR_PAD_LEFT),
                        'agency_id' => $agency->id,
                        'role_id' => $roleIds['artist'] ?? null,
                    ]
                );

                if (!$artistUser->hasRole('artist')) {
                    $artistUser->syncRoles(['artist']);
                }

                $artist = Artist::firstOrCreate(
                    ['user_id' => $artistUser->id],
                    [
                        'agency_id' => $agency->id,
                        'stage_name' => $stageName,
                        'address' => $address,
                        'birth_place' => $birthPlace,
                        'birth_date' => $birthDate,
                        'status' => 'APPROVED',
                    ]
                );

                $wallet = Wallet::firstOrCreate(
                    ['artist_id' => $artist->id],
                    ['balance' => 0, 'last_transaction' => null]
                );

                if ($artist->wallet_id !== $wallet->id) {
                    $artist->wallet_id = $wallet->id;
                    $artist->save();
                }
            }
        }
    }
}
