<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable =[
        'first_name',
        'last_name',
        'email',
        'phone',
        'adress'
    ];

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function delete()
    {
        
        if($this->order()->count() >0){
            throw new \Exception("Cannot delete customer: it is associated with one or more orders.");
 
        }
        parent::delete();
    }

}