<?php

use Illuminate\Support\Facades\Route;

Route::get("/", "HomeController@index")->name("home");
Route::get("/{account}", "HomeController@showAccount");