<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    protected $date = ['deleted_at'];
    
    protected $fillable = [
        'job_description'
    ];

    // role belongs to one user
    public function users(){
        return $this->hasMany(User::class);
    }
}
