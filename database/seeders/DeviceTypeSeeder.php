<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            ['name' => 'Cinema Screen', 'type' => 'Public', 'coefficient' => 10],
            ['name' => 'Theater Speaker System', 'type' => 'Public', 'coefficient' => 8],
            ['name' => 'Stage LED Wall', 'type' => 'Public', 'coefficient' => 7],
            ['name' => 'Stadium Speakers', 'type' => 'Public', 'coefficient' => 9],
            ['name' => 'TV in Cafe/Restaurant', 'type' => 'Commercial', 'coefficient' => 4],
            ['name' => 'Store Display Screen', 'type' => 'Commercial', 'coefficient' => 3.5],
            ['name' => 'Hotel Lobby Screen', 'type' => 'Commercial', 'coefficient' => 3],
            ['name' => 'Radio System', 'type' => 'Commercial', 'coefficient' => 4.5],
            ['name' => 'Laptop', 'type' => 'Personal', 'coefficient' => 1],
            ['name' => 'Mobile Phone', 'type' => 'Personal', 'coefficient' => 1],
            ['name' => 'Tablet', 'type' => 'Personal', 'coefficient' => 1.2],
            ['name' => 'Home Smart TV', 'type' => 'Personal', 'coefficient' => 2],
            ['name' => 'Bluetooth Speaker', 'type' => 'Personal', 'coefficient' => 1.5],
            ['name' => 'Projector System', 'type' => 'Public', 'coefficient' => 8.5],
            ['name' => 'Concert Sound System', 'type' => 'Public', 'coefficient' => 9.5],
            ['name' => 'Digital Billboard', 'type' => 'Commercial', 'coefficient' => 5],
            ['name' => 'Restaurant Audio System', 'type' => 'Commercial', 'coefficient' => 4.2],
            ['name' => 'Shopping Mall Display', 'type' => 'Commercial', 'coefficient' => 3.8],
            ['name' => 'Desktop Computer', 'type' => 'Personal', 'coefficient' => 1.1],
            ['name' => 'Portable Speaker', 'type' => 'Personal', 'coefficient' => 1.3],
        ];

        foreach ($devices as $device) {
            DeviceType::firstOrCreate(
                ['name' => $device['name']],
                [
                    'type' => $device['type'],
                    'coefficient' => $device['coefficient'],
                ]
            );
        }
    }
}

