<?php

use App\Http\Controllers\LogMonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('log-monitoring/', [LogMonitoringController::class, 'show']);
