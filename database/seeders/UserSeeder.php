<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Davina Leong',
            'email' => 'leong.shi.yun@gmail.com',
            'password' => '$2y$12$xYODh3sp2fdbOmyzPB6FTum0cVabmzoajJFg6WRTDkeqA/1ceN1lq'
        ]);
    }
}
