<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'super_admin' => [
                'manage admins',
                'manage gestionnaires',
                'manage categories',
                'manage agencies',
                'manage pvs',
                'view dashboard',
                'view notifications',
            ],
            'admin' => [
                'approve artists',
                'reject artists',
                'manage users',
                'manage complaints',
                'manage gestionnaires (category)',
                'view dashboard',
                'view notifications',
            ],
            'gestionnaire' => [
                'approve artworks',
                'reject artworks',
                'manage artworks',
                'send agents',
                'manage pvs',
                'manage agencies',
                'manage revenues',
                'view dashboard',
                'view notifications',
            ],
            'agent' => [
                'create pv',
                'view pvs',
                'close pv',
                'view law',
                'manage payments',
                'view dashboard',
                'view notifications',
            ],
            'artist' => [
                'view profile',
                'edit profile',
                'create artwork',
                'edit artwork',
                'delete artwork',
                'view wallet',
                'view related pvs',
                'submit complaint',
                'view dashboard',
                'view notifications',
            ],
            'user' => [],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }
    }
}
