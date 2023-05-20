<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'aldi',
            'email' => 'aldistate@yahoo.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        Product::create([
            'name' => 'Keyboard Fantech 1',
            'price' => 30000,
            'description' => 'Ini Keyboard 1',
            'stock' => '5',
        ]);

        Product::create([
            'name' => 'Keyboard Fantech 2',
            'price' => 50000,
            'description' => 'Ini Keyboard 2',
            'stock' => '10',
        ]);
    }
}
