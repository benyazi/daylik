<?php

namespace App\Http\Controllers;

use App\Models\DayTask;
use App\Models\UserDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function saveDayData(Request $request)
    {
        $userDay = $request->get('userDay');
        $userDayObj = UserDay::query()
            ->where('user_id', Auth::id())
            ->where('id', $userDay['id'])
            ->get()->first();
        if(empty($userDayObj)) {
            abort(404);
        }
        foreach ($userDay['tasks'] as $taskData) {
            $task = null;
            if(isset($taskData['id'])) {
                /** @var DayTask $task */
                $task = DayTask::query()
                    ->where('id', $taskData['id'])
                    ->where('day_id', $userDayObj->id)
                    ->get()->first();
                if($taskData['forRemove']) {
                    $task->delete();
                    continue;
                }
            }
            if(empty($task)) {
                $task = new DayTask();
                $task->day_id = $userDayObj->id;
            }
            $task->title = $taskData['title'];
            $task->status = $taskData['status'];
            $task->type = $taskData['type'];
            $task->save();
        }

        return [
            'success'=>true,
            'userDay' => $this->dayToArray($userDayObj)
        ];
    }

    /**
     * @param UserDay $userDay
     * @return array
     */
    public function dayToArray($userDay)
    {
        $day = $userDay->date;
        $day->setTime(0,0,0,0);
        $dayData = $userDay->toArray();
        $tasks = DayTask::query()
            ->where('day_id', $userDay->id)
            ->get();
        $taskArray = [];
        $count = count($tasks);
        $success = 0;
        foreach ($tasks as $task)
        {
            $taskData = $task->toArray();
            $taskData['isEditing'] = 0;
            $taskData['forRemove'] = 0;
            $taskArray[] = $taskData;
            if($task->status == DayTask::STATUS_DONE) {
                $success++;
            }
        }
        $dayData['tasks'] = $taskArray;
        $dayData['nav'] = [
            'current' => $day->format('Y-m-d'),
            'prev' => (clone $day)->subDay()->format('Y-m-d'),
            'next' => (clone $day)->addDay()->format('Y-m-d'),
        ];
        $dayData['stat'] = [
            'count' => $count,
            'success' => $success
        ];
        return $dayData;
    }

    public function getDayData(Request $request)
    {
        $date = $request->get('date', null);
        if($date) {
            $day = Carbon::createFromFormat('Y-m-d', $date);
        } else {
            $day = Carbon::now();
        }
        $day->setTime(0,0,0,0);
        $userDay = UserDay::query()
            ->where('user_id', Auth::id())
            ->where('date', $day)
            ->get()->first();
        if(empty($userDay)) {
            $userDay = UserDay::create([
                'user_id' => Auth::id(),
                'date' => $day
            ]);
        }
        return [
            'userDay' => $this->dayToArray($userDay)
        ];
    }
}
