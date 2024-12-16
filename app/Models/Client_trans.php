<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client_trans extends Model
{
    protected $fillable = ['client_id', 'sale_id', 'to', 'from'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
