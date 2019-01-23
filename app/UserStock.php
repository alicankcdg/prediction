<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStock extends Model
{
    protected $table = "user_stock";

    public function stocks() {
        return $this->belongsTo(Stock::class,"stock_id","id");
    }
}


