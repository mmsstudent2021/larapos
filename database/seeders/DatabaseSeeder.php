<?php

namespace Database\Seeders;

use App\Models\DailyVoucher;
use App\Models\Item;
use App\Models\Voucher;
use App\Models\VoucherList;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $items = \Illuminate\Support\Facades\Http::get("https://fakestoreapi.com/products")->object();
        foreach ($items as $item){
            Item::factory()->create([
                "title"=>$item->title,
                "price"=>$item->price,
                "image" => $item->image
            ]);
        }

        //        $item = Item::all()->random();


        $period = CarbonPeriod::create('2021-05-1', '2022-05-30');

        // Iterate over the period
        foreach ($period as $date) {
            $dailyVoucher = new DailyVoucher();
            $dailyVoucher->date = $date->format("Y-m-d");
            $dailyVoucher->save();
            $dailyVoucherTotal = 0;
            for($v=1;$v<rand(10,20);$v++){
                $voucher = new Voucher();
                $voucher->date = $date->format('Y-m-d');
                $voucher->customer_name = Str::random(10);
                $voucher->voucher_number = uniqid();
                $voucher->save();

                $totalCost = 0;

                for($i=1;$i<rand(10,50);$i++){
                    $item = Item::where("id",rand(1,20))->first();
                    $quantity = rand(1,15);
                    $cost = $item->price * $quantity;
                    $totalCost += $cost;
                    VoucherList::factory()->create([
                        "item_id"=> $item->id,
                        "quantity"=> $quantity,
                        "unit_price"=>$item->price,
                        "cost" => $cost,
                        "date" => $date->format("Y-m-d"),
                        "voucher_id" => $voucher->id
                    ]);
                }
                $voucher->update([
                   "total" => $totalCost
                ]);
                $dailyVoucherTotal += $totalCost;
            }
            $dailyVoucher->update(["total"=>$dailyVoucherTotal]);
        }





        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
