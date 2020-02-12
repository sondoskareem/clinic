<?php

namespace App;


class Patient extends User
{
    //patient can request many formula
    public function formula(){
        return $this->hasMany(Formula::class);
    }
   
}
