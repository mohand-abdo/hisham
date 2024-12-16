<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'phone', 'address','friend', 'client_id'];
    protected $casts   = ['phone' => 'array'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function money_trans()
    {
        return $this->hasMany(Money_trans::class);
    }

    public function sup_account_mains()
    {
        return $this->hasMany(Sup_account_main::class);
    }
}
