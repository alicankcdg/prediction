<?php

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
        return factory(\App\User::class,1)->create();
        // $this->call(UsersTableSeeder::class);
    }
}
