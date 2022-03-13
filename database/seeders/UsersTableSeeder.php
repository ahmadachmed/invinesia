<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User; 

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'daengweb',
            'username' => 'daeng',
            'email' => 'daeng@mail.com',
          //  'picture' => 'daeng.png',
            'password' => app('hash')->make('password'),
            'status' => '1',
            'role' => 0,
            'member' => 'no',
            'token' => Str::random(40),
        ]);
    }
}
