<?php

namespace App\Http\Controllers;

use App\Services\PortalService;
use App\Services\StockService;
use App\Stock;
use App\UserStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $stockService, $portalService;

    public function __construct(StockService $stockService, PortalService $portalService)
    {
        $this->middleware('auth');
        $this->stockService = $stockService;
        $this->portalService = $portalService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userStocks = $this->stockService->getUserStocks();

        if (count($userStocks) <= 0) {

            return redirect("/stock/buy/");
        }
        return view("user_stock", ["stocks" => $userStocks]);
    }

    public function saveValues()
    {
        try {
            $this->portalService->insertAllValues();
            return redirect("/home");
        } catch (\Exception $exception) {
            return redirect("/home")->with("message", $exception->getMessage());
        }
    }

    public function stocks()
    {

        $stocks = $this->stockService->getBestRatedStocks();
        return view('stock', ["stocks" => $stocks]);

    }

    public function buyStock(Request $request, $id)
    {
        try {
            $this->stockService->buyStock($id);
            return redirect("/home")->with("message", "Hisse Senedi Alındı");
        } catch (\Exception $exception) {
            return redirect("/home")->with("message", $exception->getMessage());
        }

    }

    public function sellStock(Request $request, $id)
    {
        try {
            $this->stockService->sellStock($id);
            return redirect("/home")->with("message", "Hisse Senedi Satıldı");
        } catch (\Exception $exception) {
            return redirect("/home")->with("message", $exception->getMessage());
        }

    }

}
