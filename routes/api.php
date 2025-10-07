<?php

use App\Http\Controllers\Api\UserActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/api/user-activity', [UserActivityController::class, 'index']);
