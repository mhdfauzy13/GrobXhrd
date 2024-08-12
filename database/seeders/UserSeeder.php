<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Superadmin',
            // 'role' => 'admin',
            'email' => 'Superadmin@gmail.com',
            'password' => bcrypt(12345678),]);

}
}