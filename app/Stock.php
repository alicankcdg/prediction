<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = "stock";
    public $timestamps = false;



    public function getCodeName () {
        return $this->code;
    }


    public function values() {
        return $this->hasMany(Value::class,"stock_id","id");
    }




}
