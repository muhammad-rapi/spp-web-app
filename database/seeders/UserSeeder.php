<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "name" => "Muhammad Rafi",
                "email" => "muhammadrafi@spp.com",
                "password" => Hash::make('password'),
                "role" => User::HEADMASTER,
                "gender" => User::PRIA_GENDER,
                "is_active" => true
            ],
            [
                "name" => "Nasywa Artanti",
                "email" => "nasywaartanti@spp.com",
                "password" => Hash::make('password'),
                "role" => User::OPERATOR,
                "gender" => User::WANITA_GENDER,
                "is_active" => true
            ],
            [
                "name" => "Petugas TU",
                "email" => "petugastu@spp.com",
                "password" => Hash::make('password'),
                "role" => User::PETUGAS_TU,
                "gender" => User::WANITA_GENDER,
                "is_active" => true
            ],
            [
                "name" => "Siswa",
                "email" => "siswa@spp.com",
                "password" => Hash::make('password'),
                "role" => User::SISWA,
                "gender" => User::PRIA_GENDER,
                "is_active" => true
            ],
        ];

        foreach ($data as $d) {
            \App\Models\User::create($d);
        }
    }
}
