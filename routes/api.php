<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\CourseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('adduser', 'App\Http\Controllers\AuthController@adduser');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('me', 'App\Http\Controllers\AuthController@me');
    Route::get('show','App\Http\Controllers\AuthController@show');
    Route::delete('delete/{id}', 'App\Http\Controllers\AuthController@delete');
    Route::put('updateuser/{id}', 'App\Http\Controllers\AuthController@updateUser');
    Route::post('deletes', 'App\Http\Controllers\AuthController@deletes');
    Route::post('resetpassword', 'App\Http\Controllers\AuthController@resetpassword');

    Route::post('setimage/{id}', 'App\Http\Controllers\AuthController@setImage');
    Route::post('updatecourse/{id}', 'App\Http\Controllers\AuthController@updateCourse');


  
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'course'

], function ($router) {

    Route::post('add', 'App\Http\Controllers\CourseController@add');
    Route::get('show', 'App\Http\Controllers\CourseController@show');
    Route::delete('delete/{id}', 'App\Http\Controllers\CourseController@delete');
    Route::post('deletes', 'App\Http\Controllers\CourseController@deletes');
    Route::put('update/{id}', 'App\Http\Controllers\CourseController@update');
 
    // courseRegister
    Route::post('register','App\Http\Controllers\RegisterController@add');   
    Route::get('courseuser/{id}','App\Http\Controllers\RegisterController@CoureUser');   
    Route::get('showregister','App\Http\Controllers\RegisterController@show');   
    Route::delete('deletecourse/{id}', 'App\Http\Controllers\RegisterController@deletecourse');
    Route::post('adduser','App\Http\Controllers\RegisterController@addUser');   

    Route::post('line','App\Http\Controllers\RegisterController@notifyMessage');   
    
    
    Route::get('profile','App\Http\Controllers\RegisterController@showCourseUser');   
    Route::get('registeruser/{id}','App\Http\Controllers\RegisterController@showRegistration');   
    Route::delete('deleteregister', 'App\Http\Controllers\RegisterController@deleteRegister');
    Route::put('updateregister','App\Http\Controllers\RegisterController@update');   
  
  
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'line'

], function ($router) {
    Route::get('show', 'App\Http\Controllers\LineTokenController@token');
    Route::put('edit', 'App\Http\Controllers\LineTokenController@editToken');
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'products'

], function ($router) {

    Route::get('frontend',[\App\Http\Controllers\ProductController::class,'frontend']);
    Route::get('backend',[\App\Http\Controllers\ProductController::class,'backend']);

    
  
});