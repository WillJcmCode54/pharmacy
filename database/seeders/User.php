<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsUser::create([
            'name' => 'Admin' ,
            'last_name' => 'admin' ,
            'number_id' => 'V0000000' ,
            'img' => '/storage/img/medicine.png' ,
            'phone' => '+000000000' ,
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
