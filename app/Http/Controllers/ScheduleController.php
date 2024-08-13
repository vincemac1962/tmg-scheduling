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
        // Step 1: Retrieve Schedule and Schedule Items
        $site = DB::table('sites')->where('site_ref', $siteId)->first();
        $siteId = $site->id;

        $schedule = DB::table('schedule_site')
            ->where('site_id', $siteId)
            ->join('schedules', 'schedule_site.schedule_id', '=', 'schedules.id')
            ->select('schedules.*')
            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'Schedule not found.'], 404);
        }

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

        // Step 2: Filter Items with Null Advertiser ID
        $nullAdvertiserItems = $scheduleItems->filter(function ($item) {
            return is_null($item->advertiser_id);
        });

        // Step 3: Retrieve and Merge Items with Advertiser ID
        $nonNullAdvertiserItems = $scheduleItems->filter(function ($item) {
            return !is_null($item->advertiser_id);
        });

        $mergedItems = $nullAdvertiserItems->merge($nonNullAdvertiserItems);

        // Step 4: Process Advertiser Data
        $advertiserData = [];
        $uniqueAdvertiserIds = $mergedItems->pluck('advertiser_id')->unique();

        foreach ($uniqueAdvertiserIds as $advertiserId) {
            if ($advertiserId) {
                $advertiser = DB::table('advertisers')
                    ->where('id', $advertiserId)
                    ->select(
                        'id',
                        'contract',
                        'business_name',
                        'address_1',
                        'address_2',
                        'street',
                        'city',
                        'county',
                        'postal_code',
                        'country',
                        'phone',
                        'mobile',
                        'email',
                        'url',
                        'social',
                        'banner',
                        'button',
                        'mp4'
                    )
                    ->first();

                $uploads = DB::table('uploads')
                    ->where('advertiser_id', $advertiserId)
                    ->select('resource_type', 'resource_filename')
                    ->get();

                foreach ($uploads as $upload) {
                    if ($upload->resource_type === 'ban') {
                        $advertiser->banner = $upload->resource_filename;
                    } elseif ($upload->resource_type === 'btn') {
                        $advertiser->button = $upload->resource_filename;
                    } elseif ($upload->resource_type === 'mp4') {
                        $advertiser->mp4 = $upload->resource_filename;
                    }
                }

                if ($advertiser) {
                    $advertiserData[] = $advertiser;
                }
            }
        }

        // Step 5: Create and Return Result Array
        $result[] = [
            'schedule' => $schedule,
            'items' => $mergedItems,
            'advertisers' => $advertiserData
        ];

        return response()->json($result);
    }

    public function getFile(Request $request)
    {
        // Validate the request to ensure 'file_path' is provided
        $request->validate([
            'file_path' => 'required|string'
        ]);

        // Retrieve the file path from the request
        $filePath = storage_path('app/public/' . $request->input('file_path'));

        // Log the file path for debugging
        Log::info('Checking file path: ' . $filePath);

        // Check if the file exists
        if (!file_exists($filePath)) {
            // Log the error
            Log::error('File not found at path: ' . $filePath);
            return response()->json(['error' => 'File not found.'], 404);
        }

        // Return the file as a response
        return response()->download($filePath);
    }






}
