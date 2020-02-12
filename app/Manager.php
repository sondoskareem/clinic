<?php

namespace App;
use App\Employee;

class Manager extends User
{
    public function employees(){
        return $this->hasMany(Employee::class);
    }
}
