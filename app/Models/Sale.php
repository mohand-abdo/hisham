<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['total_price', 'user_id', 'client_id', 'inv_type', 'bank_id', 'uncash_type', 'inv_chack', 'pay_date'];

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot(['qty', 'item_price']);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function cli_account()
    {
        return $this->hasOne(Cli_account::class);
    }

    public function client_trans()
    {
        return $this->hasMany(Client_trans::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);    
    }
}
