<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table ="value";
    public $timestamps = false;


   public function stock() {
       return $this->belongsTo(Stock::class);
   }


}
