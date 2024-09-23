<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        'category_id',
        'name',
        'quantity',
        'price'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function delete()
    {
        if($this->order()->count() >0){
            throw new \Exception("Cannot delete product: it is associated with one or more orders.");

        }
        parent::delete();
    }
}
