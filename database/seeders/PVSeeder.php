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

        // Realistic shop names list
        $shopNames = [
            'Sports Cafe', 'Home Restaurant', 'Star Cinema', 'Fashion Store', 'Oasis Hotel', 'Internet Cafe',
            'Eastern Restaurant', 'University Cafe', 'Supermarket', 'Electronics Store', 'Beauty Salon', 'Fast Food',
            'Science Bookstore', 'Game Store', 'Turkish Restaurant', 'European Cafe', 'Health Pharmacy', 'Clothing Store',
            'Italian Restaurant', 'Literary Cafe', 'Phone Store', 'Chinese Restaurant', 'Music Cafe', 'Bookstore',
            'Lebanese Restaurant', 'Art Cafe', 'Sports Store', 'Mediterranean Restaurant', 'Traditional Cafe', 'Gift Shop'
        ];

        foreach ($agents as $agent) {
            // Create 5-12 points of sale for each agent
            $pvCount = rand(5, 12);

            for ($pvNum = 1; $pvNum <= $pvCount; $pvNum++) {
                $paymentMethods = ['CASH', 'CHEQUE'];
                $statuses = ['OPEN', 'CLOSED', 'PENDING'];

                $shopName = $shopNames[array_rand($shopNames)] . ' - ' . $agent->user->name;
                $status = $statuses[array_rand($statuses)];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                $paymentStatus = $status === 'CLOSED' ? (rand(0, 1) ? 'VALIDATED' : 'PENDING') : 'PENDING';

                // Select random shop type from database
                $randomShopType = ShopType::inRandomOrder()->first();
                
                if (!$randomShopType) {
                    continue;
                }

                if (!$agent->user || !$agent->user->agency) {
                    continue;
                }

                $gestionnaire = User::where('agency_id', $agent->agency_id)
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'gestionnaire');
                    })
                    ->first();

                $mission = Mission::create([
                    'agency_id' => $agent->agency_id,
                    'gestionnaire_id' => $gestionnaire?->id ?? $agent->user_id,
                    'agent_id' => $agent->id,
                    'title' => 'Inspection #' . $pvNum . ' for ' . $agent->user->name,
                    'description' => 'Auto-generated mission for testing data',
                    'location_text' => $shopName . ' - ' . $randomShopType->name,
                    'map_link' => 'https://maps.google.com/?q=' . urlencode($agent->user->agency->wilaya),
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
