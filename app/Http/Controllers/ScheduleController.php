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
    // ScheduleController.php

    public function index(Request $request)
    {
        // Unset the session variable
        session()->forget('schedule_id');

        // Check if the user wants to view all schedules
        $viewAll = $request->input('view_all', false);

        // Retrieve schedules with the necessary conditions
        $query = Schedule::withCount(['sites', 'sites as downloaded_sites_count' => function ($query) {
            $query->where('downloaded', true);
        }]);

        if (!$viewAll) {
            $query->havingRaw('downloaded_sites_count < sites_count OR sites_count = 0');
        }

        $schedules = $query->paginate(10);

        $header = 'Schedules Index';

        return view('schedules.index', compact('schedules', 'header', 'viewAll'));
    }
    /*public function index(Request $request)
    {
        // unset the session variable
        session()->forget('schedule_id');

        $query = Schedule::query();

        $schedules = $query->paginate(10);

        $header = 'Schedules Index';

        return view('schedules.index', compact('schedules', 'header'));
    }*/

    public function show($id)
    {
        $schedule = Schedule::withCount(['sites', 'sites as downloaded_sites_count' => function ($query) {
            $query->where('downloaded', true);
        }])->findOrFail($id);

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
    // used to return a list of advertisers without upload items
    public function getAdvertiserDetails($advertiserId)
    {
        // find advertiser by id
        $advertiser = Advertiser::find($advertiserId);
        if ($advertiser) {
            // return advertiser contract and business name
            return $advertiser->contract . ' - ' . $advertiser->business_name;
        }
        return null;
    }

    public function addSelectedAdvertisers(Request $request)
    {
        $advertiserIds = $request->input('advertiser_ids', []);
        $scheduleId = session('schedule_id');

        foreach ($advertiserIds as $advertiserId) {
            $uploads = Upload::where('advertiser_id', $advertiserId)->get();

            if ($uploads->isEmpty()) {
                $error = 'Uploads missing for: ' . $this->getAdvertiserDetails($advertiserId);
                if ($error) {
                    session()->flash('errors', session('errors', new \Illuminate\Support\MessageBag)->add('advertiser_error', $error));
                }
                continue;
            }

            foreach ($uploads as $upload) {
                $scheduleItem = new \App\Models\ScheduleItem();
                $scheduleItem->schedule_id = $scheduleId;
                $scheduleItem->upload_id = $upload->id;
                $scheduleItem->advertiser_id = $advertiserId;
                $scheduleItem->file = $upload->resource_path;
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
        $sites = $schedule->sites()
            ->select('sites.id', 'site_ref', 'site_name', 'site_address')
            ->withPivot('downloaded', 'downloaded_at')
            ->get();
        //var_dump($sites);
        //die();
        $header = 'Associated Sites for Schedule ' . $scheduleId;
        return view('schedules.associated_sites', compact('sites', 'header', 'scheduleId'));
    }


    // remove associated site
    public function removeAssociatedSite($scheduleId, $siteId) {
        $schedule = Schedule::findOrFail($scheduleId);
        $schedule->sites()->detach($siteId);
        return redirect()->route('schedules.associatedSites', $scheduleId);
    }








}
