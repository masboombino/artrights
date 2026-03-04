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
    // Algerian wilayas official numbers
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

    public function run(): void
    {
        // Ensure roles are loaded
        $roleIds = Role::pluck('id', 'name')->toArray();
        
        if (empty($roleIds)) {
            return;
        }

        $password = Hash::make('11223344');

        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super admin',
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

        // Lists of names for artists
        $firstNames = [
            'Ahmed', 'Fatima', 'Mohamed', 'Aisha', 'Ali', 'Maryam', 'Omar', 'Khadija', 'Hassan', 'Zahra',
            'Ibrahim', 'Layla', 'Youssef', 'Noor', 'Abdullah', 'Sarah', 'Khaled', 'Iman', 'Saad', 'Fatima',
            'Abdelrahman', 'Huda', 'Abdelaziz', 'Asmaa', 'Mustafa', 'Rakia', 'Ahmed', 'Fatima', 'Ali', 'Zainab'
        ];

        $lastNames = [
            'Ben Ali', 'Zerouali', 'Arab', 'Algerian', 'Ben Mohamed', 'Sherif', 'Bashir', 'Saleh',
            'Maghrebi', 'Taher', 'Farouk', 'Mansour', 'Husseini', 'Andalusi', 'Belidi', 'Tipazi',
            'Chelfi', 'Ouahrani', 'Constantini', 'Algerian', 'Tlemceni', 'Tiaret', 'Bejaia', 'Setif'
        ];

        $stageNameSuffixes = [
            'Art', 'Music', 'Studio', 'Design', 'Media', 'Works', 'Creative', 'Vision', 'Sound', 'Beats',
            'Digital', 'Film', 'Photo', 'Graphic', 'Video', 'Animation', 'Writing', 'Poetry', 'Sculpture', 'Paint'
        ];

        foreach ($agencies as $agency) {
            $wilayaNumber = $this->getWilayaNumber($agency->wilaya);
            
            // Create 1 Admin for each agency: admin{wilayaNumber}@gmail.com
            // Example: admin28@gmail.com for MSila
            $adminEmail = 'admin' . $wilayaNumber . '@gmail.com';
            $admin = User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Admin ' . $agency->wilaya,
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

            // Create 3 Gestionnaires for each agency
            // Gest 1: gest{wilayaNumber}@gmail.com
            // Gest 2: gest{wilayaNumber}{wilayaNumber}@gmail.com
            // Gest 3: gest{wilayaNumber}{wilayaNumber}{wilayaNumber}@gmail.com
            // Example for MSila (28): gest28@gmail.com, gest2828@gmail.com, gest282828@gmail.com
            for ($gestNum = 1; $gestNum <= 3; $gestNum++) {
                $gestEmailNumber = str_repeat($wilayaNumber, $gestNum);
                $gestEmail = 'gest' . $gestEmailNumber . '@gmail.com';
                $gestionnaire = User::firstOrCreate(
                    ['email' => $gestEmail],
                    [
                        'name' => 'Gestionnaire ' . $agency->wilaya . ' ' . $gestNum,
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

            // Create 3 Agents for each agency
            // Agent 1: agent{wilayaNumber}@gmail.com
            // Agent 2: agent{wilayaNumber}{wilayaNumber}@gmail.com
            // Agent 3: agent{wilayaNumber}{wilayaNumber}{wilayaNumber}@gmail.com
            // Example for MSila (28): agent28@gmail.com, agent2828@gmail.com, agent282828@gmail.com
            for ($agentNum = 1; $agentNum <= 3; $agentNum++) {
                $agentEmailNumber = str_repeat($wilayaNumber, $agentNum);
                $agentEmail = 'agent' . $agentEmailNumber . '@gmail.com';
                $agentUser = User::firstOrCreate(
                    ['email' => $agentEmail],
                    [
                        'name' => 'Agent ' . $agency->wilaya . ' ' . $agentNum,
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

            // Create 3 Artists for each agency
            // Artist 1: artist{wilayaNumber}@gmail.com
            // Artist 2: artist{wilayaNumber}{wilayaNumber}@gmail.com
            // Artist 3: artist{wilayaNumber}{wilayaNumber}{wilayaNumber}@gmail.com
            // Example for MSila (28): artist28@gmail.com, artist2828@gmail.com, artist282828@gmail.com
            for ($artistNum = 1; $artistNum <= 3; $artistNum++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = $firstName . ' ' . $lastName;

                // Build email: repeat wilaya number based on artist number
                $artistEmailNumber = str_repeat($wilayaNumber, $artistNum);
                $artistEmail = 'artist' . $artistEmailNumber . '@gmail.com';

                $stageNameSuffix = $stageNameSuffixes[array_rand($stageNameSuffixes)];
                $stageName = $firstName . $stageNameSuffix;

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

                // List of diverse birth places
                $birthPlaces = [
                    $agency->wilaya, 'Algiers', 'Oran', 'Constantine', 'Annaba', 'Setif', 'Tlemcen',
                    'Blida', 'Batna', 'Djelfa', 'Bejaia', 'Sidi Bel Abbes', 'Biskra', 'Tebessa', 'Tiaret'
                ];

                $artist = Artist::firstOrCreate(
                    ['user_id' => $artistUser->id],
                    [
                        'agency_id' => $agency->id,
                        'stage_name' => $stageName,
                        'address' => 'Address ' . $artistNum . ', ' . $agency->wilaya,
                        'birth_place' => $birthPlaces[array_rand($birthPlaces)],
                        'birth_date' => Carbon::now()->subYears(rand(20, 50)),
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
