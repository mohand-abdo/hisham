<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\SameSize;

class Client extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'friend', 'supplier_id'];
    protected $casts   = ['phone' => 'array'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function money_trans()
    {
        return $this->hasMany(Money_trans::class);
    }

    public function cli_account_mains()
    {
        return $this->hasMany(Cli_account_main::class);
    }

    public function friend_purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id', 'supplier_id')->where(['inv_chack' => 0, 'inv_type' => 3]);
    }

    public function friend_sales()
    {
        return $this->hasMany(Sale::class, 'client_id')->where(['inv_chack' => 0, 'inv_type' => 3]);
    }
}
