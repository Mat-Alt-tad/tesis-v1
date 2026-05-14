<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------------------
        // Admin
        // ----------------------------------------------------------------
        $admin = User::create([
            'name'              => 'Admin Principal',
            'email'             => 'admin@etnobotanica.co',
            'password'          => Hash::make('admin2026UDEC'), // ⚠️ cambiar en producción
            'email_verified_at' => now(),
            'activo'            => true,
        ]);
        $admin->assignRole('admin');

        // ----------------------------------------------------------------
        // Moderador de ejemplo
        // ----------------------------------------------------------------
        $moderador = User::create([
            'name'              => 'Moderador Ejemplo',
            'email'             => 'moderador@etnobotanica.co',
            'password'          => Hash::make('moderador2026udec'),
            'email_verified_at' => now(),
            'activo'            => true,
        ]);
        $moderador->assignRole('moderador');

        // ----------------------------------------------------------------
        // Lector de ejemplo
        // ----------------------------------------------------------------
        $lector = User::create([
            'name'              => 'Lector Ejemplo',
            'email'             => 'lector@etnobotanica.co',
            'password'          => Hash::make('lectorbotanicafusa'),
            'email_verified_at' => now(),
            'activo'            => true,
        ]);
        $lector->assignRole('lector');
    }
}
