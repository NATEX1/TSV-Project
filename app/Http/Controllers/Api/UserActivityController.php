<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserActivityController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $logFile = storage_path("logs/user_activity-{$date}.log");

        if (!File::exists($logFile)) {
            return response()->json(['data' => []]);
        }

        $lines = File::lines($logFile);
        $logs = [];

        foreach ($lines as $line) {
            $parts = explode('|', $line);

            if (count($parts) >= 4) {
                $logs[] = [
                    'time' => trim($parts[0]),
                    'ip' => trim($parts[1]),
                    'username' => trim($parts[2]),
                    'action' => trim($parts[3]),
                ];
            }
        }

        $logs = array_reverse($logs);
        return response()->json(['data' => $logs]);
    }
}
