<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use App\Models\Schedule;
use App\Models\Upload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     * @method static Builder orderBy(string $column, string $direction = 'asc')
     */

    // index method
    public function index(Request $request)
    {
        // unset the session variable
        session()->forget('schedule_id');

        $query = Schedule::query();

        $schedules = $query->paginate(10);

        $header = 'Schedules Index';

        return view('schedules.index', compact('schedules', 'header'));
    }


    /*
    public function show(int $id)
    {
        $schedule = Schedule::with('scheduleItems')->find($id);
        $data = array(
            'header' => 'Schedule Details',
            'schedule' => $schedule);
        return view('schedules.show')->with($data);
    } */

    public function show($id)
    {
        $schedule = Schedule::findOrFail($id);
        session(['schedule_id' => $id]);
        $scheduleItems = $schedule->scheduleItems()->paginate(10);

        return view('schedules.show', compact('schedule', 'scheduleItems'));
    }


    // edit method
    public function edit(int $id)
    {
        // find site by id
        $schedule = Schedule::find($id);
        $data = array(
            'header' => 'Edit Schedule',
            'schedule' => $schedule);
        return view('schedules.edit')->with($data);
    }

    // update method
    public function update(Request $request, int $id)
    {
        $schedule = Schedule::find($id);
        $schedule->update($request->all());
        return redirect()->route('schedules.index');
    }

    // create method
    public function create()
    {
        $data = array(
            'header' => 'Create Schedule',
            'user_id' => auth()->id()
        );
        return view('schedules.create')->with($data);
    }

    // store method
    public function store(Request $request)
    {
        Schedule::create($request->all());
        return redirect()->route('schedules.index');
    }

    // destroy method
    public function destroy(int $id)
    {
        $schedule = Schedule::find($id);
        $schedule->delete();
        return redirect()->route('schedules.index');
    }

    public function addSelectedAdvertisers(Request $request)
    {
        $advertiserIds = $request->input('advertiser_ids', []);
        $scheduleId = session('schedule_id');

        foreach ($advertiserIds as $advertiserId) {
            $uploads = Upload::where('advertiser_id', $advertiserId)->get();

            foreach ($uploads as $upload) {
                $scheduleItem = new \App\Models\ScheduleItem();
                $scheduleItem->schedule_id = session('schedule_id');
                $scheduleItem->upload_id = $upload->id;
                $scheduleItem->advertiser_id = $advertiserId;
                $scheduleItem->file = $upload->resource_path . $upload->resource_filename;
                $scheduleItem->created_by = auth()->id();
                $scheduleItem->save();
            }
        }

        return redirect()->route('schedules.show', ['schedule' => $scheduleId]);
    }


}
