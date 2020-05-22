<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {return view('welcome');});

Route::post('/login', 'Api\UserApi\UserApiController@index');

Route::middleware('auth:sanctum')->get('/users', 'Api\UserApi\UserApiController@users');
