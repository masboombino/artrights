<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\AgencyWallet;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        // Comprehensive list of all 58 Algerian provinces (wilayas)
        $algerianWilayas = [
            'Adrar', 'Chlef', 'Laghouat', 'Oum El Bouaghi', 'Batna', 'Bejaia', 'Biskra', 'Bechar',
            'Blida', 'Bouira', 'Tamanrasset', 'Tebessa', 'Tlemcen', 'Tiaret', 'Tizi Ouzou', 'Algiers',
            'Djelfa', 'Jijel', 'Setif', 'Saida', 'Skikda', 'Sidi Bel Abbes', 'Annaba', 'Guelma',
            'Constantine', 'Medea', 'Mostaganem', 'MSila', 'Mascara', 'Ouargla', 'Oran', 'El Bayadh',
            'Illizi', 'Bordj Bou Arreridj', 'Boumerdes', 'El Tarf', 'Tindouf', 'Tissemsilt', 'El Oued',
            'Khenchela', 'Souk Ahras', 'Tipaza', 'Mila', 'Ain Defla', 'Naama', 'Ain Temouchent',
            'Ghardaia', 'Relizane', 'El M\'Ghair', 'El Menia', 'Ouled Djellal', 'Bordj Badji Mokhtar',
            'Beni Abbes', 'In Salah', 'In Guezzam', 'Touggourt', 'Djanet'
        ];

        $agencies = [];
        foreach ($algerianWilayas as $wilaya) {
            $agencies[] = [
                'agency_name' => $wilaya . ' Office',
                'wilaya' => $wilaya
            ];
        }

        foreach ($agencies as $agency) {
            Agency::firstOrCreate(
                ['agency_name' => $agency['agency_name']],
                ['wilaya' => $agency['wilaya']]
            );
        }
        
        Agency::all()->each(function ($agency) {
            $wallet = AgencyWallet::where('agency_id', $agency->id)->first();
            if (!$wallet) {
                AgencyWallet::create([
                    'agency_id' => $agency->id,
                    'balance' => 0
                ]);
            }
        });
    }
}

