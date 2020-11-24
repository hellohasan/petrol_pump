<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $guarded = [''];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


}
