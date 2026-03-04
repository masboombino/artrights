<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\AgencyWallet;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        // Comprehensive list of all 70 Algerian provinces (wilayas)
        $algerianWilayas = [
            'Adrar', 'Chlef', 'Laghouat', 'Oum El Bouaghi', 'Batna', 'Bejaia', 'Biskra', 'Bechar',
            'Blida', 'Bouira', 'Tamanrasset', 'Tebessa', 'Tlemcen', 'Tiaret', 'Tizi Ouzou', 'Algiers',
            'Djelfa', 'Jijel', 'Setif', 'Saida', 'Skikda', 'Sidi Bel Abbes', 'Annaba', 'Guelma',
            'Constantine', 'Medea', 'Mostaganem', 'MSila', 'Mascara', 'Ouargla', 'Oran', 'El Bayadh',
            'Illizi', 'Bordj Bou Arreridj', 'Boumerdes', 'El Tarf', 'Tindouf', 'Tissemsilt', 'El Oued',
            'Khenchela', 'Souk Ahras', 'Tipaza', 'Mila', 'Ain Defla', 'Naama', 'Ain Temouchent',
            'Ghardaia', 'Relizane', 'El M\'Ghair', 'El Menia', 'Ouled Djellal', 'Bordj Badji Mokhtar',
            'Beni Abbes', 'In Salah', 'In Guezzam', 'Touggourt', 'Djanet',
            'El Hegueir', 'Sebdou', 'Beni Saf', 'Telerghma', 'Azzaba',
            'Djemila', 'El Eulma', 'Barika', 'Menaa', 'Ksar El Hirane',
            'Sidi Khaled', 'Sidi Aissa'
        ];

        $agencies = [];
        foreach ($algerianWilayas as $wilaya) {
            $agencies[] = [
                'agency_name' => $wilaya . ' Office',
                'wilaya' => $wilaya
            ];
        }

        foreach ($agencies as $agency) {
            $createdAgency = Agency::firstOrCreate(
                ['agency_name' => $agency['agency_name']],
                [
                    'wilaya' => $agency['wilaya'],
                    'bank_account_number' => null,
                ]
            );
            
            // Generate bank account number if it doesn't exist
            if (!$createdAgency->bank_account_number) {
                // Generate a unique bank account number: 24 digits
                // Format: 6 digits for agency ID + 18 random digits
                $accountNumber = str_pad($createdAgency->id, 6, '0', STR_PAD_LEFT) . str_pad(mt_rand(0, 999999999999), 18, '0', STR_PAD_LEFT);
                $createdAgency->update(['bank_account_number' => $accountNumber]);
            }
        }
        
        // Also update any existing agencies that don't have bank account numbers
        Agency::whereNull('bank_account_number')->each(function ($agency) {
            $accountNumber = str_pad($agency->id, 6, '0', STR_PAD_LEFT) . str_pad(mt_rand(0, 999999999999), 18, '0', STR_PAD_LEFT);
            $agency->update(['bank_account_number' => $accountNumber]);
        });
        
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

