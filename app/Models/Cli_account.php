<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cli_account extends Model
{
    protected $fillable = ['sale_id', 'client_id', 'total_price', 'pay_price', 'sub_price', 'acc_check', 'cash_type'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}


