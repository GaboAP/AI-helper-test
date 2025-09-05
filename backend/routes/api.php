<?php

use App\Http\Controllers\SuggestionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/suggestions', SuggestionsController::class)
    ->middleware('throttle:20,1');
