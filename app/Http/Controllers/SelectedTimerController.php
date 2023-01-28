<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SelectedTimer;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class SelectedTimerController extends Controller
{
    public function toggle(Request $request)
    {
        $userId = Auth::id();
        $selectedTimer = SelectedTimer::where('user_id', $userId)->firstOrFail();
        $selectedTimer->start_time = $request->startTime;
        $selectedTimer->passed_time = $request->passedTime;
        $selectedTimer->status = $request->status;
        $selectedTimer->save();
    }
}
