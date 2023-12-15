<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Commune;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
             'name' => 'Admin',
             'email' => 'admin@gmail.com',
             'password' => '123456'
        ]);

        $communes = [
            ['nom'=>'Bandalungwa'],
            ['nom' => 'Barumbu'],
            ['nom' => 'Bumbu'],
            ['nom' => 'Gombe'],
            ['nom' => 'Kalamu'],
            ['nom' => 'Kasa-Vubu'],
            ['nom' => 'Kimbanseke'],
            ['nom' => 'Kinshasa'],
            ['nom' => 'Kintambo'],
            ['nom' => 'Kisenso'],
            ['nom' => 'Lemba'],
            ['nom' => 'Limete'],
            ['nom' => 'Lingwala'],
            ['nom' => 'Makala'],
            ['nom' => 'Maluku'],
            ['nom' => 'Masina'],
            ['nom' => 'Matete'],
            ['nom' => 'Mont-Ngafula'],
            ['nom' => 'Ndjili'],
            ['nom' => 'Ngaba'],
            ['nom' => 'Ngaliema'],
            ['nom' => 'Ngiri-Ngiri'],
            ['nom' => 'Nsele'],
            ['nom' => 'Selembao']
        ];
        $record = Commune::insert($communes);
         $role = Role::create(['name' => 'Gerant']);
        $user = User::find(1)->first();
        $user->assignRole($role);
        
    }
}
