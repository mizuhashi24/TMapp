<?php

namespace App\Http\Controllers;

use App\Models\Timer;
use App\Http\Requests\StoreTimerRequest;
use App\Http\Requests\UpdateTimerRequest;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\SelectedTimer;

class TimerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        return Inertia::render('Timers/Index', [
            'timers' => Timer::select('id', 'name', 'memo', 'set_seconds', 'total_seconds', 'used_seconds', 'status', 'updated_at')
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get(),
            'selectedTimer' => SelectedTimer::select('timer_id', 'start_time', 'passed_time', 'status')
            ->where('user_id', $userId)
            ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTimerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimerRequest $request)
    {
        $userId = Auth::id();

        Timer::create([
            'user_id' => $userId,
            'name' => $request->name,
            'memo' => $request->memo,
            'set_seconds' => $request->setSeconds,
        ]);

        to_route('timers.index')->with([
            'message' => 'タイマーを新規登録しました。',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function show(Timer $timer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function edit(Timer $timer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTimerRequest  $request
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTimerRequest $request, Timer $timer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timer $timer)
    {
        //
    }
}
