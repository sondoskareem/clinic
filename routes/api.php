<?php

use Illuminate\Http\Request;

//create manager
//no token required
Route::resource('manager' , 'Manager\MangerController' , ['only' => ['store' ,'update', 'destroy']]);

//only by manager
Route::resource('roles' , 'Role\RoleController' , ['only' => ['store']]);

Route::resource('patient/formula' , 'Patient\PatientController' , ['only' => ['store']]);


Route::resource('users' , 'User\UserController' , ['expect' => ['create' ,'show', 'edite']]);


//login for all users 
Route::post('users/login' , 'User\UserController@login');
