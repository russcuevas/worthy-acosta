<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds for Mariveles 18 Barangays.
     */
    public function run(): void
    {
        $barangays = [
            1  => ['name' => 'Alion',          'slug' => 'alion',          'pin_x' => 71.00, 'pin_y' => 33.20],
            2  => ['name' => 'Batangas II',    'slug' => 'batangas-ii',    'pin_x' => 88.80, 'pin_y' => 33.60],
            3  => ['name' => 'Cabcaben',       'slug' => 'cabcaben',       'pin_x' => 63.30, 'pin_y' => 41.80],
            4  => ['name' => 'Lucanin',        'slug' => 'lucanin',        'pin_x' => 78.00, 'pin_y' => 42.80],
            5  => ['name' => 'Balon-Anito',    'slug' => 'balon-anito',    'pin_x' => 34.80, 'pin_y' => 40.50],
            6  => ['name' => 'Maligaya',       'slug' => 'maligaya',       'pin_x' => 52.60, 'pin_y' => 42.20],
            7  => ['name' => 'Biaan',          'slug' => 'biaan',          'pin_x' => 22.50, 'pin_y' => 47.20],
            8  => ['name' => 'Malaya',         'slug' => 'malaya',         'pin_x' => 44.50, 'pin_y' => 44.20],
            9  => ['name' => 'Townsite',       'slug' => 'townsite',       'pin_x' => 79.60, 'pin_y' => 50.10],
            10 => ['name' => 'San Isidro',     'slug' => 'san-isidro',     'pin_x' => 38.50, 'pin_y' => 51.10],
            11 => ['name' => 'Mt. View',       'slug' => 'mt-view',        'pin_x' => 77.70, 'pin_y' => 59.20],
            12 => ['name' => 'Alas-Asin',      'slug' => 'alas-asin',      'pin_x' => 68.20, 'pin_y' => 62.20],
            13 => ['name' => 'Camaya',         'slug' => 'camaya',         'pin_x' => 43.60, 'pin_y' => 61.80],
            14 => ['name' => 'Baseco Country', 'slug' => 'baseco-country', 'pin_x' => 58.90, 'pin_y' => 62.90],
            15 => ['name' => 'San Carlos',     'slug' => 'san-carlos',     'pin_x' => 51.70, 'pin_y' => 63.00],
            16 => ['name' => 'Poblacion',      'slug' => 'poblacion',      'pin_x' => 36.00, 'pin_y' => 67.90],
            17 => ['name' => 'Sisiman',        'slug' => 'sisiman',        'pin_x' => 58.10, 'pin_y' => 69.50],
            18 => ['name' => 'Ipag',           'slug' => 'ipag',           'pin_x' => 40.50, 'pin_y' => 74.70],
        ];

        foreach ($barangays as $id => $data) {
            Barangay::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'pin_x' => $data['pin_x'],
                    'pin_y' => $data['pin_y'],
                    'is_active' => true,
                ]
            );
        }
    }
}
