<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiScheduleController extends Controller
{
    public function getSchedules($siteId)
    {
        // Retrieve the site by site_ref
        $site = DB::table('sites')->where('site_ref', $siteId)->first();
        if (!$site) {
            return response()->json(['error' => 'Site not found.'], 404);
        }
        $siteId = $site->id;

        // Retrieve schedules where 'downloaded' is false
        $schedules = DB::table('schedule_site')
            ->where('site_id', $siteId)
            ->where('downloaded', false)
            ->join('schedules', 'schedule_site.schedule_id', '=', 'schedules.id')
            ->select('schedules.*')
            ->get();

        // Check if any schedules are found
        if ($schedules->isEmpty()) {
            return response()->json(['message' => 'No schedules available for download.']);
        }

        $scheduleItems = collect();

        foreach ($schedules as $sched) {
            $items = DB::table('schedule_items')
                ->leftJoin('uploads', 'schedule_items.upload_id', '=', 'uploads.id')
                ->where('schedule_items.schedule_id', $sched->id)
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

            $scheduleItems = $scheduleItems->merge($items);
        }

        // Filter Items with Null Advertiser ID
        $nullAdvertiserItems = $scheduleItems->filter(function ($item) {
            return is_null($item->advertiser_id);
        });

        // Retrieve and Merge Items with Advertiser ID
        $nonNullAdvertiserItems = $scheduleItems->filter(function ($item) {
            return !is_null($item->advertiser_id);
        });

        $mergedItems = $nullAdvertiserItems->merge($nonNullAdvertiserItems);

        // Process Advertiser Data
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

        // Create and Return Result Array
        $result[] = [
            'schedules' => $schedules,
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
        //$filePath = storage_path('app/public/' . $request->input('file_path'));
        $filePath = public_path('storage/' . $request->input('file_path'));

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

    // log schedule upload
    public function logScheduleUpload(Request $request)
    {
        // Validate the request to ensure 'schedule_id' and 'site_id' are provided
        $request->validate([
            'schedule_id' => 'required|integer',
            'site_id' => 'required|integer'
        ]);

        // Retrieve the schedule_id and site_id from the request
        $scheduleId = $request->input('schedule_id');
        $siteId = $request->input('site_id');

        // Update the downloaded and downloaded_at columns with true and the current date and time
        $updated = DB::table('schedule_site')
            ->where('schedule_id', $scheduleId)
            ->where('site_id', $siteId)
            ->update([
                'downloaded' => true,
                'downloaded_at' => now()
            ]);

        // Check if the update was successful
        if ($updated) {
            return response()->json(['success' => 'Download status and time updated successfully.']);
        } else {
            return response()->json(['error' => 'Failed to update download status and time.'], 500);
        }
    }

    public function getSiteId(Request $request)
    {
        // Validate the request to ensure 'site_ref' is provided
        $request->validate([
            'site_ref' => 'required|string'
        ]);

        // Retrieve the site_ref from the request
        $siteRef = $request->input('site_ref');

        // Query the sites table to get the id corresponding to the site_ref
        $site = DB::table('sites')
            ->where('site_ref', $siteRef)
            ->select('id')
            ->first();

        // Check if the site was found
        if ($site) {
            return response()->json(['id' => $site->id]);
        } else {
            return response()->json(['error' => 'Site not found.'], 404);
        }
    }

}