<?php

namespace App\Http\Controllers\Role;
use App\Http\Controllers\ApiController;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
        $this->middleware('Admin');

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'job_description' => 'required|unique:roles',
        ];
        $this->validate($request ,$rules );
        $data = $request->all();
        $data['job_description'] = $request->job_description;
      
        $Role = Role::create($data);


        return $this->sucessMseeage('New role added' ,  200);

    }

}
