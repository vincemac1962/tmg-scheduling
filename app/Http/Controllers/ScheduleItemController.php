<?php

namespace App\Http\Controllers;

use App\Models\ScheduleItem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ScheduleItemController extends Controller
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
        $query = ScheduleItem::query();
        // Filtering
        if ($request->has('filter')) {
            $query->where('schedule_id', 'like', '%' . $request->filter . '%')
                ->orWhere('file', 'like', '%' . $request->filter . '%');;
        }
        // Sorting
        if ($request->has('sort_by')) {
            $direction = $request->direction ?? 'asc';
            $query->orderBy($request->sort_by, $direction);
        }
        $scheduleItems = $query->paginate(10);
        $header = 'Schedule Items Index';
        return view('schedule_items.index', compact('scheduleItems', 'header'));
    }

    // create method
    public function create()
    {
        return view('schedule_items.create');
    }

    // store method
    public function store(Request $request)
    {
        //
    }

    // show method
    /* public function show($id)
    {
        $scheduleItem = ScheduleItem::find($id);
        $data = array(
            'header' => 'Schedule Item Details',
            'item' => $scheduleItem);
        return view('schedule_items.show')->with($data);
    } */

    public function show(int $id)
    {
        $scheduleItem = ScheduleItem::with('upload')->find($id);

        if ($scheduleItem === null) {
            // Handle the case where the schedule item was not found
            abort(404);
        }

        $data = array(
            'header' => 'Schedule Item Details',
            'scheduleItem' => $scheduleItem
        );
        //dd($data);
        return view('schedule_items.show')->with($data);
    }

}
