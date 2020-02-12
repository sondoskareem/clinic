<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;


class UserController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->middleware('Trusted', ['except' => ['login']]);
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
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:4',
            'isTrusted'=>'required'
        ];
        $this->validate($request ,$rules );
        $data = $request->all();
       
        //get user id from the token
        $data['manager_id'] =  JWTAuth::user()->id;
        $user = User::create($data);


        return $this->sucessMseeage('Success' ,  200);

    }

    //check if email & password correct 
    //if true generate token and pass it to respondWithToken() 

    public function login(Request $request){ 
        $credentials = request(['email', 'password']);

        if (! $token =auth('api')->attempt($credentials)) {
            return $this->errorResponse('Unauthorize' , 401);
        }
        return $this->respondWithToken($token);
    }

    //only to display the response 
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // ignore a given ID during the unique check
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'name' => 'required',
            'password' => 'required|confirmed|min:4',
        ];
        if($request->has('name')){
            $user->name = request('name');
        }
        if($request->has('email')){
            $user->email = request('email');
        }
        if($request->has('password')){
            $user->setPasswordAttribute(request('password'));
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
        if($user->isAdmin && $user->isTrusted){
            return $this->errorResponse('Unauthorize' , 401);
        }
        $user->delete();
        return $this->showOne($user);
    }
    
}
