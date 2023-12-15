<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Commune;
use App\Models\Permission;
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

         App\Models\User::create([
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
        // Les roles
        $role = Role::create(['name' => 'Gerant']);
        $role2 = Role::create(['name' => 'Boss']);
        $role3 = Role::create(['name' => 'Caissier']);
        $user = User::find(1)->first();
        $user->assignRole($role);
        
        // Les permissions
        $permisision1 = Permission::create(['name' => 'create Galerie']);
        $permisision2 = Permission::create(['name' => 'create Occupation']);
        $permisision3 = Permission::create(['name' => 'create Type Occus']);
        $permisision4 = Permission::create(['name' => 'edit Galerie']);
        $permisision5 = Permission::create(['name' => 'edit Type Occus']);
        $permisision6 = Permission::create(['name' => 'edit Occupation']);
        $permisision7 = Permission::create(['name' => 'delete Galerie']);
        $permisision8 = Permission::create(['name' => 'delete Occupation']);
        $permisision9 = Permission::create(['name' => 'delete Galerie']);

        $permissions = [$permisision1,$permisision2,$permisision3,$permisision4,$permisision5,$permisision6,$permisision7,$permisision8,$permisision9];
        foreach($permissions as $permission)
        {
            $user->givePermissionTo($permission);
        }
    }
}
