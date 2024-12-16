<?php

namespace App\models;

use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class Sup_account extends Model
{
    protected $fillable = ['purchase_id', 'supplier_id','total_price', 'pay_price', 'sub_price', 'acc_check', 'cash_type'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}


