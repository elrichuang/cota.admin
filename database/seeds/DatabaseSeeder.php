<?php

use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();
        $this->call(UsersTableSeeder::class);
        $this->call(MembersTableSeeder::class);
        $this->call(AbilitiesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ArticleCategoriesTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(MerchantsTableSeeder::class);
        $this->call(StoresTableSeeder::class);
        $this->call(SlicesTableSeeder::class);
        $this->call(SliceItemsTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(SpuCategoriesTableSeeder::class);
        $this->call(SpusTableSeeder::class);
        $this->call(SkusTableSeeder::class);
        $this->call(CartsTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(OrderStatusesTableSeeder::class);
        $this->call(OrderSkusTableSeeder::class);
        $this->call(OrderSkuStatusesTableSeeder::class);
        $this->call(ExpressesTableSeeder::class);
        $this->call(MerchantOrdersTableSeeder::class);
        $this->call(MemberAddressesTableSeeder::class);
        $this->call(RefundOrdersTableSeeder::class);
        $this->call(RefundOrderStatusesTableSeeder::class);
        Model::reguard();
    }
}
