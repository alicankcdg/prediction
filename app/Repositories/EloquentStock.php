<?php
/**
 * Created by PhpStorm.
 * User: roketworks
 * Date: 22.01.2019
 * Time: 13:48
 */

namespace App\Repositories;


use App\Stock;

class EloquentStock implements StockRepository
{

    public $stock;


    /**
     * EloquentStock constructor.
     * @param $stock
     */
    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function getAll()
    {
        return $this->stock->all();
    }
}
