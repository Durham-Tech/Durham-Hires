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
            'name' => 'Your name here',
            'user' => 'Your CIS account here',
            'email' => 'Your email here',
            'privileges' => '1',
            'site' => 0
            ]
        );
    }
}
