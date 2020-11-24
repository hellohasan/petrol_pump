<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repayment extends Model
{
    use SoftDeletes;
    protected $table = 'repayments';

    protected $guarded = [''];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

}
