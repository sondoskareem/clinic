<?php

namespace App;
use App\Manager;

class Employee extends User
{
    public function manager(){
        return $this->belongsTo(Manager::class);
    }
}
