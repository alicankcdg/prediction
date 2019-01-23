<?php
/**
 * Created by PhpStorm.
 * User: roketworks
 * Date: 22.01.2019
 * Time: 15:35
 */

namespace App\Services;


use App\Account;
use App\Lkp;
use App\Stock;
use App\User;
use App\UserStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Bu classdaki methodlarda yapılan elequent işlemleri ilgili repositorylerde yapılmalı
// hızlı olması açısından tek servis üzerinden ilerledim.
// katman yapısı
// controller
// servis
// repository
// model

class StockService
{

    public function getUserStocks(){
        return UserStock::where("user_id",Auth::user()->getAuthIdentifier())->with(["stocks"])
            ->select("id","stock_id", DB::raw('count(1) as count'))
            ->groupBy("stock_id")
            ->get();

    }



    public function getBestRatedStocks () {
        $stocks = Stock::with(['values' => function($query) {
            $query->orderBy("time","desc");
        }])->get();
        foreach ($stocks as $stock) {

            $minDate  = Carbon::createFromTimestamp($stock->values->min("time"));
            $maxDate  = Carbon::createFromTimestamp($stock->values->max("time"));
            $dateDiff = $maxDate->diffInDays($minDate);
            $evaluatedArea = $minDate->addDays($dateDiff/2)->timestamp;
            $stockMin = $stock->values->where("time",">=",$evaluatedArea)->min("value");
            $stockMin = $stockMin == 0 ? 0.1 : $stockMin;
            $stockMax = $stock->values->where("time",">=",$evaluatedArea)->max("value");
            $diff = $stockMax-$stockMin;
            $rate = ($diff*100)/$stockMin;
            $stock->rate = $rate;
            $stock->saveOrFail();


        }
        return Stock::orderBy("rate","desc")->take(5)->get();
    }


    public function buyStock($id){
       $stock = $this->getStockWithValue($id);

        $stockValue = $stock->values->first()->value;
        $acc = Auth::user()->account;
        $balace = $acc->balance;

        $userStock = Auth::user()->userStocks;

        if ($stockValue > $balace) {
            throw new \Exception("Insufficient balance");
        }

        $lkp = Lkp::where("key","DEF_STOCK_VALUE")->first();

        if (count($userStock) >= $lkp->value ) {
            throw new \Exception("You must sell before the buy");

        }
        $userStock = new UserStock();
        $userStock->user_id = Auth::user()->getAuthIdentifier();
        $userStock->stock_id = $stock->id;
        $userStock->saveOrFail();

        $acc->balance = $balace-$stockValue;;
        $acc->save();

       return "OK";
    }

     public function sellStock($id){
       $stock = $this->getStockWithValue($id);

        $stockValue = $stock->values->first()->value;
        $acc = Auth::user()->account;
        $balace = $acc->balance;



        $userStock = UserStock::find($id);
        $userStock->delete();
        $acc->balance = $balace+$stockValue;;
        $acc->save();

        return "OK";
    }

    public function getStockWithValue($id){
        $stock = Stock::where("id",$id)->with(["values" => function ($query) {
            $query->orderBy("id","desc");
            $query->first();
        }])->first();

        return $stock;
    }

}
