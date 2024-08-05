<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\Upload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function associateSites(Request $request, $scheduleId) {
        $schedule = Schedule::findOrFail($scheduleId);

        // Retrieve the existing associated site IDs
        $existingSiteIds = $schedule->sites()->pluck('site_id')->toArray();

        // Merge existing site IDs with new site IDs from the request
        $newSiteIds = array_merge($existingSiteIds, $request->input('sites', []));

        // Use syncWithoutDetaching to associate sites without removing existing ones
        $schedule->sites()->syncWithoutDetaching($newSiteIds);

        return redirect()->route('schedules.show', $scheduleId);
    }

    // show associated sites
    public function showAssociatedSites($scheduleId) {
        $schedule = Schedule::findOrFail($scheduleId);
        $sites = $schedule->sites()->select('sites.id', 'site_ref', 'site_name', 'site_address')->get();
        $header = 'Associated Sites for Schedule ' . $scheduleId;
        return view('schedules.associated_sites', compact('sites', 'header', 'scheduleId'));
    }

    // remove associated site
    public function removeAssociatedSite($scheduleId, $siteId) {
        $schedule = Schedule::findOrFail($scheduleId);
        $schedule->sites()->detach($siteId);
        return redirect()->route('schedules.associatedSites', $scheduleId);
    }

    // API method returns schedules for a given site
    public function getSchedules($siteId)
    {
        $schedules = DB::table('schedules')
            ->join('schedule_site', 'schedules.id', '=', 'schedule_site.schedule_id')
            ->where('schedule_site.site_id', $siteId)
            ->where('schedule_site.downloaded', false)
            ->select('schedules.id', 'schedules.title', 'schedules.created_at', 'schedules.updated_at')
            ->get();

        $result = [];

        foreach ($schedules as $schedule) {
            $scheduleItems = DB::table('schedule_items')
                ->leftJoin('uploads', 'schedule_items.upload_id', '=', 'uploads.id')
                ->where('schedule_items.schedule_id', $schedule->id)
                ->select(
                    'schedule_items.id',
                    'schedule_items.schedule_id',
                    'schedule_items.advertiser_id',
                    'schedule_items.title',
                    'schedule_items.start_date',
                    'schedule_items.end_date',
                    'schedule_items.file',
                    'uploads.resource_type'
                )
                ->get();

            $filteredItems = $scheduleItems->filter(function ($item) {
                if (is_null($item->file)) {
                    Log::warning('Schedule item skipped due to null file', ['item_id' => $item->id]);
                    return false;
                }
                return true;
            });

            $result[] = [
                'schedule' => $schedule,
                'items' => $filteredItems
            ];
        }

        return response()->json($result);
    }






}
