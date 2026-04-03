<?php

namespace Database\Seeders;

use App\Models\ImageFabric;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageFabricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imageFabrics = [
            'Robot',
            'Love',
            'Kipas',
            'Kelinci',
            'Beruang',
            'Angry Birds',
            'Bulan Bintang',
            'Flamboyan',
            'Kenangan',
            'Bawang',
            'Cempaka',
            'Raflesia',
            'Lotus',
            'Adenium',
            'Rosela',
            'Seruni',
            'Anggrek',
            'Semanggi',
            'Teratai',
            'Nusa Indah',
            'Matahari',
            'Bougenvil',
            'Aster',
            'Jasmin',
            'Dahlia',
            'Melati',
            'Mawar',
            'Sepatu',
            'Kuncup',
            'Sakura',
            'Mahkota',
            'Kamboja',
            'Lavender',
        ];

        foreach ($imageFabrics as $imageFabric) {
            ImageFabric::create([
                'name'  => $imageFabric,
                'notes' => null
            ]);
        }
    }
}
