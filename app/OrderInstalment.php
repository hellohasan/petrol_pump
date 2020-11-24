<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInstalment extends Model
{
    use SoftDeletes;

    protected $table = 'order_instalments';

    protected $guarded = [''];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

}
