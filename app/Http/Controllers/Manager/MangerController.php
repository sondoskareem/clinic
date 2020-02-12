<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\User;
class MangerController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api',['except' => [ 'store']]);
        $this->middleware('Admin', ['except' => [ 'store']]);

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     *  display each employee with its manager
     */
    public function index()
    {
        $results = DB::select( DB::raw(
            "SELECT employee.name as employeeName ,
                    employee.email as employeeEmail ,
                    patient.name as patientName ,
                    patient.email as patientEmail
                  
            FROM
                users employee
            INNER JOIN users patient ON employee.id = patient.manager_id 
                                    AND patient.role_id = 3
           "
            ));
    
            return response()->json(['data' =>$results]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * manager_id && role_id by default null
     * cuz first user will have no manager and no role
     * roles can added only by manager
     */
    
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:4',
        ];
       
        $this->validate($request ,$rules );
        $data = $request->all();
        $data['isAdmin'] = 'true';
        $data['isTrusted'] = 'true';
        $user = User::create($data);

        return $this->sucessMseeage('Success' ,  200);

    }


    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'isActive' => 'required',
        ];
        if($request->has('isActive')){
            $user->isActive = request('isActive');
        }
       
        $user->save();
        return $this->showOne($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }
    
}
