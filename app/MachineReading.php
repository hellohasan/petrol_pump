<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineReading extends Model
{
    protected $table = 'machine_readings';

    protected $guarded = [''];

    public function machine()
    {
        return $this->belongsTo(Machine::class,'machine_id');
    }
}
