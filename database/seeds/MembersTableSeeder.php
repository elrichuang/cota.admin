<?php

use App\Models\Member;
use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = factory(Member::class,100)->make();
        Member::insert($members->makeVisible(['password'])->toArray());
    }
}
