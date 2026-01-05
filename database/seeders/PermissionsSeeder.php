<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view dashboard',
            'view notifications',
            'manage admins',
            'manage gestionnaires',
            'manage categories',
            'approve artists',
            'reject artists',
            'manage users',
            'manage complaints',
            'manage gestionnaires (category)',
            'approve artworks',
            'reject artworks',
            'manage artworks',
            'send agents',
            'manage pvs',
            'manage agencies',
            'manage revenues',
            'create pv',
            'view pvs',
            'close pv',
            'view law',
            'manage payments',
            'view profile',
            'edit profile',
            'create artwork',
            'edit artwork',
            'delete artwork',
            'view wallet',
            'view related pvs',
            'submit complaint',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
