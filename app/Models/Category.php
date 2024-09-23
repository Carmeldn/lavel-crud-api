<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'description',
    ];
    
    public function products(){
        return $this->hasMany(Product::class);
    }

    public function delete()
    {
        if ($this->products()->count() > 0) {
            throw new \Exception("Cannot delete category: it is associated with one or more products.");
        }
    
        parent::delete();
    }
    
}