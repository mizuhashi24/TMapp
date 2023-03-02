<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

use Inertia\Inertia;
use App\Models\SelectedTimer;
use App\Models\Timer;



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

    public function reset(Request $request)
    {
        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $selectedTimer = SelectedTimer::where('user_id', $userId)->firstOrFail();
            $selectedTimerPassedTime = $selectedTimer->passed_time + $request->passedTime;  // passedTime合計
            
            $timerId = $selectedTimer->timer_id;
            $timer = Timer::where('id', $timerId)->firstOrFail();
            if ($timer->set_seconds * 1000 <= $selectedTimerPassedTime) {
                $timer->total_seconds += $timer->set_seconds * 1000;
            } else {
                $timer->total_seconds += $selectedTimerPassedTime;
            }
            $timer->save();

            $selectedTimer->start_time = $request->startTime;
            $selectedTimer->passed_time = 0;
            $selectedTimer->status = $request->status;
            $selectedTimer->save();

            DB::commit();


        } catch ( Exception $e ) {

            DB::rollBack();

        }
    }
}
