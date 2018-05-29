<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert(
            [
            'name' => 'Your name',
            'user' => 'Your CIS account',
            'email' => 'Your email',
            'privileges' => '1',
            'site' => '0'
            ]
        );

    }
}
