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

        \App\Models\User::create([
             'name' => 'Admin',
             'email' => 'admin@loyer.com',
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
        Commune::insert($communes);
        // Les roles
        $role = Role::create(['name' => 'Gerant']);
        $role2 = Role::create(['name' => 'Boss']);
        $role3 = Role::create(['name' => 'Caissier']);
        $user = User::find(1)->first();
        $user->assignRole($role);

        // Les permissions
        $permisision2 = Permission::create(['name' => 'create Occupation']);
        $permisision6 = Permission::create(['name' => 'edit Occupation']);
        $permisision8 = Permission::create(['name' => 'delete Occupation']);

        $permisision3 = Permission::create(['name' => 'create Type Occus']);
        $permisision5 = Permission::create(['name' => 'edit Type Occus']);
        $permisision9 = Permission::create(['name' => 'delete Type Occus']);

        $permisision1 = Permission::create(['name' => 'create Galerie']);
        $permisision4 = Permission::create(['name' => 'edit Galerie']);
        $permisision7 = Permission::create(['name' => 'delete Galerie']);


        $permisision10 = Permission::create(['name' => 'create Locataire']);
        $permisision12 = Permission::create(['name' => 'edit Locataire']);
        $permisision14 = Permission::create(['name' => 'delete Locataire']);

        $permisision11 = Permission::create(['name' => 'create Loyer']);
        $permisision13 = Permission::create(['name' => 'edit Loyer']);
        $permisision15 = Permission::create(['name' => 'delete Loyer']);


        $permissions = [$permisision1,$permisision2,$permisision3,$permisision4,$permisision5,$permisision6,$permisision7,$permisision8,$permisision9,$permisision10,$permisision11,$permisision12,$permisision13,$permisision14,$permisision15];
        foreach($permissions as $permission)
        {
            $user->givePermissionTo($permission);
        }
    }
}
