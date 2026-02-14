<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Data; 
use App\Models\User;

class DataSeeder extends Seeder{
    public function run(): void{
        $user = User::first(); 
        if ($user) {
            Data::create([
                'user_id' => "3",
                'sensors_data' => [
                    'temp_main' => 31,
                    'temp_water' => 10,
                    'humidity_room' => 25,
                    'co2_level' => 400,
                    'light_sensor' => 120,
                    'pressure' => 20,
                    'wind_speed' => 45,
                    'soil_moisture' => 75,
                    'voltage' => 30,
                    'current' => 25
                ],
                'rele_data' => [
                    "rele1" => 0,
                    "rele2" => 1
                ]
            ]);
        }
    }
}

//{"rele1":0,"rele2":1}