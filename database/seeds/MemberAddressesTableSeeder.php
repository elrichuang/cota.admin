<?php

use Illuminate\Database\Seeder;

class MemberAddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\MemberAddress::class,1000)->make();
        \App\Models\MemberAddress::insert($entities->toArray());
    }
}
