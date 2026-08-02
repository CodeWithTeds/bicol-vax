<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── 1. Super Admin (only account seeded) ─────────────────────
        User::updateOrCreate(
            ['email' => 'superadmin@bicolvax.com'],
            [
                'name'           => 'BicolVax Super Admin',
                'password'       => Hash::make('SuperAdmin_123'),
                'is_admin'       => true,
                'is_super_admin' => true,
                'branch_id'      => null,
            ]
        );

        // ── 2. Eight Branches (no admins pre-assigned) ────────────────
        // Super admin creates branch admins manually via /superadmin/admins
        $branchData = [
            ['name' => 'Naga City Branch',    'location' => 'Naga City, Camarines Sur',   'address' => 'Brgy. Triangulo, Naga City',     'contact' => '054-123-0001', 'email' => 'naga@bicolvax.com'],
            ['name' => 'Legazpi City Branch', 'location' => 'Legazpi City, Albay',        'address' => 'Brgy. Cabangan, Legazpi City',   'contact' => '052-123-0002', 'email' => 'legazpi@bicolvax.com'],
            ['name' => 'Iriga City Branch',   'location' => 'Iriga City, Camarines Sur',  'address' => 'Brgy. San Nicolas, Iriga City',  'contact' => '054-123-0003', 'email' => 'iriga@bicolvax.com'],
            ['name' => 'Sorsogon Branch',     'location' => 'Sorsogon City, Sorsogon',    'address' => 'Brgy. Centro, Sorsogon City',    'contact' => '056-123-0004', 'email' => 'sorsogon@bicolvax.com'],
            ['name' => 'Daet Branch',         'location' => 'Daet, Camarines Norte',      'address' => 'Brgy. Magsaysay, Daet',          'contact' => '054-123-0005', 'email' => 'daet@bicolvax.com'],
            ['name' => 'Tabaco City Branch',  'location' => 'Tabaco City, Albay',         'address' => 'Brgy. Tayhi, Tabaco City',       'contact' => '052-123-0006', 'email' => 'tabaco@bicolvax.com'],
            ['name' => 'Daraga Branch',       'location' => 'Daraga, Albay',              'address' => 'Brgy. Bigaa, Daraga',            'contact' => '052-123-0007', 'email' => 'daraga@bicolvax.com'],
            ['name' => 'Masbate Branch',      'location' => 'Masbate City, Masbate',      'address' => 'Brgy. Nursery, Masbate City',    'contact' => '056-123-0008', 'email' => 'masbate@bicolvax.com'],
        ];

        foreach ($branchData as $data) {
            Branch::updateOrCreate(['name' => $data['name']], array_merge($data, ['is_active' => true]));
        }
    }
}
