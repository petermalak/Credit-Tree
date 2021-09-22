<?php

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
        DB::table('users')->insert([
            'name' => 'Michael Nagib',
            'email' => 'stars.tree@tree.com',
            'password' => bcrypt('m!chaelN@012'),
            'code' => \Illuminate\Support\Str::random(7),
            'is_admin' => true,
            'api_token' => \Illuminate\Support\Str::random(60)
        ]);
    }
}
