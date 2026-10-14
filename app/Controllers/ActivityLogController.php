<?php

namespace App\Controllers;

use Sakuci\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::latest('id')->limit(100)->get();

        return view('log/index', ['logs' => $logs]);
    }
}