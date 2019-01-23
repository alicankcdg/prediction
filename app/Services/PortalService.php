<?php
/**
 * Created by PhpStorm.
 * User: roketworks
 * Date: 22.01.2019
 * Time: 12:32
 */

namespace App\Services;


use App\Repositories\StockRepository;
use App\Stock;
use App\Value;
use function Couchbase\defaultDecoder;
use function MongoDB\BSON\toJSON;

class PortalService
{
    public $stockRepository;
    private $domain =  "https://portal-widgets-v3.foreks.com/periodic-tabs/data";

    /**
     * PortalService constructor.
     * @param $stockRepository
     */
    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }


    // Bu işlem  app içinde sürekli yapılıcaksa scheduling yapılmalı  benim uygulamaya başlamam
    // için valueları 1 kere yapmam yeterli olduğundan timeout süresini uzatarak url üzerinden aldım
    public function insertAllValues () {
        ini_set('max_execution_time', 1000);
        $stocks = $this->stockRepository->getAll();
        foreach ($stocks as $stock) {
           $stockId = $stock->id;
           $stockCode = $stock->code;

            $dataSet = $this->getValues($stockCode);
            $values = $dataSet->data;

            foreach ($values as $value) {

                $dbValue = New Value();
                $dbValue->stock_id = $stockId;
                $dbValue->time = (int)substr($value[0],0,10);
                $dbValue->value = $value[1];
                $dbValue->save();
            }
        }
        return true ;
    }


    private function getValues($code) {
        $text = "id=graph1&code=".$code.".E.BIST&period=1W&intraday=60&delay=15&linecolor=%23ffd355";
        return $this->postCurl($text);
    }

    private function postCurl($text) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->domain,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $text,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return \GuzzleHttp\json_decode($response);
    }




}
