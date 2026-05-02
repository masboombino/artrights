<?php

namespace Database\Seeders;

use App\Models\AgencyWallet;
use App\Models\AgencyWalletTransaction;
use App\Models\Agent;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Mission;
use App\Models\PV;
use App\Models\PVArtwork;
use App\Models\ShopType;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Seeder;

class PVSeeder extends Seeder
{
    public function run(): void
    {
        $baseRate = config('artrights.base_rate', 200);
        $agents = Agent::with('user', 'user.agency')->get();

        if ($agents->isEmpty()) {
            return;
        }

        $shopTypes = ShopType::all();
        if ($shopTypes->isEmpty()) {
            return;
        }

        $deviceTypes = DeviceType::all();
        if ($deviceTypes->isEmpty()) {
            return;
        }

        // Realistic Algerian business names grouped by shop type for believable PVs.
        $shopNamesByType = [
            'Restaurant'      => ['Restaurant La Casbah', 'Restaurant Dar Diaf', 'Restaurant El Djenina', 'Restaurant El Boustane', 'Restaurant Le Tantonville', 'Restaurant Tipaza Plage'],
            'Coffee Shop'     => ['Café Malakoff', 'Café El Djazair', 'Café Le Tantonville', 'Café Les Palmiers', 'Café El Houria', 'Café La Perle'],
            'Fast Food'       => ['Pizza Cosmos', 'Tacos El Harrach', 'Fast Food Sahara', 'Burger Alger', 'Snack Kheir'],
            'Bakery'          => ['Pâtisserie Les Délices d\'Alger', 'Boulangerie El Baraka', 'Pâtisserie Ketchaoua', 'Boulangerie La Fleur'],
            'Ice Cream Shop'  => ['Glacier Riviera Alger', 'Glacier El Wiam', 'Glacier Les Sablettes'],
            'Bar'             => ['Bar Le Milk Bar', 'Bar de la Poste'],
            'Cafeteria'       => ['Cafétéria Université Alger', 'Cafétéria ENP', 'Cafétéria El Kettani'],
            'Cinema'          => ['Cinéma Cosmos', 'Cinéma Ibn Khaldoun', 'Cinéma Afrique', 'Cinéma Algeria', 'Cinéma Mouggar'],
            'Theater'         => ['Théâtre National Mahieddine Bachtarzi', 'Théâtre Régional Kateb Yacine', 'Théâtre de Verdure Laadi Flici'],
            'Concert Hall'    => ['Salle El Mouggar', 'Salle Ibn Zeydoun', 'Palais de la Culture Moufdi Zakaria', 'Coupole d\'Alger'],
            'Nightclub'       => ['Club Le Sofitel', 'Club El Djazair Riviera'],
            'Karaoke Bar'     => ['Karaoké Le Riad', 'Karaoké El Nour'],
            'Amusement Park'  => ['Parc des Grands Vents', 'Parc de Loisirs Kherrouba'],
            'Retail Store'    => ['Galeries Algériennes', 'Magasin El Nasr', 'Boutique Dar Diaf'],
            'Supermarket'     => ['Supermarché Ardis', 'Supermarché UNO City', 'Supermarché Numidis', 'Supermarché El Hayat'],
            'Pharmacy'        => ['Pharmacie El Shifa', 'Pharmacie Centrale Didouche Mourad', 'Pharmacie El Baraka'],
            'Bookstore'       => ['Librairie du Tiers-Monde', 'Librairie Mauguin', 'Librairie El Ijtihad'],
            'Clothing Store'  => ['Boutique Dar El Hanine', 'Boutique El Anissa', 'Boutique Prestige Alger'],
            'Electronics Store' => ['Condor Store Alger-Centre', 'Stream System Bab Ezzouar', 'Starlight Électronique'],
            'Jewelry Store'   => ['Bijouterie El Dzair', 'Bijouterie Dar El Dhahab', 'Bijouterie El Andalous'],
            'Sporting Goods'  => ['Sportland Alger', 'Décathlon Dar El Beida', 'Sport Pro Hussein Dey'],
            'Hotel'           => ['Hôtel El Djazair (ex-Saint-George)', 'Hôtel El Aurassi', 'Hôtel Sofitel Alger', 'Hôtel Albert 1er', 'Hôtel Sheraton Club des Pins'],
            'Internet Cafe'   => ['Cyber Café El Harrach', 'Cyber Espace Bab Ezzouar', 'Cyber Centre El Biar'],
            'Game Center'     => ['Game Zone Bab Ezzouar', 'Arcade El Djazair', 'Play Station Lounge Alger'],
            'Beauty Salon'    => ['Salon de Coiffure Sabrina', 'Institut de Beauté Yasmine', 'Salon El Anissa'],
            'Gym'             => ['Salle de Sport Atlas Fitness', 'Fitness Club El Mouradia', 'Gym Pro Alger'],
            'Laundry'         => ['Pressing Blanche-Neige', 'Laverie El Djenina'],
            'Car Wash'        => ['Station de Lavage El Harrach', 'Auto Wash Bab El Oued'],
            'Pet Store'       => ['Animalerie El Hayat', 'Pet Shop Alger'],
            'Office'          => ['Cabinet d\'Architecture El Djazair', 'Bureau Notarial Didouche Mourad'],
            'School'          => ['École Ibn Sina', 'Lycée Émir Abdelkader', 'École El Khawarizmi'],
            'Hospital'        => ['Clinique Les Orangers', 'Clinique El Azhar'],
            'Bank'            => ['Agence BEA Didouche Mourad', 'Agence BNA Bab Ezzouar', 'Agence CPA Alger-Centre'],
            'Post Office'     => ['Bureau de Poste Grande Poste d\'Alger', 'Bureau de Poste El Harrach'],
            'Gas Station'     => ['Station Naftal Bab Ezzouar', 'Station Sonatrach Alger-Centre'],
            'Auto Repair'     => ['Garage El Djazair', 'Mécanique Bab El Oued'],
        ];

        $genericShopNames = [
            'Café El Houria', 'Restaurant El Djenina', 'Librairie Mauguin', 'Supermarché Numidis',
            'Hôtel El Aurassi', 'Cyber Centre El Biar', 'Pharmacie El Shifa', 'Boutique Dar El Hanine',
            'Cinéma Mouggar', 'Salle Ibn Zeydoun',
        ];

        foreach ($agents as $agent) {
            // Create 5-12 points of sale for each agent
            $pvCount = rand(5, 12);

            for ($pvNum = 1; $pvNum <= $pvCount; $pvNum++) {
                $paymentMethods = ['CASH', 'CHEQUE'];
                $statuses = ['OPEN', 'CLOSED', 'PENDING'];

                $randomShopType = ShopType::inRandomOrder()->first();

                if (!$randomShopType) {
                    continue;
                }

                if (!$agent->user || !$agent->user->agency) {
                    continue;
                }

                $pool = $shopNamesByType[$randomShopType->name] ?? $genericShopNames;
                $shopName = $pool[array_rand($pool)];
                $status = $statuses[array_rand($statuses)];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                $paymentStatus = $status === 'CLOSED' ? (rand(0, 1) ? 'VALIDATED' : 'PENDING') : 'PENDING';

                $gestionnaire = User::where('agency_id', $agent->agency_id)
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'gestionnaire');
                    })
                    ->first();

                $wilaya = $agent->user->agency->wilaya;
                $locationText = $shopName . ', ' . $wilaya;

                $mission = Mission::create([
                    'agency_id' => $agent->agency_id,
                    'gestionnaire_id' => $gestionnaire?->id ?? $agent->user_id,
                    'agent_id' => $agent->id,
                    'title' => 'Contrôle des droits d\'auteur - ' . $shopName,
                    'description' => 'Mission de contrôle de la diffusion d\'œuvres artistiques au sein de l\'établissement ' . $shopName . ' (' . $randomShopType->name . '), wilaya de ' . $wilaya . '.',
                    'location_text' => $locationText,
                    'map_link' => 'https://maps.google.com/?q=' . urlencode($locationText),
                    'scheduled_at' => now()->addDays($pvNum),
                    'status' => $status === 'OPEN' ? 'IN_PROGRESS' : 'DONE',
                ]);

                $pv = PV::create([
                    'agent_id' => $agent->id,
                    'agency_id' => $agent->agency_id,
                    'mission_id' => $mission->id,
                    'shop_name' => $shopName,
                    'shop_type' => $randomShopType->name,
                    'date_of_inspection' => now()->subDays(rand(1, 60)),
                    'status' => $status,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'base_rate' => $baseRate,
                    'total_amount' => 0,
                    'closed_at' => $status === 'CLOSED' ? now()->subDays(rand(1, 30)) : null,
                ]);

                // Create 2-6 devices for each point of sale
                $selectedDeviceTypes = DeviceType::inRandomOrder()->take(rand(2, 6))->get();
                
                if ($selectedDeviceTypes->isEmpty()) {
                    continue;
                }
                
                $deviceRecords = [];
                $deviceTotals = [];

                foreach ($selectedDeviceTypes as $deviceType) {
                    $quantity = rand(1, 5);
                    $device = Device::create([
                        'pv_id' => $pv->id,
                        'device_type_id' => $deviceType->id,
                        'name' => $deviceType->name . ' - Model ' . rand(100, 999),
                        'type' => $deviceType->type,
                        'coefficient' => $deviceType->coefficient,
                        'quantity' => $quantity,
                        'amount' => 0,
                    ]);

                    $deviceRecords[] = $device;
                    $deviceTotals[$device->id] = 0;
                }

                // Get artworks from same province or neighboring provinces
                $agencyArtists = Artist::where('agency_id', $agent->agency_id)->pluck('id');
                $artworks = Artwork::where('status', 'APPROVED')
                    ->where('platform_tax_status', 'PAID')
                    ->whereIn('artist_id', $agencyArtists)
                    ->inRandomOrder()
                    ->take(rand(3, 8))
                    ->get();

                // If not enough artworks, add from other provinces
                if ($artworks->count() < 3) {
                    $additionalArtworks = Artwork::where('status', 'APPROVED')
                        ->where('platform_tax_status', 'PAID')
                        ->whereNotIn('artist_id', $agencyArtists)
                        ->inRandomOrder()
                        ->take(3 - $artworks->count())
                        ->get();
                    $artworks = $artworks->merge($additionalArtworks);
                }

                $artistTotals = [];

                foreach ($artworks as $artwork) {
                    if (empty($deviceRecords)) {
                        break;
                    }

                    $device = $deviceRecords[array_rand($deviceRecords)];
                    $hours = round(rand(1, 24) + (rand(0, 9) / 10), 1); // More realistic hours
                    $plays = rand(1, 20); // More plays count

                    if (!$artwork->category) {
                        continue;
                    }

                    $fine = round($artwork->category->coefficient * $device->coefficient * $hours * $plays * $baseRate, 2);

                    PVArtwork::create([
                        'pv_id' => $pv->id,
                        'artwork_id' => $artwork->id,
                        'device_id' => $device->id,
                        'hours_used' => $hours,
                        'plays_count' => $plays,
                        'base_rate' => $baseRate,
                        'fine_amount' => $fine,
                    ]);

                    $deviceTotals[$device->id] = ($deviceTotals[$device->id] ?? 0) + $fine;

                    if ($artwork->artist) {
                        $artistTotals[$artwork->artist->id] = ($artistTotals[$artwork->artist->id] ?? 0) + $fine;
                    }
                }

                foreach ($deviceTotals as $deviceId => $amount) {
                    $device = Device::find($deviceId);
                    if ($device) {
                        $device->amount = $amount;
                        $device->save();
                    }
                }

                $totalAmount = array_sum($artistTotals);
                $pv->total_amount = $totalAmount;
                $pv->cash_received_amount = $paymentStatus === 'VALIDATED' ? $totalAmount : 0;
                $pv->save();

                $wallet = AgencyWallet::where('agency_id', $agent->agency_id)->first();
                if (!$wallet) {
                    $wallet = AgencyWallet::create([
                        'agency_id' => $agent->agency_id,
                        'balance' => 0
                    ]);
                }

                if ($paymentStatus === 'VALIDATED') {
                    $wallet->balance += $totalAmount;
                    $wallet->last_transaction = now();
                    $wallet->save();

                    AgencyWalletTransaction::create([
                        'agency_wallet_id' => $wallet->id,
                        'pv_id' => $pv->id,
                        'direction' => 'IN',
                        'amount' => $totalAmount,
                        'description' => 'Funds collected from PV #' . $pv->id,
                    ]);

                    foreach ($artistTotals as $artistId => $amount) {
                        Transaction::create([
                            'pv_id' => $pv->id,
                            'artist_id' => $artistId,
                            'amount' => $amount,
                            'payment_method' => $paymentMethod,
                            'payment_status' => 'VALIDATED',
                        ]);

                        $artistWallet = Wallet::where('artist_id', $artistId)->first();
                        if (!$artistWallet) {
                            $artistWallet = Wallet::create([
                                'artist_id' => $artistId,
                                'balance' => 0,
                                'last_transaction' => null
                            ]);
                        }
                        $artistWallet->balance = $artistWallet->balance + $amount;
                        $artistWallet->last_transaction = now();
                        $artistWallet->save();

                        $wallet->balance -= $amount;
                        $wallet->save();

                        AgencyWalletTransaction::create([
                            'agency_wallet_id' => $wallet->id,
                            'pv_id' => $pv->id,
                            'direction' => 'OUT',
                            'amount' => $amount,
                            'description' => 'Released to artist #' . $artistId,
                        ]);
                    }

                    $pv->funds_released_at = now();
                    $pv->save();
                }
            }
        }
    }
}
