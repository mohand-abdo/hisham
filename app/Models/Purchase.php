<?php

namespace App\Models;

use App\models\Supplier;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['inv_no', 'total_price', 'user_id', 'inv_date', 'supplier_id', 'inv_type', 'bank_id', 'uncash_type', 'inv_chack' ,'pay_date'];

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot(['qty','item_price']);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function sup_account()
    {
        return $this->hasOne(Sup_account::class);
    }

    public function supplier_trans()
    {
        return $this->hasMany(Supplier_trans::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
