<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class supplier_trans extends Model
{
    protected $fillable = ['supplier_id','purchase_id','to','from'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
