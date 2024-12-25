<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogMonitoringController extends Controller 
{
    public function show(Request $request) 
    {
        for ($x = 0; $x <= 5; $x++) {
            Log::error(__('Log alarm'));
        }
    }
}
