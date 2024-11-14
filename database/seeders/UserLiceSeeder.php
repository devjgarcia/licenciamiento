<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserLiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Juan Carlos',
            'rif' => 'V23573119',
            'direction' => 'Yaracuy, Venezuela',
            'phone' => '04126723869',
            'frkcanal' => NULL,
            'email' => 'develop.juanc@gmail.com',
            'password' => Hash::make('Ab123456'),
            'codigo' => NULL,
            'role_id' => NULL,
            'status' => 'Activo'
        ]);

        User::create([
            'name' => 'Roberto Hernandez',
            'rif' => 'V12345678',
            'direction' => 'Valencia, Venezuela',
            'phone' => '041212345678',
            'frkcanal' => NULL,
            'email' => 'harays2013@gmail.com',
            'password' => Hash::make('Ab123456'),
            'codigo' => NULL,
            'role_id' => NULL,
            'status' => 'Activo'
        ]);

        User::create([
            'name' => 'Ricardo Blesa',
            'rif' => 'V123456789',
            'direction' => 'Caracas, Venezuela',
            'phone' => '041212345678',
            'frkcanal' => NULL,
            'email' => 'rblesa88@gmail.com',
            'password' => Hash::make('Ab123456'),
            'codigo' => NULL,
            'role_id' => NULL,
            'status' => 'Activo'
        ]);

        User::create([
            'name' => 'José Kenayfati',
            'rif' => 'V12345',
            'direction' => 'Caracas, Venezuela',
            'phone' => '041212345678',
            'frkcanal' => NULL,
            'email' => 'josekenayfati@gmail.com',
            'password' => Hash::make('Ab123456'),
            'codigo' => NULL,
            'role_id' => NULL,
            'status' => 'Activo'
        ]);
    }
}
