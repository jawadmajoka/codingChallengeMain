<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnectionController;

/*------------- User connection routes file -------------*/
Route::resource('userconnection', ConnectionController::class);
