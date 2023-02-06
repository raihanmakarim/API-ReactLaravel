<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'apidocs');

Route::view('/users', 'userlist');
