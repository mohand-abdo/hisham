<?php

namespace App\Models;

use App\models\Category;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'category_id', 'purches_price', 'sale_price', 'qty'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class)->withPivot('qty');
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withPivot(['qty','item_price']);
    }
    
    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot(['qty','item_price']);
    }
}
