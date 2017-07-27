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
        DB::table('content')->insert(
            [
            'page' => 'home',
            'name' => 'Home Page',
            'content' => '<div align="center"><h1><font face="Raleway">Welcome to the best hires site around!</font></h1></div>',
            'site' => 1
            ]
        );
        DB::table('content')->insert(
            [
            'page' => 'tc',
            'name' => 'Terms and Conditions',
            'content' => '<h1>Ts and Cs fun and games!</h1>',
            'site' => 1
            ]
        );
        DB::table('settings')->insert(
            [
            'name' => 'hiresManager',
            'value' => '1',
            'site' => 1
            ]
        );
        DB::table('settings')->insert(
            [
            'name' => 'hiresEmail',
            'value' => 'test@example.com',
            'site' => 1
            ]
        );
        DB::table('admins')->insert(
            [
            'name' => 'Test Admin User',
            'user' => 'nwng84',
            'email' => 'jonathan.salmon@hotmail.co.uk',
            'privileges' => '5',
            'site' => 1
            ]
        );
        DB::table('sites')->insert(
            [
            'name' => 'Trevs',
            'slug' => 'trevs'
            ]
        );
    }
}
