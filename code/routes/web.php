<?php

use Illuminate\Support\Facades\Route;

Route::get("/", "HomeController@index")->name("home");
Route::post("/findAccount", "HomeController@postFindAccount");

Route::get("/{account}", "HomeController@showAccount");