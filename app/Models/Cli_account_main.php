<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cli_account_main extends Model
{
    public $timestamps = false;
    protected $fillable = ['client_id','total_price','sub_price','pay_price', 'acc_check'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    } 
}
