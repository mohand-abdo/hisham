<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sup_account_main extends Model
{
    public $timestamps = false;
    protected $fillable = ['supplier_id', 'total_price', 'sub_price', 'pay_price', 'acc_check'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
