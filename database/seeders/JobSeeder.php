<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs=['Barbiere', 'Estetista', 'Parrucchiere', 'Tatuatore'];

        foreach ($jobs as $value) {
            $job=new Job();
            $job->name=$value;
            $job->save();
        }
    }
}
