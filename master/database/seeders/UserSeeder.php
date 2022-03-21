<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
       User::insert([
           [
               'name'       => 'Admin',
               'email'      => 'admin@master.com',
               'password'   => bcrypt('123456789'),
           ],
           [
               'name'       => 'App ONE',
               'email'      => 'app@master.com',
               'password'   => bcrypt('123456789'),
           ],
       ]);
    }
}
