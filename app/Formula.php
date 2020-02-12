<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formula extends Model
{
    use SoftDeletes;
    protected $date = ['deleted_at'];
    
    protected $fillable = [
        'description',
        'patient_id'
    ];

    //formula belongs to one patient
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
