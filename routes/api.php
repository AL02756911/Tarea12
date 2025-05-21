<?php

use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\CharacterController;

Route::apiResource('media', MediaController::class);
Route::apiResource('characters', CharacterController::class);