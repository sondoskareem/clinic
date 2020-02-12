<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Formula;
class PatientController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api',);
        $this->middleware('Trusted');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * 
     *  This registeration for employee and patient ,
     *   Its required a token
     */
    public function store(Request $request)
    {
        $rules = [
            'description' => 'required',
            'patient_id' => 'required',
        ];
        $this->validate($request ,$rules );
        $data = $request->all();
        $user = Formula::create($data);


        return $this->sucessMseeage('Success' ,  200);

    }
}
